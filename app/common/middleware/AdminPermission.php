<?php
declare (strict_types=1);

namespace app\common\middleware;

use think\facade\Session;

class AdminPermission
{
    use \app\common\traits\Base;

    protected array $noAuthRoutes = [
        '/admin/admin.permission/permission',
    ];

    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     *
     * @return Response
     */
    public function handle($request, \Closure $next)
    {

        $url = $request->root() . '/' . $request->controller(true) . '/' . $request->action(true);
        // 否在不受限制的路由列表中
        if (in_array($url, $this->noAuthRoutes)) {
            return $next($request);
        }

        //超级管理员不需要验证
        if (Session::get('admin.id') == 1) return $next($request);

        //没有创建的菜单接口调过如果需要严格模式可以移除
        $model    = new \app\common\model\AdminPermission();
        $menuHref = $model->where('href', '<>', '')->column('href');
        if (!in_array($url, $menuHref)) {
            return $next($request);
        }

        //验证权限
        $href = array_column(Session::get('admin.menu'), 'href');
        if (!in_array($url, $href)) {
            return $request->isAjax() ? $this->json('权限不足', 999) : $this->error('权限不足', '');
        }

        return $next($request);
    }

}
