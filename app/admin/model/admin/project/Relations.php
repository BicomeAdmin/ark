<?php

namespace app\admin\model\admin\project;

use think\Model;

/**
 * Relations
 */
class Relations extends Model
{
    // 表名
    protected $name = 'admin_project_relations';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 追加属性
    protected $append = [
        'project',
    ];


    public function getProjectAttr($value, $row): array
    {
        return [
            'string' => \app\admin\model\Project::whereIn('id', $row['project_ids'])->column('string'),
        ];
    }

    public function getProjectIdsAttr($value): array
    {
        if ($value === '' || $value === null) return [];
        if (!is_array($value)) {
            return explode(',', $value);
        }
        return $value;
    }

    public function setProjectIdsAttr($value): string
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    public function admin(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\Admin::class, 'admin_id', 'id');
    }
}