<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
use think\facade\Route;

/**
 * 审批流相关路由
 */
Route::group('wf', function () {
    /**流程分类**/
    Route::group(function () {
        Route::get('category/select_tree', 'v1.wf.Category/typeTree')->option(['real_name' => '获取分类树']);
        Route::get('category/page', 'v1.wf.Category/index')->option(['real_name' => '获取列表']);
        Route::get('category/detail', 'v1.wf.Category/read')->option(['real_name' => '获取详情']);
        Route::post('category/save', 'v1.wf.Category/save')->option(['real_name' => '保存分类']);
        Route::post('category/update', 'v1.wf.Category/update')->option(['real_name' => '保存分类']);
        Route::post('category/delete', 'v1.wf.Category/delete')->option(['real_name' => '删除分类']);
        Route::post('category/remove', 'v1.wf.Category/batchRemove')->option(['real_name' => '批量删除模型分类']);
    })->option(['parent' => 'wf', 'cate_name' => '模型分类']);

    /**流程设计**/
    Route::group(function () {
        Route::post('designer/batch_remove', 'v1.wf.Designer/batchRemove')->option(['real_name' => '批量删除模型']);
        Route::post('designer/update_define', 'v1.wf.Designer/updateDefine')->option(['real_name' => '保存模型定义']);
        Route::post('designer/deploy', 'v1.wf.Designer/deploy')->option(['real_name' => '流程部署']);
        Route::post('designer/redeploy', 'v1.wf.Designer/redeploy')->option(['real_name' => '流程重新部署']);

        Route::get('designer/page', 'v1.wf.Designer/index')->option(['real_name' => '获取列表']);
        Route::get('designer/detail', 'v1.wf.Designer/read')->option(['real_name' => '获取详情']);
        Route::post('designer/save', 'v1.wf.Designer/save')->option(['real_name' => '保存设计']);
        Route::post('designer/update', 'v1.wf.Designer/update')->option(['real_name' => '更新设计']);
        Route::post('designer/delete', 'v1.wf.Designer/delete')->option(['real_name' => '删除设计']);
        Route::post('designer/remove', 'v1.wf.Designer/batchRemove')->option(['real_name' => '批量删除模型设计']);

//        Route::resource('designer', 'v1.wf.Designer')->except(['create', 'edit'])->option([
//            'real_name' => [
//                'index'  => '获取模型列表',
//                'save'   => '保存模型',
//                'update' => '修改模型',
//                'delete' => '删除模型',
//            ],
//        ]);
    })->option(['parent' => 'wf', 'cate_name' => '模型设计']);

    /**流程定义**/
    Route::group(function () {
        Route::post('process_define/set_status', 'v1.wf.Define/setStatus')->option(['real_name' => '更新流程状态']);
        Route::post('process_define/start_and_execute', 'v1.wf.Define/startAndExecute')->option(['real_name' => '启动流程']);
        //流程定义收藏
        Route::get('process_define/favorite/page', 'v1.wf.Define/favoritePage')->option(['real_name' => '收藏列表']);
        Route::post('process_define/favorite/setting', 'v1.wf.Define/processFavorite')->option(['real_name' => '流程收藏']);
        Route::post('process_define/favorite/remove', 'v1.wf.Define/favoriteDelete')->option(['real_name' => '收藏删除']);
        //流程定义
        Route::get('process_define/page', 'v1.wf.Define/index')->option(['real_name' => '获取列表']);
        Route::get('process_define/detail', 'v1.wf.Define/read')->option(['real_name' => '获取详情']);
        Route::post('process_define/save', 'v1.wf.Define/save')->option(['real_name' => '保存分类']);
        Route::post('process_define/update', 'v1.wf.Define/update')->option(['real_name' => '保存分类']);
        Route::post('process_define/delete', 'v1.wf.Define/delete')->option(['real_name' => '删除分类']);
        Route::post('process_define/remove', 'v1.wf.Define/batchRemove')->option(['real_name' => '批量删除模型分类']);
    })->option(['parent' => 'wf', 'cate_name' => '流程定义']);

    /**流程实例**/
    Route::group(function () {
        Route::get('process_instance/highlight', 'v1.wf.Instance/highLightData')->option(['real_name' => '获取高亮数据']);
        Route::get('process_instance/approval_record', 'v1.wf.Instance/approvalRecord')->option(['real_name' => '获取记录']);
        Route::post('process_instance/withdraw', 'v1.wf.Instance/withdraw')->option(['real_name' => '撤回流程']);
        Route::post('process_instance/undo', 'v1.wf.Instance/undo')->option(['real_name' => '作废流程']);
        Route::post('process_instance/cascade_delete', 'v1.wf.Instance/cascadeDelete')->option(['real_name' => '级联删除流程定义']);
        Route::get('process_instance/cc_list', 'v1.wf.Instance/ccList')->option(['real_name' => '获取抄送列表']);

        //我发起的申请
        Route::get('process_instance/management', 'v1.wf.Instance/management')->option(['real_name' => '流程追踪列表']);
        Route::get('process_instance/page', 'v1.wf.Instance/index')->option(['real_name' => '获取列表']);
        Route::get('process_instance/detail', 'v1.wf.Instance/detail')->option(['real_name' => '获取详情']);
        Route::post('process_instance/save', 'v1.wf.Instance/save')->option(['real_name' => '保存实例']);
        Route::post('process_instance/update', 'v1.wf.Instance/update')->option(['real_name' => '更新实例']);
        Route::post('process_instance/delete', 'v1.wf.Instance/cascadeDelete')->option(['real_name' => '删除实例']);
        Route::post('process_instance/remove', 'v1.wf.Instance/batchCascadeDelete')->option(['real_name' => '批量删除模型实例']);

//        Route::resource('process_instance', 'v1.wf.Instance')->except(['create', 'edit'])->option([
//            'real_name' => [
//                'index'  => '获取实例列表',
//                'save'   => '保存实例定义',
//                'update' => '修改流程定义',
//                'delete' => '删除流程定义',
//            ],
//        ]);
    })->option(['parent' => 'wf', 'cate_name' => '流程实例']);

    /**流程任务**/
    Route::group('process_task', function () {
        Route::get('done/page', 'v1.wf.Task/doneList')->option(['real_name' => '我的代办']);
        Route::get('todo/page', 'v1.wf.Task/todoList')->option(['real_name' => '我的已办']);
        Route::get('user', 'v1.wf.Task/userList')->option(['real_name' => '获取用户列表']);
        Route::post('execute', 'v1.wf.Task/execute')->option(['real_name' => '执行任务']);
        Route::post('backoff', 'v1.wf.Task/backOff')->option(['real_name' => '任务驳回']);
        Route::post('surrogate', 'v1.wf.Task/surrogate')->option(['real_name' => '委托代理']);
        Route::get('detail', 'v1.wf.Task/detail')->option(['real_name' => '获取任务详情']);
        Route::post('jump_able_task_name_list', 'v1.wf.Task/jumpAbleTaskNameList')->option(['real_name' => '获取跳转节点']);
        Route::post('listHisByOrderId', 'v1.wf.Task/listHisByOrderId')->option(['real_name' => '获取实例历史记录']);
    })->option(['parent' => 'wf', 'cate_name' => '流程任务']);

    Route::group('other', function () {
        Route::get('user/list', 'v1.wf.OtherHandle/userList')->option(['real_name' => '审批人列表']);
        Route::get('user/info', 'v1.wf.OtherHandle/userInfo')->option(['real_name' => '审批人详情']);
        Route::get('assignment_handler', 'v1.wf.OtherHandle/assignmentHandler')->option(['real_name' => '处理类列表']);
        Route::get('form/option', 'v1.wf.OtherHandle/formHandler')->option(['real_name' => '获取表单实例url']);
        Route::get('candidate_page', 'v1.wf.OtherHandle/candidatePage')->option(['real_name' => '获取候选人列表']);
        Route::get('carbon_page', 'v1.wf.OtherHandle/carbonPage')->option(['real_name' => '获取候选人列表']);

    });

})->middleware([
    \phoenix\middleware\AllowCrossOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminAuthPermissionMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class,
])->option(['mark' => 'wf', 'mark_name' => '工作流程']);
