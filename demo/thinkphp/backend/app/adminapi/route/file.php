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
 * 附件相关路由
 */
Route::group('file', function () {
    //附件列表
    Route::get('file', 'v1.file.SystemAttachment/index')->option(['real_name' => '图片附件列表']);
    //删除图片和数据记录
    Route::post('file/delete', 'v1.file.SystemAttachment/delete')->option(['real_name' => '删除图片']);
    //移动图片分来表单
    Route::get('file/move', 'v1.file.SystemAttachment/move')->option(['real_name' => '移动图片分类表单']);
    //移动图片分类
    Route::put('file/do_move', 'v1.file.SystemAttachment/moveImageCate')->option(['real_name' => '移动图片分类']);
    //修改图片名称
    Route::put('file/update/:id', 'v1.file.SystemAttachment/update')->option(['real_name' => '修改图片名称']);
    //上传图片
    Route::post('upload/[:upload_type]', 'v1.file.SystemAttachment/upload')->option(['real_name' => '上传图片']);
    //附件分类管理资源路由
    Route::resource('category', 'v1.file.SystemAttachmentCategory')->except(['create', 'edit'])->option([
        'real_name' => [
            'index'  => '获取附件分类管理列表',
            'save'   => '保存附件分类管理',
            'update' => '修改附件分类管理',
            'delete' => '删除附件分类管理',
        ],

    ]);
    //获取上传类型
    Route::get('upload_type', 'v1.file.SystemAttachment/uploadType')->option(['real_name' => '上传类型']);
    //分片上传本地视频
    Route::post('video_upload', 'v1.file.SystemAttachment/videoUpload')->option(['real_name' => '分片上传本地视频']);

    //获取扫码上传页面链接以及参数
    Route::get('scan_upload/qrcode', 'v1.file.SystemAttachment/scanUploadQrcode')->option(['real_name' => '扫码上传页面链接']);
    //删除扫码上传token
    Route::delete('scan_upload/qrcode', 'v1.file.SystemAttachment/removeUploadQrcode')->option(['real_name' => '删除扫码上传页面链接']);
    //获取扫码上传的图片数据
    Route::get('scan_upload/image/:scan_token', 'v1.file.SystemAttachment/scanUploadImage')->option(['real_name' => '获取扫码上传的图片数据']);
    //网络图片上传
    Route::post('online_upload', 'v1.file.SystemAttachment/onlineUpload')->option(['real_name' => '网络图片上传']);
})->middleware([
    \phoenix\middleware\AllowCrossOriginMiddleware::class,
    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
    \app\adminapi\middleware\AdminAuthPermissionMiddleware::class,
    \app\adminapi\middleware\AdminLogMiddleware::class,
])->option(['mark' => 'file', 'mark_name' => '上传管理']);
