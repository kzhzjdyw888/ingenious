<?php

namespace app\adminapi\controller\file;

use app\adminapi\controller\AuthController;
use app\common\CacheService;
use app\common\Json;
use app\services\system\attachment\SystemAttachmentServices;
use support\Container;
use support\Request;

/**
 * 附件管理类
 * Class SystemAttachment
 *
 * @package app\adminapi\controller\v1\file
 */
class SystemAttachment extends AuthController
{
    /**
     * @var SystemAttachmentServices
     */
    protected SystemAttachmentServices $service;

    public function __construct()
    {
        parent::__construct();
        $this->service = Container::make(SystemAttachmentServices::class);
    }

    /**
     * 显示列表
     */
    public function index(Request $request): \support\Response
    {
        $where = $request->getMore([
            ['pid', 0],
            ['real_name', ''],
        ]);
        return Json::success($this->service->getImageList($where));
    }

    /**
     * 删除指定资源
     *
     * @return \support\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete(Request $request): \support\Response
    {
        [$ids] = $request->postMore([
            ['ids', ''],
        ], true);
        $this->service->del($ids);
        return Json::success(100002);
    }

    /**
     * 图片上传
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function upload(Request $request): \support\Response
    {
        [$pid, $file, $menuName] = $request->postMore([
            ['pid', '-1'],
            ['file', 'file'],
            ['name', ''],
        ], true);
        $upload_type = $request->input('upload_type', 0);
        $type        = $request->input('type', 0);
        $res         = $this->service->upload($pid, $file, $upload_type, $type, $menuName);
        return Json::success('上传成功', ['src' => $res]);
    }

    /**
     * 移动图片
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function moveImageCate(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['pid', 0],
            ['images', ''],
        ]);
        $this->service->move($data);
        return Json::success('移动成功');
    }

    /**
     * 修改文件名
     *
     * @param $id
     *
     * @return mixed
     */
    public function update(Request $request): \support\Response
    {
        $realName = $request->input('real_name', '');
        $category = $request->input('pid', '');
        $id       = $request->input('id');
        if (!$realName) {
            return Json::fail(400104);
        }
        $data = ['real_name' => $realName];
        if ($category) {
            $data['pid'] = $category;
        }
        $this->service->update($id, $data);
        return Json::success('修改成功');
    }

    /**
     * 获取上传类型
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function uploadType(Request $request): \support\Response
    {
        $data['upload_type'] = (string)sys_config('upload_type', 1);
        return Json::success($data);
    }

    /**
     * 视频分片上传
     *
     * @return mixed
     */
    public function videoUpload(Request $request)
    {
        $data = $request->postMore([
            ['chunkNumber', 0],//第几分片
            ['currentChunkSize', 0],//分片大小
            ['chunkSize', 0],//总大小
            ['totalChunks', 0],//分片总数
            ['file', 'file'],//文件
            ['md5', ''],//MD5
            ['filename', ''],//文件名称
        ]);
        $res  = $this->service->videoUpload($data, $_FILES['file']);
        return Json::success($res);
    }

    /**
     * 获取扫码上传页面链接以及参数
     *
     * @return \support\Response
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/06/13
     */
    public function scanUploadQrcode(Request $request): \support\Response
    {
        [$pid] = $request->getMore([
            ['pid', 0],
        ], true);
        $uploadToken = md5(time());
        CacheService::set('scan_upload', $uploadToken, 600);
        $url = sys_config('site_url') . '/app/upload?pid=' . $pid . '&token=' . $uploadToken;
        return Json::success(['url' => $url]);
    }

    /**
     * 删除二维码
     *
     * @return \support\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/6/26
     */
    public function removeUploadQrcode(Request $request): \support\Response
    {
        CacheService::delete('scan_upload');
        return Json::success();
    }

    /**
     * 获取扫码上传的图片数据
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/06/13
     */
    public function scanUploadImage(Request $request): \support\Response
    {
        return Json::success($this->service->scanUploadImage($request));
    }

    /**
     * 网络图片上传
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @throws \Exception
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/06/13
     */
    public function onlineUpload(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['pid', 0],
            ['images', []],
        ]);
        $this->service->onlineUpload($data);
        return Json::success('上传成功');
    }
}
