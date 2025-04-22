<?php
// app/common/model/ProjectBase.php
namespace app\common\model;

use think\Model;

class ProjectBase extends Model
{
    // 自動查詢條件
    protected function base($query)
    {
        $projectId = session('current_project_id');
        if ($projectId) {
            $query->where($this->name . '.project_id', $projectId);
        }
    }

    // 自動填充專案ID
    public static function onBeforeInsert($model)
    {
        $projectId = session('current_project_id');
        if ($projectId && !isset($model->project_id)) {
            $model->project_id = $projectId;
        }
    }
}