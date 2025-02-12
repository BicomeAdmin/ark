<?php

use think\facade\Db;
use think\migration\Migrator;

class Version202 extends Migrator
{
    /**
     * 規範菜單規則
     * @throws Throwable
     */
    public function up(): void
    {
        Db::startTrans();
        try {
            $dashboardId = Db::name('admin_rule')
                ->where('name', 'dashboard/dashboard')
                ->lock(true)
                ->value('id');
            if ($dashboardId) {
                // 修改name
                Db::name('admin_rule')
                    ->where('name', 'dashboard/dashboard')
                    ->update([
                        'name' => 'dashboard',
                    ]);

                // 增加一個查看的權限節點
                $dashboardIndexId = Db::name('admin_rule')->insertGetId([
                    'pid'         => $dashboardId,
                    'type'        => 'button',
                    'title'       => '查看',
                    'name'        => 'dashboard/index',
                    'update_time' => time(),
                    'create_time' => time(),
                ]);

                // 原本有控制台權限的管理員，給予新增的查看權限
                $group = Db::name('admin_group')
                    ->where('rules', 'find in set', $dashboardId)
                    ->select();
                foreach ($group as $item) {

                    $newRules = trim($item['rules'], ',');
                    $newRules = $newRules . ',' . $dashboardIndexId;

                    Db::name('admin_group')
                        ->where('id', $item['id'])
                        ->update([
                            'rules' => $newRules
                        ]);
                }
            }

            // 修改name
            Db::name('admin_rule')
                ->where('name', 'buildadmin/buildadmin')
                ->update([
                    'name' => 'buildadmin',
                ]);

            Db::commit();
        } catch (Throwable $e) {
            Db::rollback();
            throw $e;
        }
    }
}
