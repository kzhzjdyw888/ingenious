<?php

use think\facade\Route;

/**
 *Miss路由
 */
Route::miss(function () {
    $appRequest = request()->pathinfo();

    if ($appRequest === null) {
        $appName = '';
    } else {
        $appRequest = str_replace('//', '/', $appRequest);
        $appName    = explode('/', $appRequest)[0] ?? '';
    }

    switch (strtolower($appName)) {
        case config('app.admin_prefix', 'admin'):
            return view(app()->getRootPath() . 'public' . DS . config('app.admin_prefix', 'admin') . DS . 'index.html');
            break;
    }

});
