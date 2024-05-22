<?php

namespace app\adminapi\validate\setting;

use think\Validate;

class SystemUserValidata extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'account'      => ['require', 'alphaDash'],
        'password'     => 'require',
        'captcha'      => 'require',
        'cid'          => 'require',
        'captcha_type' => ['require', 'array'],
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名'    =>    '错误信息'
     *
     * @var array
     */
    protected $message = [
        'account.require'   => '400033',
        'account.alphaDash' => '400034',
        'conf_pwd.require'  => '400263',
        'password.require'  => '400256',
        'user_name.require' => '400035',
    ];

    protected $scene = [
        'get'    => ['user_id'],
        'update' => ['account','user_id'],
    ];

}
