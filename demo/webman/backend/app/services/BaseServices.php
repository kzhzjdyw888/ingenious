<?php

namespace app\services;

use app\common\CacheService;
use app\common\JwtAuth;
use support\Container;
use think\facade\Db;

abstract class BaseServices
{

    /**
     * 模型注入
     *
     * @var object
     */
    protected object $dao;

    /**
     * 缓存管理
     *
     * @return \app\common\CacheService
     */
    public function cacheDriver(): CacheService
    {
        return new CacheService($this->dao->getTableName());
    }

    /**
     * 获取分页配置
     *
     * @param bool $isPage
     * @param bool $isRelieve
     *
     * @return int[]
     */
    public function getPageValue(bool $isPage = true, bool $isRelieve = true): array
    {
        // 获取请求实例
        $request = request();
        $page    = $limit = 0;
        if ($isPage) {
            $page  = $request->input(Config('thinkorm.page.pageKey', 'page') . '/d', 0);
            $limit = $request->input(Config('thinkorm.page.limitKey', 'limit') . '/d', 0);
        }
        $limitMax     = Config('thinkorm.page.limitMax');
        $defaultLimit = Config('thinkorm.page.defaultLimit', 10);
        if ($limit > $limitMax && $isRelieve) {
            $limit = $limitMax;
        }
        return [(int)$page, (int)$limit, (int)$defaultLimit, (int)$limitMax];
    }

    /**
     * 数据库事务操作
     *
     * @param callable $closure
     * @param bool     $isTran
     *
     * @return mixed
     */
    public function transaction(callable $closure, bool $isTran = true): mixed
    {
        return $isTran ? DB::transaction($closure) : $closure();
    }

    /**
     * 创建token
     *
     * @param int|string $id
     * @param string     $type
     * @param string     $pwd
     *
     * @return array
     * @throws \app\exception\AdminException
     */
    public function createToken(int|string $id, string $type, string $pwd = ''): array
    {
        /** @var JwtAuth $jwtAuth */
        $jwtAuth = Container::make(JwtAuth::class);
        return $jwtAuth->createToken($id, $type, ['pwd' => md5($pwd)]);
    }

    /**
     * 获取路由地址
     *
     * @param string $path
     * @param array  $params
     * @param bool   $suffix
     * @param bool   $isDomain
     *
     * @return \think\route\Url
     */
//    public function url(string $path, array $params = [], bool $suffix = false, bool $isDomain = false)
//    {
//        return Url::buildUrl($path, $params)->suffix($suffix)->domain($isDomain)->build();
//    }

    /**
     * 密码hash加密
     *
     * @param string $password
     *
     * @return false|string|null
     */
    public function passwordHash(string $password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->dao, $name], $arguments);
    }
}