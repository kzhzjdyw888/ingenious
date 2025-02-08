<?php

use Webman\Route;

Route::group('/adminapi/wf', function () {
    /**流程分类**/
    Route::group('/category', function () {
        Route::get('/select_tree', [app\adminapi\controller\wf\CategoryController::class, 'typeTree'])->name('获取分类树');
        Route::get('/page', [app\adminapi\controller\wf\CategoryController::class, 'select'])->name('获取列表');
        Route::get('/detail', [app\adminapi\controller\wf\CategoryController::class, 'read'])->name('获取详情');
        Route::post('/save', [app\adminapi\controller\wf\CategoryController::class, 'save'])->name('保存分类');
        Route::post('/update', [app\adminapi\controller\wf\CategoryController::class, 'update'])->name('保存分类');
        Route::post('/delete', [app\adminapi\controller\wf\CategoryController::class, 'delete'])->name('删除分类');
        Route::post('/remove', [app\adminapi\controller\wf\CategoryController::class, 'remove'])->name('批量删除模型分类');
    });
    /**流程设计**/
    Route::group('/designer', function () {
        Route::post('/batch_remove', [app\adminapi\controller\wf\DesignerController::class, 'batchRemove'])->name('批量删除模型');
        Route::post('/update_define', [app\adminapi\controller\wf\DesignerController::class, 'updateDefine'])->name('保存模型定义');
        Route::post('/deploy', [app\adminapi\controller\wf\DesignerController::class, 'deploy'])->name('流程部署');
        Route::post('/redeploy', [app\adminapi\controller\wf\DesignerController::class, 'redeploy'])->name('流程重新部署');
        Route::get('/page', [app\adminapi\controller\wf\DesignerController::class, 'index'])->name('获取列表');
        Route::get('/detail', [app\adminapi\controller\wf\DesignerController::class, 'read'])->name('获取详情');
        Route::post('/save', [app\adminapi\controller\wf\DesignerController::class, 'save'])->name('保存设计');
        Route::post('/update', [app\adminapi\controller\wf\DesignerController::class, 'update'])->name('更新设计');
        Route::post('/delete', [app\adminapi\controller\wf\DesignerController::class, 'delete'])->name('删除设计');
        Route::post('/remove', [app\adminapi\controller\wf\DesignerController::class, 'batchRemove'])->name('批量删除模型设计');
    });
    /**流程定义**/
    Route::group('/process_define', function () {
        Route::post('/set_status', [app\adminapi\controller\wf\DefineController::class, 'setStatus'])->name('更新流程状态');
        Route::post('/start_and_execute', [app\adminapi\controller\wf\DefineController::class, 'startAndExecute'])->name('启动流程');
        //流程定义收藏
        Route::get('/favorite/page', [app\adminapi\controller\wf\DefineController::class, 'favoritePage'])->name('收藏列表');
        Route::post('/favorite/setting', [app\adminapi\controller\wf\DefineController::class, 'processFavorite'])->name('流程收藏');
        Route::post('/favorite/remove', [app\adminapi\controller\wf\DefineController::class, 'favoriteDelete'])->name('收藏删除');
        //流程定义
        Route::get('/page', [app\adminapi\controller\wf\DefineController::class, 'index'])->name('获取列表');
        Route::get('/detail', [app\adminapi\controller\wf\DefineController::class, 'read'])->name('获取详情');
//        Route::post('/save', [app\adminapi\controller\wf\DefineController::class, 'save'])->name('保存分类');
//        Route::post('/update', [app\adminapi\controller\wf\DefineController::class, 'update'])->name('保存分类');
        Route::post('/delete', [app\adminapi\controller\wf\DefineController::class, 'delete'])->name('删除分类');
//        Route::post('/remove', [app\adminapi\controller\wf\DefineController::class, 'remove'])->name('批量删除模型分类');
    });
    /**流程实例**/
    Route::group('/process_instance', function () {
        Route::get('/highlight', [app\adminapi\controller\wf\InstanceController::class, 'highLightData'])->name('获取高亮数据');
        Route::get('/approval_record', [app\adminapi\controller\wf\InstanceController::class, 'approvalRecord'])->name('获取记录');
        Route::post('/withdraw', [app\adminapi\controller\wf\InstanceController::class, 'withdraw'])->name('撤回流程');
//        Route::post('/undo', [app\adminapi\controller\wf\InstanceController::class, 'undo'])->name('作废流程');
        Route::post('/cascade_delete', [app\adminapi\controller\wf\InstanceController::class, 'cascadeDelete'])->name('级联删除流程定义');
        Route::get('/cc_list', [app\adminapi\controller\wf\InstanceController::class, 'ccList'])->name('获取抄送列表');

        //我发起的申请
        Route::get('/management', [app\adminapi\controller\wf\InstanceController::class, 'management'])->name('流程追踪列表');
        Route::get('/page', [app\adminapi\controller\wf\InstanceController::class, 'index'])->name('获取列表');
        Route::get('/detail', [app\adminapi\controller\wf\InstanceController::class, 'detail'])->name('获取详情');
//        Route::post('/save', [app\adminapi\controller\wf\InstanceController::class, 'save'])->name('保存实例');
//        Route::post('/update', [app\adminapi\controller\wf\InstanceController::class, 'update'])->name('更新实例');
        Route::post('/delete', [app\adminapi\controller\wf\InstanceController::class, 'cascadeDelete'])->name('删除实例');
//        Route::post('/remove', [app\adminapi\controller\wf\InstanceController::class, 'batchCascadeDelete'])->name('批量删除模型实例');
    });

    /**流程任务**/
    Route::group('/process_task', function () {
        Route::get('/done/page', [app\adminapi\controller\wf\TaskController::class, 'doneList'])->name('我的代办');
        Route::get('/todo/page', [app\adminapi\controller\wf\TaskController::class, 'todoList'])->name('我的已办');
        Route::get('/user', [app\adminapi\controller\wf\TaskController::class, 'userList'])->name('获取用户列表');
        Route::post('/execute', [app\adminapi\controller\wf\TaskController::class, 'execute'])->name('执行任务');
        Route::post('/surrogate', [app\adminapi\controller\wf\TaskController::class, 'surrogate'])->name('委托代理');
        Route::get('/detail', [app\adminapi\controller\wf\TaskController::class, 'detail'])->name('获取任务详情');
        Route::post('/jump_able_task_name_list', [app\adminapi\controller\wf\TaskController::class, 'jumpAbleTaskNameList'])->name('获取跳转节点');
    });

    /**其他**/
    Route::group('/other', function () {
        Route::get('/user/list', [app\adminapi\controller\wf\OtherHandleController::class, 'userList'])->name('审批人列表');
        Route::get('/user/info', [app\adminapi\controller\wf\OtherHandleController::class, 'userInfo'])->name('审批人详情');
        Route::get('/assignment_handler', [app\adminapi\controller\wf\OtherHandleController::class, 'assignmentHandler'])->name('处理类列表');
        Route::get('/form/option', [app\adminapi\controller\wf\OtherHandleController::class, 'formHandler'])->name('获取表单实例url');
        Route::get('/candidate_page', [app\adminapi\controller\wf\OtherHandleController::class, 'candidatePage'])->name('获取候选人列表');
        Route::get('/carbon_page', [app\adminapi\controller\wf\OtherHandleController::class, 'carbonPage'])->name('获取候选人列表');
    });

})->middleware([
    app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    app\adminapi\middleware\AdminAuthPermissionMiddleware::class,
    app\adminapi\middleware\AdminLogMiddleware::class,

]);

Route::disableDefaultRoute();
