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

namespace app\services\system\admin;

use app\common\CacheService;
use app\common\JwtAuth;
use app\dao\system\admin\AdminAuthDao;
use app\exception\AuthException;
use app\services\BaseServices;
use support\Container;

/**
 * admin授权service
 * Class AdminAuthServices
 *
 * @package app\services\system\admin
 */
class SystemAuthServices extends BaseServices
{

    public function __construct()
    {
        $this->dao = Container::make(AdminAuthDao::class);
    }

    /**
     * 获取Admin授权信息
     *
     * @param string $token
     * @param int    $code
     *
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \app\exception\AuthException
     */
    public function parseToken(string $token, int $code = 110003): array
    {
        /** @var CacheService $cacheService */
        $cacheService = Container::make(CacheService::class);
        if (!$token || $token === 'undefined') {
            throw new AuthException($code);
        }
        /** @var JwtAuth $jwtAuth */
        $jwtAuth = Container::make(JwtAuth::class);
        //设置解析token
        [$id, $type, $pwd, $exp] = $jwtAuth->parseToken($token);

        //检测token是否过期
        $md5Token = md5($token);
        if (!$cacheService->has($md5Token) || !$cacheService->get($md5Token, '', '', $type)) {
            $this->authFailAfter($id, $type);
            throw new AuthException($code);
        }
        //验证token
        try {
            $jwtAuth->verifyToken();
        } catch (\Throwable $e) {
            if (!request()->isCli()) {
                $cacheService->delete($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new AuthException($code);
        }

        //获取管理员信息
        $validTime = $this->timestamp_diff(time(), $exp);
//        $adminInfo = $this->cacheDriver()->remember($md5Token, $this->infoAdmin($id), $validTime + 60);
        $adminInfo = $this->infoAdmin($id);
        if (!$adminInfo || !$adminInfo->id) {
            if (!request()->isCli()) {
                $cacheService->delete($md5Token);
            }
            $this->authFailAfter($id, $type);
            throw new AuthException($code);
        }
        $adminInfo->type = $type;
        return $adminInfo->hidden(['pwd', 'delete_time', 'status'])->toArray();
    }

    /**
     * token验证失败后事件
     */
    protected function authFailAfter($id, $type)
    {
        try {
            //token 验证失败后处理事件
        } catch (\Throwable $e) {
        }
    }

    /**
     * 保存提交数据
     *
     * @param $adminId
     * @param $postData
     */
    protected function saveProduct($adminId, $postData)
    {
        /** @var CacheServices $cacheService */
        $cacheService = Container::make(CacheServices::class);
        $cacheService->setDbCache($adminId . '_product_data', $postData, 68400);
    }

    /**
     * 获取管理员信息
     *
     * @param string|int $id
     *
     * @return array|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function infoAdmin(string|int $id): array|\think\Model
    {
        $adminInfo = $this->get($id);
        if (!$adminInfo) {
            throw new AuthException(400595);
        }
        if (!$adminInfo->status) {
            throw new AuthException(400595);
        }
        /** @var SystemAdminDeptServices $systemAdminDeptServices */
        $systemAdminDeptServices = Container::make(SystemAdminDeptServices::class);
        $adminInfo->dept_id      = $systemAdminDeptServices->column(['admin_id' => $adminInfo->id], 'dept_id')[0] ?? '';

        /** @var SystemAdminPostServices $systemAdminPostServices */
        $systemAdminPostServices = Container::make(SystemAdminPostServices::class);
        $adminInfo->post_id      = $systemAdminPostServices->column(['admin_id' => $adminInfo->id], 'post_id')[0] ?? '';

        if (!empty($adminInfo->dept_id)) {
            /** @var SystemRoleDeptServices $systemRoleDeptServices */
            $systemRoleDeptServices = Container::make(SystemRoleDeptServices::class);
            $deptRoleId             = $systemRoleDeptServices->column(['dept_id' => $adminInfo->dept_id], 'role_id');
        }

        if (!empty($adminInfo->post_id)) {
            /** @var SystemRolePostServices $systemRolePostServices */
            $systemRolePostServices = Container::make(SystemRolePostServices::class);
            $postRoleId             = $systemRolePostServices->column(['post_id' => $adminInfo->post_id], 'role_id');
        }
        $adminInfo->role_id = array_merge_recursive($deptRoleId ?? [], $postRoleId ?? []);

        /** @var SystemRoleMenuServices $systemRoleMenuServices */
        $systemRoleMenuServices = Container::make(SystemRoleMenuServices::class);
        $adminInfo->roles       = $systemRoleMenuServices->column(['role_id' => $adminInfo->role_id], 'menu_id');
        return $adminInfo;
    }

    /**
     * 有效时间
     *
     * @param int $timestamp1
     * @param int $timestamp2
     *
     * @return int
     */
    private function timestamp_diff(int $timestamp1, int $timestamp2): int
    {
        $diff = $timestamp2 - $timestamp1;
        return max(0, $diff);
    }
}
