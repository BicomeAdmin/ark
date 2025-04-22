<?php

namespace app\admin\model\project\community;

use think\Model;

/**
 * User
 */
class User extends Model
{
    // 表名
    protected $name = 'project_community_user';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function project(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\Project::class, 'project_id', 'id');
    }
}