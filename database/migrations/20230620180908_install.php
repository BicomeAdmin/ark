<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class Install extends Migrator
{

    public function change(): void
    {
        $this->admin();
        $this->adminGroup();
        $this->adminGroupAccess();
        $this->adminLog();
        $this->area();
        $this->attachment();
        $this->captcha();
        $this->config();
        $this->menuRule();
        $this->securityDataRecycle();
        $this->securityDataRecycleLog();
        $this->securitySensitiveData();
        $this->securitySensitiveDataLog();
        $this->testBuild();
        $this->token();
        $this->user();
        $this->userGroup();
        $this->userMoneyLog();
        $this->userRule();
        $this->userScoreLog();
        $this->crudLog();
    }

    public function admin(): void
    {
        if (!$this->hasTable('admin')) {
            $table = $this->table('admin', [
                'id'          => false,
                'comment'     => '管理員表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('username', 'string', ['limit' => 20, 'default' => '', 'comment' => '用戶名', 'null' => false])
                ->addColumn('nickname', 'string', ['limit' => 50, 'default' => '', 'comment' => '暱稱', 'null' => false])
                ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '頭像', 'null' => false])
                ->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '郵箱', 'null' => false])
                ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手機', 'null' => false])
                ->addColumn('loginfailure', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '登錄失敗次數', 'null' => false])
                ->addColumn('lastlogintime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '上次登錄時間'])
                ->addColumn('lastloginip', 'string', ['limit' => 50, 'default' => '', 'comment' => '上次登錄IP', 'null' => false])
                ->addColumn('password', 'string', ['limit' => 32, 'default' => '', 'comment' => '密碼', 'null' => false])
                ->addColumn('salt', 'string', ['limit' => 30, 'default' => '', 'comment' => '密碼鹽', 'null' => false])
                ->addColumn('motto', 'string', ['limit' => 255, 'default' => '', 'comment' => '簽名', 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=禁用,1=啟用', 'null' => false])
                ->addColumn('createtime', 'integer', ['limit' => 10, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('updatetime', 'integer', ['limit' => 10, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->addIndex(['username'], [
                    'unique' => true,
                ])
                ->create();
        }
    }

    public function adminGroup(): void
    {
        if (!$this->hasTable('admin_group')) {
            $table = $this->table('admin_group', [
                'id'          => false,
                'comment'     => '管理分組表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '上級分組', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '組名', 'null' => false])
                ->addColumn('rules', 'text', ['null' => true, 'default' => null, 'comment' => '權限規則ID'])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=禁用,1=啟用', 'null' => false])
                ->addColumn('updatetime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function adminGroupAccess(): void
    {
        if (!$this->hasTable('admin_group_access')) {
            $table = $this->table('admin_group_access', [
                'id'         => false,
                'comment'    => '管理分組映射表',
                'row_format' => 'DYNAMIC',
                'collation'  => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('uid', 'integer', ['comment' => '管理員ID', 'signed' => false, 'null' => false])
                ->addColumn('group_id', 'integer', ['comment' => '分組ID', 'signed' => false, 'null' => false])
                ->addIndex(['uid'], [
                    'type' => 'BTREE',
                ])
                ->addIndex(['group_id'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }

    public function adminLog(): void
    {
        if (!$this->hasTable('admin_log')) {
            $table = $this->table('admin_log', [
                'id'          => false,
                'comment'     => '管理員日誌表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('admin_id', 'integer', ['comment' => '管理員ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('username', 'string', ['limit' => 20, 'default' => '', 'comment' => '管理員用戶名', 'null' => false])
                ->addColumn('url', 'string', ['limit' => 1500, 'default' => '', 'comment' => '操作Url', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 100, 'default' => '', 'comment' => '日誌標題', 'null' => false])
                ->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '請求數據'])
                ->addColumn('ip', 'string', ['limit' => 50, 'default' => '', 'comment' => 'IP', 'null' => false])
                ->addColumn('useragent', 'string', ['limit' => 255, 'default' => '', 'comment' => 'User-Agent', 'null' => false])
                ->addColumn('createtime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function area(): void
    {
        if (!$this->hasTable('area')) {
            $table = $this->table('area', [
                'id'          => false,
                'comment'     => '省份地區表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '父id', 'null' => true, 'default' => null, 'signed' => false])
                ->addColumn('shortname', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '簡稱'])
                ->addColumn('name', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '名稱'])
                ->addColumn('mergename', 'string', ['limit' => 255, 'null' => true, 'default' => null, 'comment' => '全稱'])
                ->addColumn('level', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'null' => true, 'default' => null, 'comment' => '層級:1=省,2=市,3=區/縣'])
                ->addColumn('pinyin', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '拼音'])
                ->addColumn('code', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '長途區號'])
                ->addColumn('zip', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '郵編'])
                ->addColumn('first', 'string', ['limit' => 50, 'null' => true, 'default' => null, 'comment' => '首字母'])
                ->addColumn('lng', 'string', ['limit' => 50, 'null' => true, 'default' => null, 'comment' => '經度'])
                ->addColumn('lat', 'string', ['limit' => 50, 'null' => true, 'default' => null, 'comment' => '緯度'])
                ->addIndex(['pid'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }

    public function attachment(): void
    {
        if (!$this->hasTable('attachment')) {
            $table = $this->table('attachment', [
                'id'          => false,
                'comment'     => '附件表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('topic', 'string', ['limit' => 20, 'default' => '', 'comment' => '細目', 'null' => false])
                ->addColumn('admin_id', 'integer', ['comment' => '上傳管理員ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '上傳用戶ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '物理路徑', 'null' => false])
                ->addColumn('width', 'integer', ['comment' => '寬度', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('height', 'integer', ['comment' => '高度', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '原始名稱', 'null' => false])
                ->addColumn('size', 'integer', ['comment' => '大小', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('mimetype', 'string', ['limit' => 100, 'default' => '', 'comment' => 'mime類型', 'null' => false])
                ->addColumn('quote', 'integer', ['comment' => '上傳(引用)次數', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('storage', 'string', ['limit' => 50, 'default' => '', 'comment' => '存儲方式', 'null' => false])
                ->addColumn('sha1', 'string', ['limit' => 40, 'default' => '', 'comment' => 'sha1編碼', 'null' => false])
                ->addColumn('createtime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->addColumn('lastuploadtime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '最後上傳時間'])
                ->create();
        }
    }

    public function captcha(): void
    {
        if (!$this->hasTable('captcha')) {
            $table = $this->table('captcha', [
                'id'          => false,
                'comment'     => '驗證碼表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'key',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('key', 'string', ['limit' => 32, 'default' => '', 'comment' => '驗證碼Key', 'null' => false])
                ->addColumn('code', 'string', ['limit' => 32, 'default' => '', 'comment' => '驗證碼(加密後)', 'null' => false])
                ->addColumn('captcha', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '驗證碼數據'])
                ->addColumn('createtime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->addColumn('expiretime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '過期時間'])
                ->create();
        }
    }

    public function config(): void
    {
        if (!$this->hasTable('config')) {
            $table = $this->table('config', [
                'id'          => false,
                'comment'     => '系統配置',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 30, 'default' => '', 'comment' => '變量名', 'null' => false])
                ->addColumn('group', 'string', ['limit' => 30, 'default' => '', 'comment' => '分組', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '變量標題', 'null' => false])
                ->addColumn('tip', 'string', ['limit' => 100, 'default' => '', 'comment' => '變量描述', 'null' => false])
                ->addColumn('type', 'string', ['limit' => 30, 'default' => '', 'comment' => '變量輸入組件類型', 'null' => false])
                ->addColumn('value', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '變量值'])
                ->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '字典數據'])
                ->addColumn('rule', 'string', ['limit' => 100, 'default' => '', 'comment' => '驗證規則', 'null' => false])
                ->addColumn('extend', 'string', ['limit' => 255, 'default' => '', 'comment' => '擴展屬性', 'null' => false])
                ->addColumn('allow_del', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '允許刪除:0=否,1=是', 'null' => false])
                ->addColumn('weigh', 'integer', ['comment' => '權重', 'default' => 0, 'null' => false])
                ->addIndex(['name'], [
                    'unique' => true,
                ])
                ->create();
        }
    }

    public function menuRule(): void
    {
        if (!$this->hasTable('menu_rule') && !$this->hasTable('admin_rule')) {
            $table = $this->table('menu_rule', [
                'id'          => false,
                'comment'     => '菜單和權限規則表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '上級菜單', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('type', 'enum', ['values' => 'menu_dir,menu,button', 'default' => 'menu', 'comment' => '類型:menu_dir=菜單目錄,menu=菜單項,button=頁面按鈕', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '標題', 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '規則名稱', 'null' => false])
                ->addColumn('path', 'string', ['limit' => 100, 'default' => '', 'comment' => '路由路徑', 'null' => false])
                ->addColumn('icon', 'string', ['limit' => 50, 'default' => '', 'comment' => '圖標', 'null' => false])
                ->addColumn('menu_type', 'enum', ['values' => 'tab,link,iframe', 'null' => true, 'default' => null, 'comment' => '菜單類型:tab=選項卡,link=鏈接,iframe=Iframe'])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => 'Url', 'null' => false])
                ->addColumn('component', 'string', ['limit' => 100, 'default' => '', 'comment' => '組件路徑', 'null' => false])
                ->addColumn('keepalive', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '緩存:0=關閉,1=開啟', 'null' => false])
                ->addColumn('extend', 'enum', ['values' => 'none,add_rules_only,add_menu_only', 'default' => 'none', 'comment' => '擴展屬性:none=無,add_rules_only=只添加為路由,add_menu_only=只添加為菜單', 'null' => false])
                ->addColumn('remark', 'string', ['limit' => 255, 'default' => '', 'comment' => '備註', 'null' => false])
                ->addColumn('weigh', 'integer', ['comment' => '權重', 'default' => 0, 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=禁用,1=啟用', 'null' => false])
                ->addColumn('updatetime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->addIndex(['pid'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }

    public function securityDataRecycle(): void
    {
        if (!$this->hasTable('security_data_recycle')) {
            $table = $this->table('security_data_recycle', [
                'id'          => false,
                'comment'     => '回收規則表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '規則名稱', 'null' => false])
                ->addColumn('controller', 'string', ['limit' => 100, 'default' => '', 'comment' => '控制器', 'null' => false])
                ->addColumn('controller_as', 'string', ['limit' => 100, 'default' => '', 'comment' => '控制器別名', 'null' => false])
                ->addColumn('data_table', 'string', ['limit' => 100, 'default' => '', 'comment' => '對應數據表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '數據表主鍵', 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=禁用,1=啟用', 'null' => false])
                ->addColumn('updatetime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function securityDataRecycleLog(): void
    {
        if (!$this->hasTable('security_data_recycle_log')) {
            $table = $this->table('security_data_recycle_log', [
                'id'          => false,
                'comment'     => '數據回收記錄表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('admin_id', 'integer', ['comment' => '操作管理員', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('recycle_id', 'integer', ['comment' => '回收規則ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('data', 'text', ['null' => true, 'default' => null, 'comment' => '回收的數據'])
                ->addColumn('data_table', 'string', ['limit' => 100, 'default' => '', 'comment' => '數據表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '數據表主鍵', 'null' => false])
                ->addColumn('is_restore', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '是否已還原:0=否,1=是', 'null' => false])
                ->addColumn('ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '操作者IP', 'null' => false])
                ->addColumn('useragent', 'string', ['limit' => 255, 'default' => '', 'comment' => 'User-Agent', 'null' => false])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function securitySensitiveData(): void
    {
        if (!$this->hasTable('security_sensitive_data')) {
            $table = $this->table('security_sensitive_data', [
                'id'          => false,
                'comment'     => '敏感數據規則表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '規則名稱', 'null' => false])
                ->addColumn('controller', 'string', ['limit' => 100, 'default' => '', 'comment' => '控制器', 'null' => false])
                ->addColumn('controller_as', 'string', ['limit' => 100, 'default' => '', 'comment' => '控制器別名', 'null' => false])
                ->addColumn('data_table', 'string', ['limit' => 100, 'default' => '', 'comment' => '對應數據表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '數據表主鍵', 'null' => false])
                ->addColumn('data_fields', 'text', ['null' => true, 'default' => null, 'comment' => '敏感數據字段'])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=禁用,1=啟用', 'null' => false])
                ->addColumn('updatetime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function securitySensitiveDataLog(): void
    {
        if (!$this->hasTable('security_sensitive_data_log')) {
            $table = $this->table('security_sensitive_data_log', [
                'id'          => false,
                'comment'     => '敏感數據修改記錄',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('admin_id', 'integer', ['comment' => '操作管理員', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('sensitive_id', 'integer', ['comment' => '敏感數據規則ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('data_table', 'string', ['limit' => 100, 'default' => '', 'comment' => '數據表', 'null' => false])
                ->addColumn('primary_key', 'string', ['limit' => 50, 'default' => '', 'comment' => '數據表主鍵', 'null' => false])
                ->addColumn('data_field', 'string', ['limit' => 50, 'default' => '', 'comment' => '被修改字段', 'null' => false])
                ->addColumn('data_comment', 'string', ['limit' => 50, 'default' => '', 'comment' => '被修改項', 'null' => false])
                ->addColumn('id_value', 'integer', ['comment' => '被修改項主鍵值', 'default' => 0, 'null' => false])
                ->addColumn('before', 'text', ['null' => true, 'default' => null, 'comment' => '修改前'])
                ->addColumn('after', 'text', ['null' => true, 'default' => null, 'comment' => '修改後'])
                ->addColumn('ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '操作者IP', 'null' => false])
                ->addColumn('useragent', 'string', ['limit' => 255, 'default' => '', 'comment' => 'User-Agent', 'null' => false])
                ->addColumn('is_rollback', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '是否已回滾:0=否,1=是', 'null' => false])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function testBuild(): void
    {
        if (!$this->hasTable('test_build')) {
            $table = $this->table('test_build', [
                'id'          => false,
                'comment'     => '知識庫表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('title', 'string', ['limit' => 100, 'default' => '', 'comment' => '標題', 'null' => false])
                ->addColumn('keyword_rows', 'string', ['limit' => 100, 'default' => '', 'comment' => '關鍵詞', 'null' => false])
                ->addColumn('content', 'text', ['null' => true, 'default' => null, 'comment' => '內容'])
                ->addColumn('views', 'integer', ['comment' => '瀏覽量', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('likes', 'integer', ['comment' => '有幫助數', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('dislikes', 'integer', ['comment' => '無幫助數', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('note_textarea', 'string', ['limit' => 100, 'default' => '', 'comment' => '備註', 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=隱藏,1=正常', 'null' => false])
                ->addColumn('weigh', 'integer', ['comment' => '權重', 'default' => 0, 'null' => false])
                ->addColumn('update_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function token(): void
    {
        if (!$this->hasTable('token')) {
            $table = $this->table('token', [
                'id'          => false,
                'comment'     => '用戶Token表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'token',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('token', 'string', ['limit' => 50, 'default' => '', 'comment' => 'Token', 'null' => false])
                ->addColumn('type', 'string', ['limit' => 15, 'default' => '', 'comment' => '類型', 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '用戶ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->addColumn('expiretime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '過期時間'])
                ->create();
        }
    }

    public function user(): void
    {
        if (!$this->hasTable('user')) {
            $table = $this->table('user', [
                'id'          => false,
                'comment'     => '會員表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('group_id', 'integer', ['comment' => '分組ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('username', 'string', ['limit' => 32, 'default' => '', 'comment' => '用戶名', 'null' => false])
                ->addColumn('nickname', 'string', ['limit' => 50, 'default' => '', 'comment' => '暱稱', 'null' => false])
                ->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '郵箱', 'null' => false])
                ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手機', 'null' => false])
                ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '頭像', 'null' => false])
                ->addColumn('gender', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '性別:0=未知,1=男,2=女', 'null' => false])
                ->addColumn('birthday', 'date', ['null' => true, 'default' => null, 'comment' => '生日'])
                ->addColumn('money', 'integer', ['comment' => '餘額', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('score', 'integer', ['comment' => '積分', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('lastlogintime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '上次登錄時間'])
                ->addColumn('lastloginip', 'string', ['limit' => 50, 'default' => '', 'comment' => '上次登錄IP', 'null' => false])
                ->addColumn('loginfailure', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '登錄失敗次數', 'null' => false])
                ->addColumn('joinip', 'string', ['limit' => 50, 'default' => '', 'comment' => '加入IP', 'null' => false])
                ->addColumn('jointime', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '加入時間'])
                ->addColumn('motto', 'string', ['limit' => 255, 'default' => '', 'comment' => '簽名', 'null' => false])
                ->addColumn('password', 'string', ['limit' => 32, 'default' => '', 'comment' => '密碼', 'null' => false])
                ->addColumn('salt', 'string', ['limit' => 30, 'default' => '', 'comment' => '密碼鹽', 'null' => false])
                ->addColumn('status', 'string', ['limit' => 30, 'default' => '', 'comment' => '狀態', 'null' => false])
                ->addColumn('updatetime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->addIndex(['username'], [
                    'unique' => true,
                ])
                ->addIndex(['email'], [
                    'unique' => true,
                ])
                ->addIndex(['mobile'], [
                    'unique' => true,
                ])
                ->create();
        }
    }

    public function userGroup(): void
    {
        if (!$this->hasTable('user_group')) {
            $table = $this->table('user_group', [
                'id'          => false,
                'comment'     => '會員組表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '組名', 'null' => false])
                ->addColumn('rules', 'text', ['null' => true, 'default' => null, 'comment' => '權限節點'])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=禁用,1=啟用', 'null' => false])
                ->addColumn('updatetime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function userMoneyLog(): void
    {
        if (!$this->hasTable('user_money_log')) {
            $table = $this->table('user_money_log', [
                'id'          => false,
                'comment'     => '會員餘額變動表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '會員ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('money', 'integer', ['comment' => '變更餘額', 'default' => 0, 'null' => false])
                ->addColumn('before', 'integer', ['comment' => '變更前餘額', 'default' => 0, 'null' => false])
                ->addColumn('after', 'integer', ['comment' => '變更後餘額', 'default' => 0, 'null' => false])
                ->addColumn('memo', 'string', ['limit' => 255, 'default' => '', 'comment' => '備註', 'null' => false])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function userRule(): void
    {
        if (!$this->hasTable('user_rule')) {
            $table = $this->table('user_rule', [
                'id'          => false,
                'comment'     => '會員菜單權限規則表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '上級菜單', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('type', 'enum', ['values' => 'route,menu_dir,menu,nav_user_menu,nav,button', 'default' => 'menu', 'comment' => '類型:route=路由,menu_dir=菜單目錄,menu=菜單項,nav_user_menu=頂欄會員菜單下拉項,nav=頂欄菜單項,button=頁面按鈕', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '標題', 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '規則名稱', 'null' => false])
                ->addColumn('path', 'string', ['limit' => 100, 'default' => '', 'comment' => '路由路徑', 'null' => false])
                ->addColumn('icon', 'string', ['limit' => 50, 'default' => '', 'comment' => '圖標', 'null' => false])
                ->addColumn('menu_type', 'enum', ['values' => 'tab,link,iframe', 'default' => 'tab', 'comment' => '菜單類型:tab=選項卡,link=鏈接,iframe=Iframe', 'null' => false])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => 'Url', 'null' => false])
                ->addColumn('component', 'string', ['limit' => 100, 'default' => '', 'comment' => '組件路徑', 'null' => false])
                ->addColumn('no_login_valid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '未登錄有效:0=否,1=是', 'null' => false])
                ->addColumn('extend', 'enum', ['values' => 'none,add_rules_only,add_menu_only', 'default' => 'none', 'comment' => '擴展屬性:none=無,add_rules_only=只添加為路由,add_menu_only=只添加為菜單', 'null' => false])
                ->addColumn('remark', 'string', ['limit' => 255, 'default' => '', 'comment' => '備註', 'null' => false])
                ->addColumn('weigh', 'integer', ['comment' => '權重', 'default' => 0, 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '狀態:0=禁用,1=啟用', 'null' => false])
                ->addColumn('updatetime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '更新時間'])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->addIndex(['pid'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }

    public function userScoreLog(): void
    {
        if (!$this->hasTable('user_score_log')) {
            $table = $this->table('user_score_log', [
                'id'          => false,
                'comment'     => '會員積分變動表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '會員ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('score', 'integer', ['comment' => '變更積分', 'default' => 0, 'null' => false])
                ->addColumn('before', 'integer', ['comment' => '變更前積分', 'default' => 0, 'null' => false])
                ->addColumn('after', 'integer', ['comment' => '變更後積分', 'default' => 0, 'null' => false])
                ->addColumn('memo', 'string', ['limit' => 255, 'default' => '', 'comment' => '備註', 'null' => false])
                ->addColumn('createtime', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }

    public function crudLog(): void
    {
        if (!$this->hasTable('crud_log')) {
            $table = $this->table('crud_log', [
                'id'          => false,
                'comment'     => 'CRUD記錄表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('table_name', 'string', ['limit' => 200, 'default' => '', 'comment' => '數據表名', 'null' => false])
                ->addColumn('table', 'text', ['null' => true, 'default' => null, 'comment' => '數據表數據'])
                ->addColumn('fields', 'text', ['null' => true, 'default' => null, 'comment' => '字段數據'])
                ->addColumn('status', 'enum', ['values' => 'delete,success,error,start', 'default' => 'start', 'comment' => '狀態:delete=已刪除,success=成功,error=失敗,start=生成中', 'null' => false])
                ->addColumn('create_time', 'biginteger', ['signed' => false, 'null' => true, 'default' => null, 'comment' => '創建時間'])
                ->create();
        }
    }
}
