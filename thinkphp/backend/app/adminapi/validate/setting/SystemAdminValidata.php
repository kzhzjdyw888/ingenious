<?php

namespace app\adminapi\validate\setting;

use think\Validate;

class SystemAdminValidata extends Validate
{

    /**
     * 定义验证规则
     * 格式：'字段名'    =>    ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'account'   => ['require', 'alphaDash'],
        'conf_pwd'  => 'require',
        'pwd'       => 'require',
        'real_name' => 'require',
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
        'pwd.require'       => '400256',
        'real_name.require' => '400035',
    ];

    protected $scene = [
        'get'    => ['account', 'pwd'],
        'update' => ['account'],
    ];

}
