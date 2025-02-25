<template>
    <div>
        <!-- 對話框表單 -->
        <el-dialog
            class="ba-operate-dialog"
            :close-on-click-modal="false"
            :model-value="baTable.form.operate ? true : false"
            @close="baTable.toggleForm"
            :destroy-on-close="true"
        >
            <template #header>
                <div class="title" v-drag="['.ba-operate-dialog', '.el-dialog__header']" v-zoom="'.ba-operate-dialog'">
                    {{ baTable.form.operate ? t(baTable.form.operate) : '' }}
                </div>
            </template>
            <el-scrollbar v-loading="baTable.form.loading" class="ba-table-form-scrollbar">
                <el-form
                    v-if="!baTable.form.loading"
                    ref="formRef"
                    @keyup.enter="baTable.onSubmit(formRef)"
                    :model="baTable.form.items"
                    label-position="top"
                    :rules="rules"
                >
                    <el-tabs class="config-tabs" @tab-change="onTabChange" v-model="state.tab">
                        <el-tab-pane label="基礎配置" name="base">
                            <FormItem
                                :label="t('routine.dataexport.name')"
                                type="string"
                                v-model="baTable.form.items!.name"
                                prop="name"
                                :input-attr="{ placeholder: t('Please input field', { field: t('routine.dataexport.name') }) }"
                            />
                            <FormItem
                                :label="t('routine.dataexport.main_table')"
                                type="select"
                                v-model="baTable.form.items!.main_table"
                                prop="main_table"
                                :data="{ content: baTable.form.extend!.tables }"
                                :input-attr="{
                                    placeholder: t('Please select field', { field: t('routine.dataexport.main_table') }),
                                    onChange: onTableChange,
                                }"
                                v-loading="baTable.form.extend!.fieldLoading"
                            />
                            <FormItem
                                v-if="baTable.form.extend!.fieldSelectOpt"
                                label="導出字段"
                                type="selects"
                                v-model="baTable.form.items!.fields"
                                :key="baTable.form.extend!.fieldSelectKey"
                                prop="fields"
                                placeholder="請先選擇導出數據源表，隨後在此選擇導出字段"
                                :data="{ content: baTable.form.extend!.fieldSelectOpt }"
                                v-loading="baTable.form.extend!.fieldLoading"
                            />
                            <el-row v-for="item in baTable.form.items!.fields" :key="item" class="field-row">
                                <el-col :span="4" class="field-title">
                                    <el-tooltip placement="top" :content="baTable.form.extend!.fieldSelect[item].name">
                                        <div>{{ baTable.form.extend!.fieldSelect[item].name }}:</div>
                                    </el-tooltip>
                                </el-col>
                                <el-col :span="4">
                                    <el-input
                                        type="text"
                                        placeholder="字段標題"
                                        v-model="baTable.form.extend!.fieldSelect[item].title"
                                        class="field-title-input"
                                    />
                                </el-col>
                                <el-col :span="3" class="field-title-input-title">數據識別:</el-col>
                                <el-col :span="4">
                                    <el-select v-model="baTable.form.extend!.fieldSelect[item].discern">
                                        <el-option label="文本" value="text" />
                                        <el-option label="數字" value="int" />
                                        <el-option label="時間日期" value="time" />
                                        <el-option label="值替換" value="valuation" />
                                        <el-option label="省份城市" value="city" />
                                    </el-select>
                                </el-col>
                                <el-col :span="3" class="field-title-input-title">
                                    <div v-if="baTable.form.extend!.fieldSelect[item].discern == 'valuation'">替換方案:</div>
                                </el-col>
                                <el-col :span="6">
                                    <div v-if="baTable.form.extend!.fieldSelect[item].discern == 'valuation'">
                                        <el-input
                                            type="text"
                                            placeholder="值替換方案"
                                            v-model="baTable.form.extend!.fieldSelect[item].comment"
                                            class="field-title-input"
                                        />
                                    </div>
                                </el-col>
                            </el-row>
                        </el-tab-pane>
                        <el-tab-pane label="關聯表配置" name="join">
                            <FormItem label="關聯表數量" type="number" v-model.number="baTable.form.items!.joinTableNumber" />
                            <div v-for="item in baTable.form.items!.joinTableNumber" :key="item" class="join-table-item">
                                <div class="form-hr"></div>
                                <el-form-item :label="'關聯表' + item">
                                    <el-select
                                        class="w100"
                                        :placeholder="t('Please select field', { field: '關聯表' + item })"
                                        v-model="baTable.form.items!.joinTable[item - 1]"
                                        @change="onJoinTableChange($event, item - 1)"
                                        v-loading="baTable.form.extend!.fieldLoading"
                                    >
                                        <el-option v-for="(table, tidx) in baTable.form.extend!.tables" :label="table" :value="tidx" :key="tidx" />
                                    </el-select>
                                </el-form-item>
                                <FormItem
                                    type="string"
                                    placeholder="非必填，設置別名後則源表可與關聯表相同"
                                    label="關聯表別名"
                                    v-model="baTable.form.items!.joinTableAsName[item - 1]"
                                />
                                <FormItem
                                    v-if="baTable.form.extend!.fieldSelectOpt"
                                    label="關聯外鍵"
                                    type="select"
                                    v-model="baTable.form.items!.joinTableFk[item - 1]"
                                    :key="baTable.form.extend!.fieldSelectKey + 'fk'"
                                    :placeholder="'請先選擇源表，隨後在此關聯外鍵'"
                                    :data="{ content: baTable.form.extend!.fieldSelectOpt }"
                                    v-loading="baTable.form.extend!.fieldLoading"
                                />
                                <FormItem
                                    v-if="baTable.form.extend!.joinTableFieldSelectOpt && baTable.form.extend!.joinTableFieldSelectOpt[item - 1]"
                                    label="關聯主鍵"
                                    type="select"
                                    v-model="baTable.form.items!.joinTablePk[item - 1]"
                                    :key="baTable.form.extend!.joinTableFieldSelectKey[item - 1] + 'pk'"
                                    :placeholder="'請先選擇關聯表' + item + '，隨後在此選擇關聯主鍵'"
                                    :data="{ content: baTable.form.extend!.joinTableFieldSelectOpt[item - 1] }"
                                    v-loading="baTable.form.extend!.fieldLoading"
                                />
                                <FormItem
                                    label="關聯類型"
                                    type="select"
                                    v-model="baTable.form.items!.joinTableType[item - 1]"
                                    placeholder="請選擇關聯類型"
                                    :data="{
                                        content: {
                                            INNER: 'INNER - 至少一個匹配',
                                            LEFT: 'LEFT - 從左表返回所有行',
                                            RIGHT: 'RIGHT - 從右表返回所有行',
                                            FULL: 'FULL - 返回所有行',
                                        },
                                    }"
                                />
                                <FormItem
                                    v-if="baTable.form.extend!.joinTableFieldSelectOpt && baTable.form.extend!.joinTableFieldSelectOpt[item - 1]"
                                    label="導出字段"
                                    type="selects"
                                    v-model="baTable.form.items!.joinTableFields[item - 1]"
                                    :key="baTable.form.extend!.joinTableFieldSelectKey[item - 1]"
                                    :placeholder="'請先選擇關聯表' + item + '，隨後在此選擇該表的導出字段'"
                                    :data="{ content: baTable.form.extend!.joinTableFieldSelectOpt[item - 1] }"
                                    v-loading="baTable.form.extend!.fieldLoading"
                                />
                                <template v-if="baTable.form.items!.joinTableFields && baTable.form.items!.joinTableFields[item - 1]">
                                    <el-row v-for="field in baTable.form.items!.joinTableFields[item - 1]" :key="field" class="field-row">
                                        <el-col :span="4" class="field-title">
                                            <el-tooltip placement="top" :content="baTable.form.extend!.joinTableFieldSelect[item - 1][field].name">
                                                <div>{{ baTable.form.extend!.joinTableFieldSelect[item - 1][field].name }}:</div>
                                            </el-tooltip>
                                        </el-col>
                                        <el-col :span="4">
                                            <el-input
                                                type="text"
                                                placeholder="字段標題"
                                                v-model="baTable.form.extend!.joinTableFieldSelect[item - 1][field].title"
                                                class="field-title-input"
                                            />
                                        </el-col>
                                        <el-col :span="3" class="field-title-input-title">數據識別:</el-col>
                                        <el-col :span="4">
                                            <el-select v-model="baTable.form.extend!.joinTableFieldSelect[item - 1][field].discern">
                                                <el-option label="文本" value="text" />
                                                <el-option label="數字" value="int" />
                                                <el-option label="時間日期" value="time" />
                                                <el-option label="值替換" value="valuation" />
                                                <el-option label="省份城市" value="city" />
                                            </el-select>
                                        </el-col>
                                        <el-col :span="3" class="field-title-input-title">
                                            <div v-if="baTable.form.extend!.joinTableFieldSelect[item - 1][field].discern == 'valuation'">
                                                替換方案:
                                            </div>
                                        </el-col>
                                        <el-col :span="6">
                                            <div v-if="baTable.form.extend!.joinTableFieldSelect[item - 1][field].discern == 'valuation'">
                                                <el-input
                                                    type="text"
                                                    placeholder="值替換方案"
                                                    v-model="baTable.form.extend!.joinTableFieldSelect[item - 1][field].comment"
                                                    class="field-title-input"
                                                />
                                            </div>
                                        </el-col>
                                    </el-row>
                                </template>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="數據篩選配置" name="where">
                            <FormItem
                                v-if="baTable.form.extend!.allTableField"
                                label="添加篩選字段"
                                type="select"
                                v-model="baTable.form.items!.where_field"
                                placeholder="請先選擇源表和關聯表，隨後在此選擇數據篩選字段"
                                :data="{ content: baTable.form.extend!.allTableField }"
                                v-loading="baTable.form.extend!.fieldLoading"
                                :key="baTable.form.extend!.allTableFieldKey"
                                :input-attr="{ onChange: onWhereChange }"
                            />
                            <el-row v-for="(item, wfIdx) in baTable.form.items!.where" :key="item" :gutter="10" class="field-row">
                                <el-col :span="5" class="field-title">
                                    <el-tooltip placement="top" :content="item.field">
                                        <div>{{ item.field }}:</div>
                                    </el-tooltip>
                                </el-col>
                                <el-col :span="6">
                                    <el-select v-model="item.operator">
                                        <el-option label="等於" value="eq" />
                                        <el-option label="不等於" value="ne" />
                                        <el-option label="大於" value="gt" />
                                        <el-option label="大於等於" value="egt" />
                                        <el-option label="小於" value="lt" />
                                        <el-option label="小於等於" value="elt" />
                                        <el-option label="LIKE" value="LIKE" />
                                        <el-option label="NOT LIKE" value="NOT LIKE" />
                                        <el-option label="IN" value="IN" />
                                        <el-option label="NOT IN" value="NOT IN" />
                                    </el-select>
                                </el-col>
                                <el-col :span="10">
                                    <div>
                                        <el-input type="text" placeholder="篩選值" v-model="item.value" class="field-title-input where-field-input" />
                                    </div>
                                </el-col>
                                <el-col :span="3">
                                    <span class="where-field-btn" @click="onWhereFieldBtn('add', wfIdx, item)">[ + ]</span>
                                    <span class="where-field-btn del" @click="onWhereFieldBtn('del', wfIdx, item)">[ - ]</span>
                                </el-col>
                            </el-row>
                            <div class="form-hr"></div>
                            <FormItem
                                v-if="baTable.form.extend!.allTableField"
                                label="排序字段"
                                type="selects"
                                v-model="baTable.form.items!.order_field"
                                :placeholder="'請先選擇源表和關聯表，隨後在此選擇數據排序字段'"
                                :data="{ content: baTable.form.extend!.allTableField }"
                                v-loading="baTable.form.extend!.fieldLoading"
                                :key="baTable.form.extend!.allTableFieldKey + 'order'"
                                :input-attr="{ onChange: onOrderChange }"
                            />
                            <el-row v-for="item in baTable.form.items!.order" :key="item" :gutter="10" class="field-row">
                                <el-col :span="6" class="field-title">
                                    <el-tooltip placement="top" :content="item.field">
                                        <div>{{ item.field }}:</div>
                                    </el-tooltip>
                                </el-col>
                                <el-col :span="6">
                                    <el-select v-model="item.value">
                                        <el-option label="倒序(從大到小)" value="DESC" />
                                        <el-option label="正序(從小到大)" value="ASC" />
                                    </el-select>
                                </el-col>
                            </el-row>
                        </el-tab-pane>
                        <el-tab-pane label="其他配置" name="other">
                            <FormItem
                                :label="t('routine.dataexport.xls_max_number')"
                                type="number"
                                prop="xls_max_number"
                                v-model.number="baTable.form.items!.xls_max_number"
                                :input-attr="{ step: 1, placeholder: t('Please input field', { field: t('routine.dataexport.xls_max_number') }) }"
                            />
                            <FormItem
                                :label="t('routine.dataexport.concurrent_create_xls')"
                                type="number"
                                prop="concurrent_create_xls"
                                v-model.number="baTable.form.items!.concurrent_create_xls"
                                :input-attr="{
                                    step: 1,
                                    placeholder: t('Please input field', { field: t('routine.dataexport.concurrent_create_xls') }),
                                }"
                            />
                            <FormItem
                                :label="t('routine.dataexport.memory_limit')"
                                type="number"
                                prop="memory_limit"
                                v-model.number="baTable.form.items!.memory_limit"
                                :input-attr="{ step: 1, placeholder: t('Please input field', { field: t('routine.dataexport.memory_limit') }) }"
                            />
                            <FormItem
                                :label="t('routine.dataexport.export_number')"
                                type="number"
                                prop="export_number"
                                v-model.number="baTable.form.items!.export_number"
                                :input-attr="{
                                    step: 1,
                                    placeholder:
                                        t('Please input field', { field: t('routine.dataexport.export_number') }) + '，留空或輸入 0 則導出全部',
                                }"
                            />
                        </el-tab-pane>
                    </el-tabs>
                </el-form>
            </el-scrollbar>
            <template #footer>
                <div :style="'width: calc(100% - ' + baTable.form.labelWidth! / 1.8 + 'px)'">
                    <el-button @click="baTable.toggleForm('')">{{ t('Cancel') }}</el-button>
                    <el-button v-blur :loading="baTable.form.submitLoading" @click="baTable.onSubmit(formRef)" type="primary">
                        {{ baTable.form.operateIds && baTable.form.operateIds.length > 1 ? t('Save and edit next item') : t('Save') }}
                    </el-button>
                </div>
            </template>
        </el-dialog>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, inject, nextTick } from 'vue'
import { useI18n } from 'vue-i18n'
import type baTableClass from '/@/utils/baTable'
import FormItem from '/@/components/formItem/index.vue'
import type { ElForm, FormItemRule, TabPaneName } from 'element-plus'
import { buildValidatorData } from '/@/utils/validate'
import { getFieldList } from '/@/api/backend/routine/dataexport'
import { uuid } from '/@/utils/random'

const formRef = ref<InstanceType<typeof ElForm>>()
const baTable = inject('baTable') as baTableClass

const { t } = useI18n()

const state = reactive({
    tab: 'base',
})

const onWhereFieldBtn = (opt: string, idx: number, whereField: anyObj) => {
    if (opt == 'del') {
        baTable.form.items!.where.splice(idx, 1)
    } else if (opt == 'add') {
        baTable.form.items!.where.push({
            ...whereField,
            value: '',
            operator: 'eq',
        })
    }
}

const onTableChange = (val: string) => {
    baTable.form.extend!.fieldLoading = true
    getFieldList(val)
        .then((res) => {
            baTable.form.extend!.fieldSelect = res.data.fields
            const fields: anyObj = []
            const fieldSelectOpt: anyObj = []
            for (const key in res.data.fields) {
                fields.push(key)
                fieldSelectOpt[key] = res.data.fields[key].name + (res.data.fields[key].title ? ' - ' + res.data.fields[key].title : '')
            }
            baTable.form.extend!.fieldSelectOpt = fieldSelectOpt
            baTable.form.extend!.fieldSelectKey = uuid()
            baTable.form.items!.fields = fields
            if (typeof baTable.form.extend!.onTableChangeCallback == 'function') {
                baTable.form.extend!.onTableChangeCallback()
                baTable.form.extend!.onTableChangeCallback = null
            }
        })
        .finally(() => {
            baTable.form.extend!.fieldLoading = false
        })
}

const onJoinTableChange = (val: string, joinTableIdx: number) => {
    baTable.form.extend!.fieldLoading = true
    getFieldList(val)
        .then((res) => {
            if (typeof baTable.form.extend!.joinTableFieldSelect == 'undefined') {
                baTable.form.extend!.joinTableFieldSelect = []
                baTable.form.extend!.joinTableFieldSelectOpt = []
                baTable.form.extend!.joinTableFieldSelectKey = []
            }
            baTable.form.extend!.joinTableFieldSelect[joinTableIdx] = res.data.fields
            const fieldSelectOpt: anyObj = []
            for (const key in res.data.fields) {
                fieldSelectOpt[key] = res.data.fields[key].name + (res.data.fields[key].title ? ' - ' + res.data.fields[key].title : '')
            }
            baTable.form.extend!.joinTableFieldSelectOpt[joinTableIdx] = fieldSelectOpt
            baTable.form.extend!.joinTableFieldSelectKey[joinTableIdx] = uuid()

            if (
                baTable.form.items!.onJoinTableChangeCallback &&
                baTable.form.items!.onJoinTableChangeCallback[joinTableIdx] &&
                typeof baTable.form.items!.onJoinTableChangeCallback[joinTableIdx] == 'function'
            ) {
                baTable.form.items!.onJoinTableChangeCallback[joinTableIdx]()
                baTable.form.items!.onJoinTableChangeCallback[joinTableIdx] = null
            }
        })
        .finally(() => {
            baTable.form.extend!.fieldLoading = false
        })
}

const onTabChange = (name: TabPaneName) => {
    if (name == 'where') {
        baTable.form.extend!.fieldLoading = true
        const allTableField: anyObj = []
        for (const key in baTable.form.extend!.fieldSelectOpt) {
            allTableField[baTable.form.items!.main_table + '.' + key] =
                baTable.form.items!.main_table + '.' + baTable.form.extend!.fieldSelectOpt[key]
        }
        for (const tableKey in baTable.form.items!.joinTable) {
            const tableName = baTable.form.items!.joinTableAsName[tableKey]
                ? baTable.form.items!.joinTableAsName[tableKey]
                : baTable.form.items!.joinTable[tableKey]
            for (const key in baTable.form.extend!.joinTableFieldSelectOpt[tableKey]) {
                allTableField[tableName + '.' + key] = tableName + '.' + baTable.form.extend!.joinTableFieldSelectOpt[tableKey][key]
            }
        }
        baTable.form.extend!.allTableField = allTableField
        baTable.form.extend!.fieldLoading = false
        baTable.form.extend!.allTableFieldKey = uuid()
    }
}

const onWhereChange = () => {
    baTable.form.items!.where.push({
        value: '',
        operator: 'eq',
        field: baTable.form.items!.where_field,
    })

    // 添加後置空
    baTable.form.items!.where_field = ''

    // 聚焦到最後一個字段的篩選條件輸入框
    nextTick(() => {
        const inputs = document.querySelectorAll('.where-field-input')
        if (inputs) {
            const inputInner = inputs[inputs.length - 1].querySelector('.el-input__inner') as HTMLInputElement | null
            if (inputInner) {
                inputInner.focus()
            }
        }
    })
}

const onOrderChange = () => {
    const order: anyObj = []
    for (const key in baTable.form.items!.order_field) {
        let orderValue = 'DESC'
        for (const okey in baTable.form.items!.order) {
            if (baTable.form.items!.order[okey].field == baTable.form.items!.order_field[key]) {
                orderValue = baTable.form.items!.order[okey].value
                break
            }
        }
        order[key] = {
            value: orderValue,
            field: baTable.form.items!.order_field[key],
        }
    }
    baTable.form.items!.order = order
}

const rules: Partial<Record<string, FormItemRule[]>> = reactive({
    name: [buildValidatorData({ name: 'required', title: t('routine.dataexport.name') })],
    main_table: [buildValidatorData({ name: 'required', message: t('Please select field', { field: t('routine.dataexport.main_table') }) })],
    concurrent_create_xls: [buildValidatorData({ name: 'required', title: t('routine.dataexport.concurrent_create_xls') })],
    memory_limit: [
        buildValidatorData({ name: 'required', title: t('routine.dataexport.memory_limit') }),
        {
            validator: (rule: any, val: string, callback: Function) => {
                let fieldCount = (baTable.form.items!.fields && baTable.form.items!.fields.length) ?? 0
                for (const key in baTable.form.items!.joinTableFields) {
                    fieldCount += baTable.form.items!.joinTableFields[key].length
                }
                if (fieldCount <= 0) {
                    return callback(new Error('請先在基礎配置中選擇導出字段'))
                }
                const memory = (fieldCount * baTable.form.items!.xls_max_number) / 1024
                if (memory >= baTable.form.items!.memory_limit) {
                    return callback(new Error('預計需要更多內存 > ' + (memory + 50).toFixed(0) + 'MB'))
                }
                return callback()
            },
            trigger: 'blur',
        },
    ],
    fields: [buildValidatorData({ name: 'required', message: t('Please select field', { field: '導出字段' }) })],
})

defineExpose({
    onTableChange,
    onJoinTableChange,
})
</script>

<style scoped lang="scss">
.where-field-btn {
    cursor: pointer;
    color: var(--el-color-primary);
}
.where-field-btn.del {
    margin-left: 6px;
    color: var(--el-color-danger) !important;
}
:deep(.ba-operate-dialog) .el-dialog__header {
    border: none;
}
.config-tabs {
    padding-bottom: 40px;
}
.field-row {
    display: flex;
    align-items: center;
    padding: 4px 0;
    .field-title {
        display: flex;
        align-items: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .field-title-input-title {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}
.form-hr {
    border-bottom: 1px solid #dcdfe6;
    margin: 16px 0;
}
</style>
