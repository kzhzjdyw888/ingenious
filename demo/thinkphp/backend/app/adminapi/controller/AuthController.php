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
namespace app\adminapi\controller;

use phoenix\basic\BaseController;
use think\Validate;
use think\exception\ValidateException;

/**
 * 基类 所有控制器继承的类
 * Class AuthController
 *
 * @package app\adminapi\controller
 */
class AuthController extends BaseController
{
    /**
     * 当前登陆管理员信息
     *
     * @var
     */
    protected $adminInfo;

    /**
     * 当前登陆管理员ID
     *
     * @var
     */
    protected string $adminId;

    /**
     * 当前管理员权限
     *
     * @var array
     */
    protected array $auth = [];

    /**
     * 初始化
     */
    protected function initialize()
    {
        $this->adminId   = $this->request->adminId();
        $this->adminInfo = $this->request->adminInfo();
        $this->auth      = $this->request->adminInfo['rules'] ?? [];
    }

    /**
     * 验证数据
     *
     * @param array               $data
     * @param array|string|object $validate
     * @param string|array        $message
     * @param bool                $batch
     *
     * @return bool
     */
    final protected function validate(array $data, array|string|object $validate, string|array $message = [], bool $batch = false): bool
    {

        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $class = str_contains($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
            if (is_string($message) && empty($scene)) {
                $v->scene($message);
            }
        }

        if (is_array($message)) {
            $v->message($message);
        }

        // 是否批量验证
        if ($batch) {
            $v->batch(true);
        }
        return $v->failException(true)->check($data);
    }
}
