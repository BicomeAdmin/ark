<template>
    <!-- 對話框表單 -->
    <el-dialog
        class="ba-operate-dialog"
        :close-on-click-modal="false"
        :model-value="['Add', 'Edit'].includes(baTable.form.operate!)"
        @close="baTable.toggleForm"
        width="50%"
    >
        <template #header>
            <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
            </div>
        </template>
        <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
            <div
                class="ba-operate-form"
                :class="'ba-' + baTable.form.operate + '-form'"
                :style="'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="right"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem
                        :label="t('routine.dataimport.data_table')"
                        type="select"
                        v-model="baTable.form.items!.data_table"
                        prop="data_table"
                        :data="{ content: baTable.form.extend!.tableList }"
                        :placeholder="t('Please select field', { field: t('routine.dataimport.data_table') })"
                    />
                    <el-form-item label="導入模板">
                        <div v-if="baTable.form.items!.data_table" @click="onDownloadImportTemplate" class="template-text-success">
                            點擊下載導入模板文件
                        </div>
                        <div v-else class="template-text-info">請先選擇數據表後可下載導入模板文件</div>
                    </el-form-item>
                    <el-form-item label="導入數據" prop="file">
                        <el-upload class="upload-xls" :show-file-list="false" accept=".xlsx,.xls" drag :auto-upload="false" @change="uploadXls">
                            <div v-if="baTable.form.extend!.fileUploadStatus == 'wait'" class="upload-file-box">
                                <Icon size="50px" color="#909399" name="el-icon-UploadFilled" />
                                <div class="el-upload__text">拖拽 .xls[x] 文件至此處 <em>或點擊我上傳</em></div>
                            </div>
                            <div v-if="baTable.form.extend!.fileUploadStatus == 'uploading'" class="upload-file-box">
                                <Icon size="50px" color="#ffffff" v-loading="true" name="el-icon-UploadFilled" />
                                <div class="el-upload__text">上傳中...</div>
                            </div>
                            <div v-if="baTable.form.extend!.fileUploadStatus == 'success'" class="upload-file-box">
                                <Icon size="50px" color="#ffffff" v-loading="true" name="el-icon-UploadFilled" />
                                <div class="el-upload__text">文件上傳成功，正在處理...</div>
                            </div>
                        </el-upload>
                    </el-form-item>
                    <el-form-item v-if="baTable.form.items!.data_table">
                        <el-alert title="提示" class="import-tips" type="success">
                            <p>1、導入數據內無`主鍵`字段或`主鍵留空`則可以使用主鍵自動遞增</p>
                            <p>2、若數據表有設計`create_time`、`update_time`字段且導入數據內未設定這兩個字段的值，則自動填充</p>
                            <p>
                                3、所有已設定值的導入數據，將原樣導入，比如：`create_time`字段，數據表設計為時間戳則請導入時間戳，`status:0=隱藏,1=開啟`，請導入`0`或`1`
                            </p>
                        </el-alert>
                    </el-form-item>
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import { reactive, ref, inject, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import type { ElForm, FormItemRule, UploadFile } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'
import { downloadImportTemplate, handleXls } from '/@/api/backend/routine/dataimport'
import { fileUpload } from '/@/api/common'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'

const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    data_table: [buildValidatorData({ name: 'required', message: '請選擇數據表' })],
    file: [buildValidatorData({ name: 'required', message: '請選擇導入數據文件' })],
})

const uploadXls = (file: UploadFile) => {
    if (!file || !file.raw) return

    NProgress.configure({ showSpinner: false })
    NProgress.start()

    baTable.form.extend!.fileUploadStatus = 'uploading'

    let fd = new FormData()
    fd.append('file', file.raw!)
    fileUpload(fd, {}, true, {
        onUploadProgress: (evt) => {
            NProgress.set(evt.progress!)
        },
    })
        .then((res) => {
            if (res.code == 1) {
                handleXls(baTable.form.items!.data_table, res.data.file.url)
                    .then((handleRes) => {
                        baTable.table.extend!.showPreImport = true
                        baTable.table.extend!.fields = handleRes.data.fields
                        baTable.table.extend!.rowCount = handleRes.data.rowCount
                        baTable.table.extend!.data = handleRes.data.data
                        baTable.table.extend!.file_url = res.data.file.url
                        baTable.form.extend!.fileUploadStatus = 'success'
                    })
                    .catch(() => {
                        baTable.form.extend!.fileUploadStatus = 'wait'
                    })
            }
        })
        .catch(() => {
            baTable.form.extend!.fileUploadStatus = 'wait'
        })
        .finally(() => {
            NProgress.done()
        })
}

const onDownloadImportTemplate = () => {
    window.location.href = downloadImportTemplate(baTable.form.items!.data_table)
}

watch(
    () => baTable.table.extend!.showPreImport,
    (newVal) => {
        if (newVal === false) {
            baTable.form.extend!.fileUploadStatus = 'wait'
        }
    }
)
</script>

<style scoped lang="scss">
.template-text-success {
    color: var(--el-color-success);
    cursor: pointer;
    user-select: none;
}
.template-text-info {
    color: var(--el-color-info);
}
.upload-xls {
    width: 100%;
}
.import-tips {
    line-height: 16px;
}
</style>
