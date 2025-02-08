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
namespace app\adminapi\controller\v1\file;

use app\adminapi\controller\AuthController;
use app\services\system\attachment\SystemAttachmentServices;
use phoenix\services\CacheService;
use think\App;

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

    /**
     * @param App                      $app
     * @param SystemAttachmentServices $service
     */
    public function __construct(App $app, SystemAttachmentServices $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 显示列表
     */
    public function index(): \think\Response\Json
    {
        $where = $this->request->getMore([
            ['pid', 0],
            ['real_name', ''],
        ]);
        return app('json')->success($this->service->getImageList($where));
    }

    /**
     * 删除指定资源
     *
     * @return \think\Response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete(): \think\Response\Json
    {
        [$ids] = $this->request->postMore([
            ['ids', ''],
        ], true);
        $this->service->del($ids);
        return app('json')->success(100002);
    }

    /**
     * 图片上传
     *
     * @param int $upload_type
     * @param int $type
     *
     * @return mixed
     */
    public function upload(int $upload_type = 0, int $type = 0): \think\Response\Json
    {
        [$pid, $file, $menuName] = $this->request->postMore([
            ['pid', '-1'],
            ['file', 'file'],
            ['name', ''],
        ], true);
        $res = $this->service->upload($pid, $file, $upload_type, $type, $menuName);
        return app('json')->success(100032, ['src' => $res]);
    }

    /**
     * 移动图片
     *
     * @return mixed
     */
    public function moveImageCate()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['images', ''],
        ]);
        $this->service->move($data);
        return app('json')->success(100034);
    }

    /**
     * 修改文件名
     *
     * @param $id
     *
     * @return mixed
     */
    public function update($id):\think\Response\Json
    {
        $realName = $this->request->post('real_name', '');
        $category = $this->request->post('pid', '');
        if (!$realName) {
            return app('json')->fail(400104);
        }
        $data = ['real_name' => $realName];
        if ($category) {
            $data['pid'] = $category;
        }
        $this->service->update($id, $data);
        return app('json')->success(100001);
    }

    /**
     * 获取上传类型
     *
     * @return mixed
     */
    public function uploadType()
    {
        $data['upload_type'] = (string)sys_config('upload_type', 1);
        return app('json')->success($data);
    }

    /**
     * 视频分片上传
     *
     * @return mixed
     */
    public function videoUpload()
    {
        $data = $this->request->postMore([
            ['chunkNumber', 0],//第几分片
            ['currentChunkSize', 0],//分片大小
            ['chunkSize', 0],//总大小
            ['totalChunks', 0],//分片总数
            ['file', 'file'],//文件
            ['md5', ''],//MD5
            ['filename', ''],//文件名称
        ]);
        $res  = $this->service->videoUpload($data, $_FILES['file']);
        return app('json')->success($res);
    }

    /**
     * 获取扫码上传页面链接以及参数
     *
     * @return \think\Response
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/06/13
     */
    public function scanUploadQrcode()
    {
        [$pid] = $this->request->getMore([
            ['pid', 0],
        ], true);
        $uploadToken = md5(time());
        CacheService::set('scan_upload', $uploadToken, 600);
        $url = sys_config('site_url') . '/app/upload?pid=' . $pid . '&token=' . $uploadToken;
        return app('json')->success(['url' => $url]);
    }

    /**
     * 删除二维码
     *
     * @return \think\Response
     * @author 等风来
     * @email  136327134@qq.com
     * @date   2023/6/26
     */
    public function removeUploadQrcode()
    {
        CacheService::delete('scan_upload');
        return app('json')->success();
    }

    /**
     * 获取扫码上传的图片数据
     *
     * @param $scan_token
     *
     * @return \think\Response
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/06/13
     */
    public function scanUploadImage($scan_token)
    {
        return app('json')->success($this->service->scanUploadImage($scan_token));
    }

    /**
     * 网络图片上传
     *
     * @return \think\Response
     * @throws \Exception
     * @author 吴汐
     * @email  442384644@qq.com
     * @date   2023/06/13
     */
    public function onlineUpload()
    {
        $data = $this->request->postMore([
            ['pid', 0],
            ['images', []],
        ]);
        $this->service->onlineUpload($data);
        return app('json')->success(100032);
    }
}
