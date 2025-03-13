<?php

namespace app\common\extra;

use madong\ingenious\interface\IAuthenticatedUser;


/**
 * 当前系统用户服务类
 *
 * @author Mr.April
 * @since  1.0
 */
class AuthenticatedUser implements IAuthenticatedUser
{

    public function getUserId(): string|int
    {
        return getCurrentUser();
    }

    /**
     * @throws \Exception
     */
    public function getUserName(): string
    {
        $userInfo = getCurrentUser();
        return $userInfo['username'] ?? '';
    }

    /**
     * @throws \Exception
     */
    public function getRealName(): string
    {
        $userInfo = getCurrentUser();
        return $userInfo['nickname'] ?? '';
    }

}
