<?php
/**
 * This file is part of webman.
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use support\Response;
use Webman\Route;

// 加载adminapi应用下的路由配置
require_once app_path('adminapi/config/route.php');

//所有预检路由通过
Route::options('[{path:.+}]', function () {
    return response('');
});

//查看php相关扩展生产环境不适用
//Route::get('/phpinfo', function () {
//    ob_start();
//    phpinfo();
//    $phpinfo = ob_get_clean();
//    $styledPhpinfo = '<!DOCTYPE html>
//                            <html>
//                            <head>
//                                <title>PHP Configuration Information</title>
//                                <style>
//                                    body {
//                                        font-family: Arial, sans-serif;
//                                        margin: 20px;
//                                    }
//                                    h1 {
//                                        color: #333;
//                                    }
//                                    table {
//                                        border-collapse: collapse;
//                                        width: 100%;
//                                    }
//                                    th, td {
//                                        border: 1px solid #ddd;
//                                        padding: 8px;
//                                    }
//                                </style>
//                            </head>
//                            <body>
//                                <h1>PHP Configuration Information</h1>';
//    $styledPhpinfo .= $phpinfo;
//    $styledPhpinfo .= '</body></html>';
//    return new Response($styledPhpinfo);
//});

Route::fallback(function (\support\Request $request) {
    return json(['code' => 404, 'msg' => '404 not found']);
});

Route::disableDefaultRoute();
