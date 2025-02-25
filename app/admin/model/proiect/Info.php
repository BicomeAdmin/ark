<?php

namespace app\admin\model\proiect;

use think\Model;

/**
 * Info
 */
class Info extends Model
{
    // 表名
    protected $name = 'proiect_info';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function getEditorAttr($value): string
    {
        return !$value ? '' : htmlspecialchars_decode($value);
    }

    public function project(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\Project::class, 'project_id', 'id');
    }
}