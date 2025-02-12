import { adminBaseRoutePath } from '/@/router/static/adminBase'

/*
 * 語言包按需加載映射表
 * 使用固定字符串 ${lang} 指代當前語言
 * key 為頁面 path，value 為語言包文件相對路徑，訪問時，按需自動加載映射表的語言包，同時加載 path 對應的語言包（若存在）
 */
export default {
    '/': ['./frontend/${lang}/index.ts'],
    [adminBaseRoutePath + '/moduleStore']: ['./backend/${lang}/module.ts'],
    [adminBaseRoutePath + '/user/rule']: ['./backend/${lang}/auth/rule.ts'],
    [adminBaseRoutePath + '/user/scoreLog']: ['./backend/${lang}/user/moneyLog.ts'],
    [adminBaseRoutePath + '/crud/crud']: ['./backend/${lang}/crud/log.ts', './backend/${lang}/crud/state.ts'],
}
