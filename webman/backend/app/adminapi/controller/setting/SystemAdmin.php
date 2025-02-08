<?php

namespace app\adminapi\controller\setting;

use app\adminapi\controller\AuthController;
use app\common\CacheService;
use app\common\Json;
use app\services\system\admin\SystemAdminServices;
use support\Container;
use support\Request;

/**
 * Class SystemAdmin
 *
 * @package app\adminapi\controller\v1\setting
 */
class SystemAdmin extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = Container::make(SystemAdminServices::class);
    }

    /**
     * 显示管理员资源列表
     */
    public function index(Request $request): \support\Response
    {
        $where = $this->request->getMore([
            ['name', '', '', 'account_like'],
            ['delete_time', null],
            ['status', ''],
        ]);
        return Json::success($this->services->getAdminList($where));
    }

    /**
     * 显示创建表单页
     */
    public function create()
    {

    }

    /**
     * 保存管理员
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function save(Request $request): \support\Response
    {
        $data = $this->request->postMore([
            ['account', ''],
            ['conf_pwd', ''],
            ['pwd', ''],
            ['real_name', ''],
            ['status', 0],
            ['address', ''],
            ['cell_phone_number', ''],
            ['email', ''],
            ['sex', 0],
            ['remark', ''],
        ]);
        if (!empty($data['pwd'])) {
            $data['conf_pwd'] = $data['pwd'];
        }
        $this->validate($data, \app\adminapi\validate\setting\SystemAdminValidata::class);
//        $data['level'] = $this->adminInfo['level'] + 1;
        $this->services->create($data);
        return Json::success(100000);
    }

    /**
     * 获取管理员详情
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function read(Request $request): \support\Response
    {
        $id  = $request->input('id');
        $ret = $this->services->find($id);
        if ($ret) {
            unset($ret['pwd']);
            return Json::success($ret);
        } else {
            return Json::fail($ret);
        }
    }

    /**
     * 修改管理员信息
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function update(Request $request): \support\Response
    {
        $id   = $request->input('id');
        $data = $request->postMore([
            ['account', ''],
            ['conf_pwd', ''],
            ['pwd', ''],
            ['real_name', ''],
            ['status', 0],
            ['address', ''],
            ['cell_phone_number', ''],
            ['email', ''],
            ['sex', 1],
            ['remark', ''],
        ]);
        if (!empty($data['pwd'])) {
            $data['conf_pwd'] = $data['pwd'];
        }
        $this->validate($data, \app\adminapi\validate\setting\SystemAdminValidata::class, 'update');
        if ($this->services->save($id, $data)) {
            return Json::success(100001);
        } else {
            return Json::fail(100007);
        }
    }

    /**
     * 删除管理员
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function delete(Request $request): \support\Response
    {
        $id = $request->input('id');
        if (!$id) return Json::fail(100100);
        if ($this->services->update($id, ['delete_time' => time(), 'status' => 0]))
            return Json::success(100002);
        else
            return Json::fail(100008);
    }

    /**
     * 批量删除管理员
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function batchUpdate(Request $request): \support\Response
    {
        $data = $this->request->post('data');
        if (empty($data)) {
            return Json::fail(100100);
        }
        if ($this->services->batchUpdate(explode(',', $data), ['delete_time' => time(), 'status' => 0], 'id')) {
            return Json::success(100002);
        } else {
            return Json::fail(100008);
        }
    }

    /**
     * 修改状态
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function set_status(Request $request): \support\Response
    {
        $id     = $request->input('id');
        $status = $request->input('status');
        if ($this->services->update($id, ['status' => $status])) {
            return Json::success(100014);
        } else {
            return Json::fail(100007);
        }
    }

    public function setPosition(Request $request): \support\Response
    {
        $id   = $request->input('id');
        $data = $request->postMore([
            ['account'],
            ['dept_id'],
            ['dept_name'],
            ['post_id'],
            ['post_name'],
            ['real_name'],
        ]);
        $this->services->setPosition($id, $data);
        return Json::success(100014);
    }

    /**
     * 获取当前登陆管理员的信息
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function info(Request $request): \support\Response
    {
        $result = $this->services->get($this->adminInfo['id'], ['*'])->toArray();
        unset($result['pwd']);
        return Json::success($result);
    }

    /**
     * 修改当前登陆admin信息
     *
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update_admin(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['real_name', ''],
            ['head_pic', ''],
            ['address', ''],
            ['email', ''],
            ['pwd', ''],
            ['cell_phone_number', ''],
            ['remarks', ''],
            ['sex', '1'],
            ['new_pwd', ''],
            ['conf_pwd', ''],
        ]);

        if ($data['pwd']) {
            if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $data['new_pwd'])) {
                return Json::fail(400183);
            }
        }

        if ($this->services->updateAdmin($this->adminId, $data)) {
            $this->services->cacheDriver()->clear();
            return Json::success(100001);
        } else {
            return Json::fail(100007);
        }
    }

    /**
     * 修改当前登陆admin的文件管理密码
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function set_file_password(Request $request): \support\Response
    {
        $data = $request->postMore([
            ['file_pwd', ''],
            ['conf_file_pwd', ''],
        ]);
        if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $data['file_pwd'])) {
            return Json::fail(400183);
        }
        if ($this->services->setFilePassword($this->adminId, $data)) {
            $this->services->cacheDriver()->clear();
            return Json::success(100001);
        } else {
            return Json::fail(100007);
        }
    }

    /**
     * 退出登陆
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     */
    public function logout(Request $request): \support\Response
    {
        $key = trim(ltrim($request->header(Config('cookie.token_name')), 'Bearer'));
        CacheService::delete(md5($key));
        return Json::success();
    }

}
