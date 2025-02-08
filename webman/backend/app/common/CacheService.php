<?php

namespace app\common;

use support\Cache;
use support\Redis;

/**
 * 缓存类
 *
 * @author Mr.April
 * @since  1.0
 */
class CacheService
{

    protected static array $redisQueueKey = [
        0 => 'product',
        1 => 'seckill',
        2 => 'bargain',
        3 => 'combination',
        6 => 'advance',
    ];

    protected static int $expire = 3600;

    protected static function getExpire(int $expire = null): int
    {
        if ($expire == null) {
            if (self::$expire) {
                return (int)self::$expire;
            }
            $default = Config('cache.default');
            $expire  = Config('cache.stores.' . $default . '.expire');
            if (!is_int($expire)) {
                $expire = (int)$expire;
            }
        }
        return self::$expire = $expire;
    }

    /**
     * 写入缓存
     *
     * @param string   $name
     * @param          $value
     * @param int|null $expire
     * @param string   $tag
     *
     * @return bool
     */
    public static function set(string $name, $value, int $expire = null, string $tag = 'ingenious'): bool
    {
        // 在键名中添加标签信息
//        $key = $tag . '_' . $name;
        try {
            return Cache::set($name, $value, $expire ?: self::getExpire($expire));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 如果不存在则写入缓存
     *
     * @param string                 $name
     * @param \app\common\mixed|null $default
     * @param int|null               $expire
     * @param string                 $tag
     *
     * @return mixed|string
     */
    public static function remember(string $name, mixed $default = null, int $expire = null, string $tag = 'ingenious')
    {
//        $key         = $tag . '_' . $name;
        $cachedValue = Cache::get($name);
        if ($cachedValue !== null) {
            return $cachedValue;
        }
        if (is_callable($default)) {
            $value = $default();
            Cache::set($name, $value, $expire ?: self::getExpire($expire));
            return $value;
        }
        Cache::set($name, $default, $expire ?: self::getExpire($expire));
        return $default;
    }

    /**
     * 读取缓存
     *
     * @param string                 $name
     * @param \app\common\mixed|null $default
     * @param string                 $tag
     *
     * @return mixed
     */
    public static function get(string $name, mixed $default = null, string $tag = 'ingenious'): mixed
    {
//        $key = $tag . '_' . $name;
        return Cache::get($name) ?? $default;
    }

    /**
     * 删除缓存
     *
     * @param string $name
     *
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
    public static function clear(string $tag = 'ingenious'): bool
    {
        $keys = Cache::keys($tag . '_*');
        foreach ($keys as $key) {
            Cache::delete($key);
        }
        return true;
    }

    /**
     * 检查缓存是否存在
     *
     * @param string $key
     * @param string $tag
     *
     * @return bool
     */
    public static function has(string $key, string $tag = "ingenious"): bool
    {
        try {
//            $keys = Cache::keys($tag . '_*');
            return Cache::has($key);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 检查锁
     *
     * @param string $key
     * @param int    $timeout
     *
     * @return bool
     */
    public static function setMutex(string $key, int $timeout = 10): bool
    {
        $curTime      = time();
        $readMutexKey = "redis:mutex:{$key}";
        $redis        = Redis::connection();

        $mutexRes = $redis->setnx($readMutexKey, $curTime + $timeout);
        if ($mutexRes) {
            return true;
        }
        // 检查是否发生死锁，即当前时间大于锁定时间
        $time = $redis->get($readMutexKey);
        if ($curTime > $time) {
            $redis->del($readMutexKey);
            return $redis->setnx($readMutexKey, $curTime + $timeout);
        }
        return false;
    }

    /**
     * 删除锁
     *
     * @param string $key
     */
    public static function delMutex(string $key): void
    {
        $readMutexKey = "redis:mutex:{$key}";
        Cache::delete($readMutexKey);
    }

    /**
     * Redis句柄
     *
     * @param $type
     *
     * @return mixed
     */
    public static function redisHandler($type = null)
    {
        if ($type) {
            return Cache::store('redis')->tag($type);
        } else {
            return Cache::store('redis');
        }
    }

    /**
     * 数据库锁
     *
     * @param     $key
     * @param     $fn
     * @param int $ex
     *
     * @return mixed|string
     */
    public static function lock($key, $fn, int $ex = 6)
    {
        $redis     = Redis::connection();
        $lockKey   = "lock:{$key}";
        $lockValue = uniqid();

        // 尝试获取锁
        $isLocked = $redis->set($lockKey, $lockValue, 'EX', $ex, 'NX');

        if ($isLocked) {
            try {
                // 执行回调函数
                return $fn();
            } finally {
                // 释放锁
                if ($redis->get($lockKey) === $lockValue) {
                    $redis->del($lockKey);
                }
            }
        } else {
            // 未获取到锁，可以选择等待重试或返回错误信息
            return 'Failed to acquire lock';
        }
    }

}
