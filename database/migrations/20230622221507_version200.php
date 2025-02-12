<?php

use think\facade\Db;
use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Version200 extends Migrator
{
    public function up(): void
    {
        $admin = $this->table('admin');
        if ($admin->hasColumn('loginfailure')) {
            // 字段改名
            $admin->renameColumn('loginfailure', 'login_failure')
                ->renameColumn('lastlogintime', 'last_login_time')
                ->renameColumn('lastloginip', 'last_login_ip')
                ->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->changeColumn('create_time', 'biginteger', ['after' => 'update_time', 'limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $adminGroup = $this->table('admin_group');
        if ($adminGroup->hasColumn('updatetime')) {
            $adminGroup->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $adminLog = $this->table('admin_log');
        if ($adminLog->hasColumn('createtime')) {
            $adminLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->changeColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '請求數據'])
                ->save();
        }

        $attachment = $this->table('attachment');
        if ($attachment->hasColumn('createtime')) {
            $attachment->renameColumn('createtime', 'create_time')
                ->renameColumn('lastuploadtime', 'last_upload_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->changeColumn('last_upload_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '最後上傳時間'])
                ->save();
        }

        $captcha = $this->table('captcha');
        if ($captcha->hasColumn('createtime')) {
            $captcha->renameColumn('createtime', 'create_time')
                ->renameColumn('expiretime', 'expire_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->changeColumn('expire_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '過期時間'])
                ->changeColumn('captcha', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true, 'default' => null, 'comment' => '驗證碼數據'])
                ->save();
        }

        if ($this->hasTable('menu_rule')) {
            $menuRule = $this->table('menu_rule');
            if ($menuRule->hasColumn('updatetime') && $this->hasTable('menu_rule')) {
                $menuRule->renameColumn('updatetime', 'update_time')
                    ->renameColumn('createtime', 'create_time')
                    ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                    ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                    ->save();
                $menuRule->rename('admin_rule')->save();
                Db::name('admin_rule')
                    ->where('name', 'auth/menu')
                    ->update([
                        'name'      => 'auth/rule',
                        'path'      => 'auth/rule',
                        'component' => '/src/views/backend/auth/rule/index.vue',
                    ]);
                Db::name('admin_rule')->where('name', 'auth/menu/index')->update(['name' => 'auth/rule/index']);
                Db::name('admin_rule')->where('name', 'auth/menu/add')->update(['name' => 'auth/rule/add']);
                Db::name('admin_rule')->where('name', 'auth/menu/edit')->update(['name' => 'auth/rule/edit']);
                Db::name('admin_rule')->where('name', 'auth/menu/del')->update(['name' => 'auth/rule/del']);
                Db::name('admin_rule')->where('name', 'auth/menu/sortable')->update(['name' => 'auth/rule/sortable']);
                Db::name('admin_rule')->whereIn('name', [
                    'dashboard/dashboard',
                    'routine/attachment',
                ])->update(['remark' => 'Remark lang']);
            }
        }

        $securityDataRecycle = $this->table('security_data_recycle');
        if ($securityDataRecycle->hasColumn('updatetime')) {
            $securityDataRecycle->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $securityDataRecycleLog = $this->table('security_data_recycle_log');
        if ($securityDataRecycleLog->hasColumn('createtime')) {
            $securityDataRecycleLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $securitySensitiveData = $this->table('security_sensitive_data');
        if ($securitySensitiveData->hasColumn('updatetime')) {
            $securitySensitiveData->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $securitySensitiveDataLog = $this->table('security_sensitive_data_log');
        if ($securitySensitiveDataLog->hasColumn('createtime')) {
            $securitySensitiveDataLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $token = $this->table('token');
        if ($token->hasColumn('createtime')) {
            $token->renameColumn('createtime', 'create_time')
                ->renameColumn('expiretime', 'expire_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->changeColumn('expire_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '過期時間'])
                ->save();
        }

        $userGroup = $this->table('user_group');
        if ($userGroup->hasColumn('createtime')) {
            $userGroup->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $userMoneyLog = $this->table('user_money_log');
        if ($userMoneyLog->hasColumn('createtime')) {
            $userMoneyLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $userRule = $this->table('user_rule');
        if ($userRule->hasColumn('createtime')) {
            $userRule->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->changeColumn('type', 'enum', ['values' => 'route,menu_dir,menu,nav_user_menu,nav,button', 'default' => 'menu', 'comment' => '類型:route=路由,menu_dir=菜單目錄,menu=菜單項,nav_user_menu=頂欄會員菜單下拉項,nav=頂欄菜單項,button=頁面按鈕', 'null' => false]);
            if (!$userRule->hasColumn('no_login_valid')) {
                $userRule->addColumn('no_login_valid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '未登錄有效:0=否,1=是']);
            }
            $userRule->save();
        }

        $userScoreLog = $this->table('user_score_log');
        if ($userScoreLog->hasColumn('createtime')) {
            $userScoreLog->renameColumn('createtime', 'create_time')
                ->changeColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }

        $user = $this->table('user');
        if ($user->hasColumn('loginfailure')) {
            $user->renameColumn('lastlogintime', 'last_login_time')
                ->renameColumn('lastloginip', 'last_login_ip')
                ->renameColumn('loginfailure', 'login_failure')
                ->renameColumn('joinip', 'join_ip')
                ->renameColumn('jointime', 'join_time')
                ->renameColumn('updatetime', 'update_time')
                ->renameColumn('createtime', 'create_time')
                ->changeColumn('update_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->changeColumn('create_time', 'biginteger', ['after' => 'update_time', 'limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->save();
        }
    }
}
