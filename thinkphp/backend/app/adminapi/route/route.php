<?php

use think\facade\Route;
use think\facade\Config;
use think\Response;

/**
 * 无需授权的接口
 */
Route::group(function () {
    Route::get('test', 'Login/test')->name('Test')->option(['real_name' => '测试']);
    Route::post('login', 'Login/login')->name('AdminLogin')->option(['real_name' => '下载表备份记录']);
    Route::get('login/info', 'Login/info')->option(['real_name' => '登录信息']);
    Route::get('captcha_pro', 'Login/captcha')->name('')->option(['real_name' => '获取验证码']);
    Route::get('ajcaptcha', 'Login/ajcaptcha')->name('ajcaptcha')->option(['real_name' => '获取验证码']);
    Route::post('ajcheck', 'Login/ajcheck')->name('ajcheck')->option(['real_name' => '一次验证']);
    Route::get('demo/user/page', 'Login/demoUser')->name('AdminDemoUser')->option(['real_name' => '演示用户列表']);
    Route::post('demo/user/act', 'Login/actUser')->name('AdminActUser')->option(['real_name' => '用户扮演']);
    Route::post('doc/verification_key', 'Login/verificationKey')->name('AdminActUser')->option(['real_name' => '文档密钥验证']);
})->allowCrossDomain([
        'Access-Control-Allow-Origin'        => '*',
        'Access-Control-Allow-Credentials'   => 'true',
        'Access-Control-Max-Age'             => 600,
    ]);
//    ->middleware([\phoenix\middleware\AllowCrossOriginMiddleware::class])->option(['mark' => 'login', 'mark_name' => '登录相关']);

/**
 * miss 路由
 */
Route::miss(function () {
    if (app()->request->isOptions()) {
        $header                                = Config::get('cookie.header');
        $header['Access-Control-Allow-Origin'] = app()->request->header('origin');
        return Response::create('ok')->code(200)->header($header);
    } else
        return Response::create()->code(404);
});
