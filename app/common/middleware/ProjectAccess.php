<?php
// app/common/middleware/ProjectAccess.php
namespace app\common\middleware;

use Closure;
use think\Request;
use app\admin\model\Project;
use app\admin\model\Project\User;

class ProjectAccess
{
    public function handle($request, Closure $next)
    {
        // 獲取當前專案ID
        $projectId = session('current_project_id');
        if (!$projectId) {
            return redirect('/');
        }

        // 驗證當前用戶是否有權限訪問該專案
        $auth = \app\common\library\Auth::instance();
        if ($auth->isLogin()) {
            $userId = $auth->id;
            $hasAccess = Project\User::where('project_id', $projectId)
                ->where('user_id', $userId)
                ->find();

            if (!$hasAccess) {
                return json(['code' => 0, 'msg' => '您沒有權限訪問此專案']);
            }
        }

        // 把專案ID注入到請求中，方便控制器使用
        $request->projectId = $projectId;

        return $next($request);
    }
}