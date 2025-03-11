<?php

namespace app\admin\model\project\user;

use think\Model;

/**
 * Relations
 */
class Relations extends Model
{
    // 表名
    protected $name = 'project_user_relations';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function project(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\Project::class, 'project_id', 'id');
    }

    public function projectUser(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\project\User::class, 'project_user_id', 'id');
    }

    public function projectStoreUser(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\project\store\User::class, 'project_store_user_id', 'id');
    }

    public function projectCommunityUser(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(\app\admin\model\project\community\User::class, 'project_community_user_id', 'id');
    }
}