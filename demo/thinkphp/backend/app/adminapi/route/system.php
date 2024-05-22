<?php

use think\facade\Route;
use think\facade\Config;
use think\Response;

//维护相关路由
Route::group('system', function () {
    /** 系统日志 */
    Route::group(function () {
        //系统日志
        Route::get('log/select', 'v1.system.SystemLog/index')->name('SystemLog')->option(['real_name' => '系统日志']);
        //系统日志管理员搜索条件
        Route::get('log/search_admin', 'v1.system.SystemLog/search_admin')->option(['real_name' => '系统日志管理员搜索条件']);
        //文件校验
        Route::get('file', 'v1.system.SystemFile/index')->name('SystemFile')->option(['real_name' => '文件校验']);
    })->option(['parent' => 'system', 'cate_name' => '系统日志']);

    /** 数据清除 */
    Route::group(function () {
        //清除缓存
        Route::get('refresh_cache/cache', 'v1.system.Clear/refresh_cache')->option(['real_name' => '清除系统缓存']);
        //清除日志
        Route::get('refresh_cache/log', 'v1.system.Clear/delete_log')->option(['real_name' => '清除系统日志']);
    })->option(['parent' => 'system', 'cate_name' => '数据清除']);

    /** 数据备份 */
    Route::group(function () {
        //数据所有表
        Route::get('backup/select', 'v1.system.SystemDatabackup/index')->option(['real_name' => '数据库所有表']);
        //数据备份详情
        Route::get('backup/read', 'v1.system.SystemDatabackup/read')->option(['real_name' => '数据备份详情']);
        //更新数据表或者表字段备注
        Route::post('database/update_mark', 'v1.system.SystemDatabackup/updateMark')->option(['real_name' => '更新数据表或者表字段备注']);
        //数据备份 优化表
        Route::put('backup/optimize', 'v1.system.SystemDatabackup/optimize')->option(['real_name' => '数据备份优化表']);
        //数据备份 修复表
        Route::put('backup/repair', 'v1.system.SystemDatabackup/repair')->option(['real_name' => '数据备份修复表']);
        //数据备份 备份表
        Route::put('backup/backup', 'v1.system.SystemDatabackup/backup')->option(['real_name' => '数据备份备份表']);
        //备份记录
        Route::get('backup/file_list', 'v1.system.SystemDatabackup/fileList')->option(['real_name' => '数据库备份记录']);
        //删除备份记录
        Route::delete('backup/del_file', 'v1.system.SystemDatabackup/delFile')->option(['real_name' => '删除数据库备份记录']);
        //导入备份记录表
        Route::post('backup/import', 'v1.system.SystemDatabackup/import')->option(['real_name' => '导入数据库备份记录']);
        //下载备份记录表
//        Route::get('backup/download', 'v1.system.SystemDatabackup/downloadFile');
    })->option(['parent' => 'system', 'cate_name' => '数据备份']);

    /** 系统路由 */
    Route::group(function () {
        //同步路由接口
        Route::get('route/sync_route/[:appName]', 'v1.system.SystemRoute/syncRoute')->option(['real_name' => '同步路由']);
        //获取路由tree行数据
        Route::get('route/tree', 'v1.system.SystemRoute/tree')->option(['real_name' => '获取路由tree']);
        //权限路由
        Route::delete('route/:id', 'v1.system.SystemRoute/delete')->option(['real_name' => '删除路由权限']);
        //查看路由权限
        Route::get('route/:id', 'v1.system.SystemRoute/read')->option(['real_name' => '查看路由权限']);
        //保存路由权限
        Route::post('route/:id', 'v1.system.SystemRoute/save')->option(['real_name' => '保存路由权限']);
        //路由分类
        Route::resource('route_cate', 'v1.system.SystemRouteCate')->except(['read'])->option([
            'real_name' => [
                'index'  => '获取路由分类列表',
                'create' => '获取创建路由分类表单',
                'save'   => '保存路由分类',
                'edit'   => '获取修改路由分类表单',
                'update' => '修改路由分类',
                'delete' => '删除路由分类',
            ],
        ]);
    })->option(['parent' => 'system', 'cate_name' => '系统路由']);

    /** 定时任务 */
    Route::group(function () {
        //定时任务列表
        Route::get('crontab/list', 'v1.system.SystemCrontab/getTimerList')->option(['real_name' => '定时任务列表']);
        //定时任务类型
        Route::get('crontab/mark', 'v1.system.SystemCrontab/getMarkList')->option(['real_name' => '定时任务类型']);
        //定时任务详情
        Route::get('crontab/info/:id', 'v1.system.SystemCrontab/getTimerInfo')->option(['real_name' => '定时任务详情']);
        //定时任务添加编辑
        Route::post('crontab/save', 'v1.system.SystemCrontab/saveTimer')->option(['real_name' => '定时任务添加编辑']);
        //删除定时任务
        Route::delete('crontab/del/:id', 'v1.system.SystemCrontab/delTimer')->option(['real_name' => '删除定时任务']);
        //定时任务是否开启开关
        Route::get('crontab/set_open/:id/:is_open', 'v1.system.SystemCrontab/setTimerStatus')->option(['real_name' => '定时任务是否开启开关']);
    })->option(['parent' => 'system', 'cate_name' => '定时任务']);

    /**字典**/
    Route::group(function () {
        Route::get('dict/:name/name', 'v1.system.SystemDict/get')->option(['real_name' => '字典获取']);
        Route::put('dict/batch/delete', 'v1.system.SystemDict/batchDelete')->option(['real_name' => '批量删除']);
        Route::resource('dict', 'v1.system.SystemDict')->except(['edit', 'create'])->option([
            'real_name' => [
                'index'  => '获取列表',
                'read'   => '字典详情',
                'save'   => '保存字典',
                'update' => '更新字典',
                'delete' => '删除字典',
            ],
        ]);
    })->option(['parent' => 'system', 'cate_name' => '字典管理']);


})->middleware([
    \phoenix\middleware\AllowCrossOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminAuthPermissionMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class,
])->option(['mark' => 'system', 'mark_name' => '系统维护']);;
