<?php
declare (strict_types=1);

namespace app\adminapi\controller;

use app\adminapi\controller\v1\wf\api\LoginUserHolder;
use app\Request;
use app\services\system\admin\SystemAdminServices;
use phoenix\services\workerman\ChannelService;
use phoenix\utils\Captcha;
use think\App;

class Login extends AuthController
{

    public function __construct(App $app, SystemAdminServices $services)
    {
        parent::__construct($app);
        $this->services = $services;
    }

    protected function initialize()
    {
    }

    /**
     * 验证码
     *
     * @return $this|\think\Response
     * @throws \Exception
     */
    public function captcha(): \think\Response|static
    {
        return app()->make(Captcha::class)->create();
    }

    /**
     * @return mixed
     */
    public function ajcaptcha()
    {
        $captchaType = $this->request->get('captcha_type');
        return app('json')->success(aj_captcha_create($captchaType));
    }

    /**
     * 一次验证
     *
     * @return mixed
     */
    public function ajcheck(): mixed
    {
        [$token, $pointJson, $captchaType] = $this->request->postMore([
            ['token', ''],
            ['pointJson', ''],
            ['captchaType', ''],
        ], true);
        try {
            aj_captcha_check_one($captchaType, $token, $pointJson);
            return app('json')->success();
        } catch (\Throwable $e) {
            return app('json')->fail('验证码错误');
        }
    }

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \ReflectionException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\Exception
     */
    public function login(Request $request)
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
            } catch (\Throwable $e) {
                return app('json')->fail(400336);
            }
        }
        if (strlen(trim($password)) < 6 || strlen(trim($password)) > 32) {
            return app('json')->fail(400762);
        }

        $this->validate(['account' => $account, 'pwd' => $password], \app\adminapi\validate\setting\SystemAdminValidata::class, 'get');

        $result = $this->services->login($account, $password, 'admin', $key);
        if (!$result) {
            return app('json')->fail(400140, ['login_captcha' => 1]);
        }
        return app('json')->success($result);
    }

    public function demoUser(Request $request)
    {
        $where  = $this->request->getMore([
            ['name', '', '', 'account_like'],
            ['delete_time', null],
            ['status', ''],
            //            ['not_account', 'admin'],
        ]);
        $result = $this->services->getAdministratorsList($where);
        //对密码进行销毁
        foreach ($result['list'] as $key => $value) {
            unset($result['list'][$key]['pwd']);
        }
        return app('json')->success($result);

    }

    public function actUser(Request $request)
    {
        $account = $request->param('account');
        if ($account === 'admin') {
//            return app('json')->fail('无法扮演超级管理员', ['login_captcha' => 1]);
        }
        if (empty($account)) {
            return app('json')->fail('传入用户不存在', ['login_captcha' => 1]);
        }

        $result = $this->services->login($account, md5('123456'), 'admin', '', false);
        if (!$result) {
            return app('json')->fail(400140, ['login_captcha' => 1]);
        }
        return app('json')->success($result);
    }

    //文档密钥验证
    public function verificationKey(Request $request){
        $password="0bc3330a-5d10-4697-8ce4-c1afdeceadaf";
        $pwd= $request->param('password');
        if($password!==$pwd){
            return app('json')->fail('无效密钥');
        }
        return app('json')->success('验证成功');
    }
}
