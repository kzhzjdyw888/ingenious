<?php

use Webman\Route;

Route::group('/adminapi/setting', function () {
    Route::group(function () {
        Route::post('/admin/batch_remove', [app\adminapi\controller\setting\SystemAdmin::class, 'batchUpdate'])->name('批量删除管理员');
        Route::get('/admin/index', [app\adminapi\controller\setting\SystemAdmin::class, 'index'])->name('获取管理员列表');
        Route::get('/admin/detail', [app\adminapi\controller\setting\SystemAdmin::class, 'read'])->name('获取详情');
        Route::post('/admin/save', [app\adminapi\controller\setting\SystemAdmin::class, 'save'])->name('保存管理员');
        Route::post('/admin/update', [app\adminapi\controller\setting\SystemAdmin::class, 'update'])->name('修改管理员');
        Route::post('/admin/delete', [app\adminapi\controller\setting\SystemAdmin::class, 'delete'])->name('删除管理员');
        Route::get('/admin/logout', [app\adminapi\controller\setting\SystemAdmin::class, 'logout'])->name('退出登陆');
        Route::put('/set_status/:id/:status', [app\adminapi\controller\setting\SystemAdmin::class, 'set_status'])->name('修改管理员状态');
        Route::get('/info', [app\adminapi\controller\setting\SystemAdmin::class, 'info'])->name('获取当前管理员信息');
        Route::put('/update_admin', [app\adminapi\controller\setting\SystemAdmin::class, 'update_admin'])->name('修改当前管理员信息');
        Route::put('/set_file_password', [app\adminapi\controller\setting\SystemAdmin::class, 'set_file_password'])->name('设置文件管理密码');
        Route::put('/set_admin_position/:id', [app\adminapi\controller\setting\SystemAdmin::class, 'setPosition'])->name('设置管理员职位');
    });

    /** 权限菜单 */
    Route::group(function () {
        Route::get('/permission', [app\adminapi\controller\setting\SystemMenus::class, 'permission'])->name('获取菜单');
        Route::get('/menus/unique', [app\adminapi\controller\setting\SystemMenus::class, 'unique'])->name('获取菜单权限和权限标识');
        Route::post('/menus/batch', [app\adminapi\controller\setting\SystemMenus::class, 'batchSave'])->name('批量保存权限');

        //权限菜单资源路由
        Route::get('/menus/page', [app\adminapi\controller\setting\SystemMenus::class, 'index'])->name('获取权限菜单列表');
        Route::get('/menus/edit', [app\adminapi\controller\setting\SystemMenus::class, 'edit'])->name('获取权限菜单表单');
//        Route::get('/menus/create', [app\adminapi\controller\setting\SystemMenus::class, 'create'])->name('获取修改权限菜单表单');
        Route::get('/menus/detail', [app\adminapi\controller\setting\SystemMenus::class, 'read'])->name('查看权限菜单信息');
        Route::post('/menus/save', [app\adminapi\controller\setting\SystemMenus::class, 'save'])->name('保存权限菜单');
        Route::post('/menus/update', [app\adminapi\controller\setting\SystemMenus::class, 'update'])->name('修改权限菜单');
        Route::post('/menus/delete', [app\adminapi\controller\setting\SystemMenus::class, 'delete'])->name('删除权限菜单');

        Route::get('/ruleList', [app\adminapi\controller\setting\SystemMenus::class, 'ruleList'])->name('权限规则列表');
        Route::get('/rule_cate', [app\adminapi\controller\setting\SystemMenus::class, 'ruleCate'])->name('权限规则分类');
        Route::post('/menus/show', [app\adminapi\controller\setting\SystemMenus::class, 'show'])->name('修改权限规格显示状态');
    });

})->middleware([
    app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    app\adminapi\middleware\AdminAuthPermissionMiddleware::class,
    app\adminapi\middleware\AdminLogMiddleware::class,
]);

