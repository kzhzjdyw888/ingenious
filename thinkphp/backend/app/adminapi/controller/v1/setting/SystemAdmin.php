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
namespace app\adminapi\controller\v1\setting;

use app\adminapi\controller\AuthController;
use app\Request;
use app\services\system\admin\SystemAdminServices;
use phoenix\services\CacheService;
use think\App;

/**
 * Class SystemAdmin
 *
 * @package app\adminapi\controller\v1\setting
 */
class SystemAdmin extends AuthController
{
    /**
     * SystemAdmin constructor.
     *
     * @param App                 $app
     * @param SystemAdminServices $services
     */
    public function __construct(App $app, SystemAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    /**
     * 显示管理员资源列表
     *
     * @return \think\Response
     */
    public function index(): \think\Response
    {
        $where = $this->request->getMore([
            ['name', '', '', 'account_like'],
            ['delete_time', null],
            ['status', ''],
        ]);
        return app('json')->success($this->services->getAdminList($where));
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
     * @return \think\Response
     * @throws \ReflectionException
     */
    public function save(): \think\Response
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
        return app('json')->success(100000);
    }

    /**
     * 获取管理员详情
     *
     * @param string $id
     *
     * @return \think\Response
     */
    public function read(string $id): \think\Response
    {
        $ret = $this->services->find($id);
        if ($ret) {
            unset($ret['pwd']);
            return app('json')->success($ret);
        } else {
            return app('json')->fail($ret);
        }
    }

    /**
     * 修改管理员信息
     *
     * @param $id
     *
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException|\ReflectionException
     */
    public function update($id): \think\Response
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
            ['sex', 1],
            ['remark', ''],
        ]);
        if (!empty($data['pwd'])) {
            $data['conf_pwd'] = $data['pwd'];
        }
        $this->validate($data, \app\adminapi\validate\setting\SystemAdminValidata::class, 'update');
        if ($this->services->save($id, $data)) {
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }

    /**
     * 删除管理员
     *
     * @param $id
     *
     * @return \think\Response
     */
    public function delete($id): \think\Response
    {
        if (!$id) return app('json')->fail(100100);
        if ($this->services->update($id, ['delete_time' => time(), 'status' => 0]))
            return app('json')->success(100002);
        else
            return app('json')->fail(100008);
    }

    /**
     * 批量删除管理员
     *
     * @return \think\Response
     */
    public function batchUpdate(): \think\Response
    {
        $data = $this->request->post('data');
        if (empty($data)) {
            return app('json')->fail(100100);
        }
        if ($this->services->batchUpdate(explode(',', $data), ['delete_time' => time(), 'status' => 0], 'id')) {
            return app('json')->success(100002);
        } else {
            return app('json')->fail(100008);
        }
    }

    /**
     * 修改状态
     *
     * @param $id
     * @param $status
     *
     * @return \think\Response
     */
    public function set_status($id, $status): \think\Response
    {
        if ($this->services->update($id, ['status' => $status])) {
            return app('json')->success(100014);
        } else {
            return app('json')->fail(100007);
        }
    }

    public function setPosition($id): \think\Response
    {
        $data = $this->request->postMore([
            ['account'],
            ['dept_id'],
            ['dept_name'],
            ['post_id'],
            ['post_name'],
            ['real_name'],
        ]);
        $this->services->setPosition($id, $data);
        return app('json')->success(100014);
    }

    /**
     * 获取当前登陆管理员的信息
     *
     * @return \think\Response
     */
    public function info(): \think\Response
    {
        $result = $this->services->get($this->adminInfo['id'], ['*'])->toArray();
        unset($result['pwd']);
        return app('json')->success($result);
    }

    /**
     * 修改当前登陆admin信息
     *
     * @return \think\Response
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update_admin(): \think\Response
    {
        $data = $this->request->postMore([
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
                return app('json')->fail(400183);
            }
        }

        if ($this->services->updateAdmin($this->adminId, $data)) {
            $this->services->cacheDriver()->clear();
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }

    /**
     * 修改当前登陆admin的文件管理密码
     *
     * @return \think\Response
     */
    public function set_file_password(): \think\Response
    {
        $data = $this->request->postMore([
            ['file_pwd', ''],
            ['conf_file_pwd', ''],
        ]);
        if (!preg_match('/^(?![^a-zA-Z]+$)(?!\D+$).{6,}$/', $data['file_pwd'])) {
            return app('json')->fail(400183);
        }
        if ($this->services->setFilePassword($this->adminId, $data)) {
            $this->services->cacheDriver()->clear();
            return app('json')->success(100001);
        } else {
            return app('json')->fail(100007);
        }
    }

    /**
     * 退出登陆
     *
     * @return \think\Response
     */
    public function logout(): \think\Response
    {
        $key = trim(ltrim($this->request->header(Config::get('cookie.token_name')), 'Bearer'));
        CacheService::delete(md5($key));
        return app('json')->success();
    }


}
