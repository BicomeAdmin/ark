<template>
    <!-- 对话框表单 -->
    <!-- 建议使用 Prettier 格式化代码 -->
    <!-- el-form 内可以混用 el-form-item、FormItem、ba-input 等输入组件 -->
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
                :style="config.layout.shrink ? '':'width: calc(100% - ' + baTable.form.labelWidth! / 2 + 'px)'"
            >
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @submit.prevent=""
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    :label-position="config.layout.shrink ? 'top' : 'right'"
                    :label-width="baTable.form.labelWidth + 'px'"
                    :rules="rules"
                >
                    <FormItem :label="t('proiect.info.project_id')" type="remoteSelect" v-model="baTable.form.items!.project_id" prop="project_id" :input-attr="{ pk: 'project.id', field: 'string', remoteUrl: '/admin/Project/index' }" :placeholder="t('Please select field', { field: t('proiect.info.project_id') })" />
                    <FormItem :label="t('proiect.info.image')" type="image" v-model="baTable.form.items!.image" prop="image" />
                    <FormItem :label="t('proiect.info.open_chat')" type="string" v-model="baTable.form.items!.open_chat" prop="open_chat" :placeholder="t('Please input field', { field: t('proiect.info.open_chat') })" />
                    <FormItem :label="t('proiect.info.website')" type="string" v-model="baTable.form.items!.website" prop="website" :placeholder="t('Please input field', { field: t('proiect.info.website') })" />
                    <FormItem :label="t('proiect.info.editor')" type="editor" v-model="baTable.form.items!.editor" prop="editor" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :placeholder="t('Please input field', { field: t('proiect.info.editor') })" />
                    <FormItem :label="t('proiect.info.status')" type="switch" v-model="baTable.form.items!.status" prop="status" :input-attr="{ content: { '0': t('proiect.info.status 0'), '1': t('proiect.info.status 1') } }" />
                    <FormItem :label="t('proiect.info.remark')" type="textarea" v-model="baTable.form.items!.remark" prop="remark" :input-attr="{ rows: 3 }" @keyup.enter.stop="" @keyup.ctrl.enter="baTable.onSubmit(formRef)" :placeholder="t('Please input field', { field: t('proiect.info.remark') })" />
                </el-form>
            </div>
        </el-scrollbar>
        <template #footer>
            <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                <el-button @click="baTable.toggleForm()">{{ t('Cancel') }}</el-button>
                <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                    {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script setup lang="ts">
import type { FormInstance, FormItemRule } from 'element-plus'
import { inject, reactive, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import FormItem from '/@/components/formItem/index.vue'
import { useConfig } from '/@/stores/config'
import type baTableClass from '/@/utils/baTable'
import { buildValidatorData } from '/@/utils/validate'

const config = useConfig()
const formRef = ref<FormInstance>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    editor: [buildValidatorData({ name: 'editorRequired', title: t('proiect.info.editor') })],
    create_time: [buildValidatorData({ name: 'date', title: t('proiect.info.create_time') })],
    update_time: [buildValidatorData({ name: 'date', title: t('proiect.info.update_time') })],
})
</script>

<style scoped lang="scss"></style>
