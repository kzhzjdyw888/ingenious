<?php

namespace app\adminapi\controller;

use app\common\Json;
use app\services\system\admin\SystemAdminServices;
use support\Request;

class LoginController extends AuthController
{

    public function __construct()
    {
        parent::__construct();
        $this->services = new SystemAdminServices();
    }

    protected function initialize()
    {
    }

    public function login(Request $request): \support\Response
    {
        [$account, $password, $key, $captchaVerification, $captchaType] = $request->postMore([
            'account',
            'pwd',
            ['key', ''],
            ['captchaVerification', ''],
            ['captchaType', ''],
        ], true);

        if ($captchaVerification != '') {
            try {
                aj_captcha_check_two($captchaType, $captchaVerification);
            } catch (\Exception $e) {
                return Json::fail($e->getMessage());
            }
        }
        if (strlen(trim($password)) < 6 || strlen(trim($password)) > 32) {
            return Json::fail('账号密码必须是在6到32位之间');
        }
        //导入验证器
        $validate = new \app\adminapi\validate\setting\SystemAdminValidata();
        if (!$validate->scene('get')->check(['account' => $account, 'pwd' => $password])) {
            return Json::fail($validate->getError());
        }
        $result = $this->services->login($account, $password, 'admin', $key);
        if (!$result) {
            return Json::fail('账号或密码错误', ['login_captcha' => 1]);
        }
        return Json::success($result);
    }

    public function demoUser(Request $request): \support\Response
    {
        $where  = $this->request->getMore([
            ['name', '', '', 'account_like'],
            ['delete_time', null],
            ['status', ''],
            ['not_account', 'admin'],
        ]);
        $result = $this->services->getAdministratorsList($where);
        //对密码进行销毁
        foreach ($result['list'] as $key => $value) {
            unset($result['list'][$key]['pwd']);
        }
        return Json::success($result);

    }

    /**
     * 角色扮演
     *
     * @param \support\Request $request
     *
     * @return \support\Response
     * @throws \ReflectionException
     * @throws \app\exception\AdminException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function actUser(Request $request): \support\Response
    {
        $account = $request->input('account');
        if ($account === 'admin') {
            return Json::fail('无法扮演超级管理员', ['login_captcha' => 1]);
        }
        if (empty($account)) {
            return Json::fail('传入用户不存在', ['login_captcha' => 1]);
        }

        $result = $this->services->login($account, md5('123456'), 'admin', '', false);
        if (!$result) {
            return Json::fail('账号或密码错误', ['login_captcha' => 1]);
        }
        return Json::success($result);
    }

}
