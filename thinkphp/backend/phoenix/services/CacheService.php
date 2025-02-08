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

namespace phoenix\services;

use think\cache\Driver;
use think\cache\TagSet;
use think\facade\Cache;
use think\facade\Config;

/**
 * phoenix 缓存类
 * Class CacheService
 * @package crmeb\services
 */
class CacheService
{
    /**
     * 缓存队列key
     * @var string[]
     */
    protected static array $redisQueueKey = [
        0 => 'product',
        1 => 'seckill',
        2 => 'bargain',
        3 => 'combination',
        6 => 'advance'
    ];

    /**
     * 过期时间
     * @var int
     */
    protected static int $expire;

    /**
     * 获取缓存过期时间
     * @param int|null $expire
     * @return int
     */
    protected static function getExpire(int $expire = null): int
    {
        if ($expire == null) {
            if (self::$expire) {
                return (int)self::$expire;
            }
            $default = Config::get('cache.default');
            $expire = Config::get('cache.stores.' . $default . '.expire');
            if (!is_int($expire)) {
                $expire = (int)$expire;
            }
        }
        return self::$expire = $expire;
    }

    /**
     * 写入缓存
     * @param string $name 缓存名称
     * @param mixed $value 缓存值
     * @param int|null $expire 缓存时间，为0读取系统缓存时间
     */
    public static function set(string $name, $value, int $expire = null, string $tag = 'phoenix')
    {
        try {
            return Cache::tag($tag)->set($name, $value, $expire ?? self::getExpire($expire));
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 如果不存在则写入缓存
     * @param string $name
     * @param mixed $default
     * @param int|null $expire
     * @param string $tag
     * @return mixed|string|null
     */
    public static function remember(string $name, $default = '', int $expire = null, string $tag = 'phoenix')
    {
        try {
            return Cache::tag($tag)->remember($name, $default, $expire ?? self::getExpire($expire));
        } catch (\Throwable $e) {
            try {
                if (is_callable($default)) {
                    return $default();
                } else {
                    return $default;
                }
            } catch (\Throwable $e) {
                return null;
            }
        }
    }

    /**
     * 读取缓存
     *
     * @param string       $name
     * @param mixed|string $default
     *
     * @return mixed|string
     */
    public static function get(string $name, mixed $default = ''): mixed
    {
        return Cache::get($name) ?? $default;
    }

    /**
     * 删除缓存
     * @param string $name
     * @return bool
     */
    public static function delete(string $name): bool
    {
        return Cache::delete($name);
    }

    /**
     * 清空缓存池
     *
     * @param string $tag
     *
     * @return bool
     */
    public static function clear(string $tag = 'phoenix'): bool
    {
        return Cache::tag($tag)->clear();
    }

    /**
     * 检查缓存是否存在
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        try {
            return Cache::has($key);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * 检查锁
     * @param string $key
     * @param int $timeout
     * @return bool
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2022/11/22
     */
    public static function setMutex(string $key, int $timeout = 10): bool
    {
        $curTime = time();
        $readMutexKey = "redis:mutex:{$key}";
        $mutexRes = Cache::store('redis')->handler()->setnx($readMutexKey, $curTime + $timeout);
        if ($mutexRes) {
            return true;
        }
        //就算意外退出，下次进来也会检查key，防止死锁
        $time = Cache::store('redis')->handler()->get($readMutexKey);
        if ($curTime > $time) {
            Cache::store('redis')->handler()->del($readMutexKey);
            return Cache::store('redis')->handler()->setnx($readMutexKey, $curTime + $timeout);
        }
        return false;
    }

    /**
     * 删除锁
     * @param string $key
     * @author 等风来
     * @email 136327134@qq.com
     * @date 2022/11/22
     */
    public static function delMutex(string $key): void
    {
        $readMutexKey = "redis:mutex:{$key}";
        Cache::store('redis')->handler()->del($readMutexKey);
    }

    /**
     * Redis缓存句柄
     *
     * @param null $type
     *
     * @return \TagSet|\Driver
     * @author 吴汐
     * @email 442384644@qq.com
     * @date 2023/02/10
     */
    public static function redisHandler($type = null): \TagSet|\Driver
    {
        if ($type) {
            return Cache::store('redis')->tag($type);
        } else {
            return Cache::store('redis');
        }
    }

    /**
     * 数据库锁
     * @param $key
     * @param $fn
     * @param int $ex
     * @return mixed
     * @author 吴汐
     * @email 442384644@qq.com
     * @date 2023/03/01
     */
    public static function lock($key, $fn, int $ex = 6): mixed
    {
        if (Config::get('cache.default') == 'file') {
            return $fn();
        }
        return app()->make(LockService::class)->exec($key, $fn, $ex);
    }
}
