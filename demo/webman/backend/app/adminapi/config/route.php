<?php

use Webman\Route;

require_once app_path('adminapi/route/setting.php');
require_once app_path('adminapi/route/wf.php');

/**
 * 无需授权的接口
 */
Route::group('/adminapi', function () {
    Route::post('/login', [\app\adminapi\controller\LoginController::class, 'login'])->name('用户登录');
    Route::get('/demo/user/page', [\app\adminapi\controller\LoginController::class, 'demoUser'])->name('演示用户列表');
    Route::post('/demo/user/act', [\app\adminapi\controller\LoginController::class, 'actUser'])->name('用户扮演');
});

