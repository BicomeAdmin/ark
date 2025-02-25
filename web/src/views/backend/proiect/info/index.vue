<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格頂部菜單 -->
        <!-- 自定義按鈕請使用插槽，甚至公共搜索也可以使用具名插槽渲染，參見文檔 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('proiect.info.quick Search Fields') })"
        ></TableHeader>

        <!-- 表格 -->
        <!-- 表格列有多種自定義渲染方式，比如自定義組件、具名插槽等，參見文檔 -->
        <!-- 要使用 el-table 組件原有的屬性，直接加在 Table 標籤上即可 -->
        <Table ref="tableRef"></Table>

        <!-- 表單 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { onMounted, provide, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import { baTableApi } from '/@/api/common'
import { defaultOptButtons } from '/@/components/table'
import TableHeader from '/@/components/table/header/index.vue'
import Table from '/@/components/table/index.vue'
import baTableClass from '/@/utils/baTable'

defineOptions({
    name: 'proiect/info',
})

const { t } = useI18n()
const tableRef = ref()
const optButtons: OptButton[] = defaultOptButtons(['edit', 'delete'])

/**
 * baTable 內包含了表格的所有數據且數據具備響應性，然後通過 provide 注入給了後代組件
 */
const baTable = new baTableClass(
    new baTableApi('/admin/proiect.Info/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false },
            { label: t('proiect.info.id'), prop: 'id', align: 'center', width: 70, operator: 'RANGE', sortable: 'custom' },
            { label: t('proiect.info.project__id'), prop: 'project.id', align: 'center', operatorPlaceholder: t('Fuzzy query'), render: 'tags', operator: 'LIKE' },
            { label: t('proiect.info.project__string'), prop: 'project.string', align: 'center', operatorPlaceholder: t('Fuzzy query'), render: 'tags', operator: 'LIKE' },
            { label: t('proiect.info.image'), prop: 'image', align: 'center', render: 'image', operator: false },
            { label: t('proiect.info.open_chat'), prop: 'open_chat', align: 'center', render: 'url', operator: false, sortable: false },
            { label: t('proiect.info.website'), prop: 'website', align: 'center', render: 'url', operator: false, sortable: false },
            { label: t('proiect.info.status'), prop: 'status', align: 'center', render: 'switch', operator: 'eq', sortable: false, replaceValue: { '0': t('proiect.info.status 0'), '1': t('proiect.info.status 1') } },
            { label: t('proiect.info.create_time'), prop: 'create_time', align: 'center', render: 'datetime', operator: 'RANGE', sortable: 'custom', width: 160, timeFormat: 'yyyy-mm-dd hh:MM:ss' },
            { label: t('proiect.info.update_time'), prop: 'update_time', align: 'center', render: 'datetime', operator: 'RANGE', sortable: 'custom', width: 160, timeFormat: 'yyyy-mm-dd hh:MM:ss' },
            { label: t('Operate'), align: 'center', width: 100, render: 'buttons', buttons: optButtons, operator: false },
        ],
        dblClickNotEditColumn: [undefined, 'status'],
    },
    {
        defaultItems: { editor: '', status: '1' },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})
</script>

<style scoped lang="scss"></style>
