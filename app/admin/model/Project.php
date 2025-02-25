<?php

namespace app\admin\model;

use think\Model;

/**
 * Project
 */
class Project extends Model
{
    // 表名
    protected $name = 'project';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 字段类型转换
    protected $type = [
        'expired_time' => 'timestamp:Y-m-d H:i:s',
    ];

}