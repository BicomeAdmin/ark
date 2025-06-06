import type { App } from 'vue'
import { createI18n } from 'vue-i18n'
import type { I18n, Composer } from 'vue-i18n'
import { useConfig } from '/@/stores/config'
import { isEmpty } from 'lodash-es'

/*
 * 默認只引入 element-plus 的中英文語言包
 * 其他語言包請自行在此 import,並添加到 assignLocale 內
 * 動態 import 只支持相對路徑，所以無法按需 import element-plus 的語言包
 * 但i18n的 messages 內是按需載入的
 */
import elementZhcnLocale from 'element-plus/es/locale/lang/zh-cn'
import elementEnLocale from 'element-plus/es/locale/lang/en'

export let i18n: {
    global: Composer
}

// 準備要合併的語言包
const assignLocale: anyObj = {
    'zh-cn': [elementZhcnLocale],
    en: [elementEnLocale],
}

export async function loadLang(app: App) {
    const config = useConfig()
    const locale = config.lang.defaultLang

    // 加載框架全局語言包
    const lang = await import(`./globs-${locale}.ts`)
    const message = lang.default ?? {}

    // 按需加載語言包文件的句柄
    if (locale == 'zh-cn') {
        window.loadLangHandle = {
            ...import.meta.glob('./backend/zh-cn/**/*.ts'),
            ...import.meta.glob('./frontend/zh-cn/**/*.ts'),
            ...import.meta.glob('./backend/zh-cn.ts'),
            ...import.meta.glob('./frontend/zh-cn.ts'),
        }
    } else {
        window.loadLangHandle = {
            ...import.meta.glob('./backend/en/**/*.ts'),
            ...import.meta.glob('./frontend/en/**/*.ts'),
            ...import.meta.glob('./backend/en.ts'),
            ...import.meta.glob('./frontend/en.ts'),
        }
    }

    /*
     * 加載頁面語言包 import.meta.glob 的路徑不能使用變量 import() 在 Vite 中目錄名不能使用變量(編譯後,文件名可以)
     */
    if (locale == 'zh-cn') {
        assignLocale[locale].push(getLangFileMessage(import.meta.glob('./common/zh-cn/**/*.ts', { eager: true }), locale))
    } else if (locale == 'en') {
        assignLocale[locale].push(getLangFileMessage(import.meta.glob('./common/en/**/*.ts', { eager: true }), locale))
    }

    const messages = {
        [locale]: {
            ...message,
        },
    }

    // 合併語言包(含element-puls、頁面語言包)
    Object.assign(messages[locale], ...assignLocale[locale])

    i18n = createI18n({
        locale: locale,
        legacy: false, // 組合式api
        globalInjection: true, // 掛載$t,$d等到全局
        fallbackLocale: config.lang.fallbackLang,
        messages,
    })

    app.use(i18n as I18n)
    return i18n
}

function getLangFileMessage(mList: any, locale: string) {
    let msg: anyObj = {}
    locale = '/' + locale
    for (const path in mList) {
        if (mList[path].default) {
            //  獲取文件名
            const pathName = path.slice(path.lastIndexOf(locale) + (locale.length + 1), path.lastIndexOf('.'))
            if (pathName.indexOf('/') > 0) {
                msg = handleMsglist(msg, mList[path].default, pathName)
            } else {
                msg[pathName] = mList[path].default
            }
        }
    }
    return msg
}

export function mergeMessage(message: anyObj, pathName = '') {
    if (isEmpty(message)) return
    if (!pathName) {
        return i18n.global.mergeLocaleMessage(i18n.global.locale.value, message)
    }
    let msg: anyObj = {}
    if (pathName.indexOf('/') > 0) {
        msg = handleMsglist(msg, message, pathName)
    } else {
        msg[pathName] = message
    }
    i18n.global.mergeLocaleMessage(i18n.global.locale.value, msg)
}

export function handleMsglist(msg: anyObj, mList: anyObj, pathName: string) {
    const pathNameTmp = pathName.split('/')
    let obj: anyObj = {}
    for (let i = pathNameTmp.length - 1; i >= 0; i--) {
        if (i == pathNameTmp.length - 1) {
            obj = {
                [pathNameTmp[i]]: mList,
            }
        } else {
            obj = {
                [pathNameTmp[i]]: obj,
            }
        }
    }
    return mergeMsg(msg, obj)
}

export function mergeMsg(msg: anyObj, obj: anyObj) {
    for (const key in obj) {
        if (typeof msg[key] == 'undefined') {
            msg[key] = obj[key]
        } else if (typeof msg[key] == 'object') {
            msg[key] = mergeMsg(msg[key], obj[key])
        }
    }
    return msg
}

export function editDefaultLang(lang: string): void {
    const config = useConfig()
    config.setLang(lang)

    /*
     * 語言包是按需加載的,比如默認語言為中文,則只在app實例內加載了中文語言包,所以切換語言需要進行 reload
     */
    location.reload()
}
