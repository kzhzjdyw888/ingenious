<?php
/**
 *+------------------
 * ingenious
 *+------------------
 * Copyright (c) https://gitcode.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: https://madong.tech
 */

namespace madong\ingenious\libs\utils;

use madong\ingenious\core\ServiceContext;
use madong\ingenious\interface\IConfiguration;
use Redis;

class RedisCache
{
    private static Redis $redis;
    private static string $prefix;
    private static string|null|int $defaultExpiration;
    private static string $host;
    private static string $port;
    private static string|null $password = null;
    private static string $timeout;

    private static array $config = [
        'host'       => '127.0.0.1',
        'port'       => '6379',
        'password'   => '',
        'expire'     => 0,
        'prefix'     => 'l:',
        'tag_prefix' => 'wf:',
        'database'   => 0,
        'timeout'    => 0,
    ];

    private static function connect(): Redis
    {

        $config = ServiceContext::getConfig('redis');
        self::$prefix            = $config['prefix'] ?? '';
        self::$defaultExpiration = $config['defaultExpiration'] ?? 0;
        self::$host              = $config['host'];
        self::$port              = $config['port'];
        self::$timeout           = $config['timeout'] ?? 0;
        self::$password          = $config['password'] ?? null;
        $redis                   = new Redis();
        $redis->connect(self::$host, self::$port, self::$timeout);
        if (!empty(self::$password)) {
            $redis->auth(self::$password);
        }
        return $redis;
    }

    private static function disconnect(): void
    {
        self::$redis->close();
    }

    private static function serializeData($data): string
    {
        return serialize($data);
    }

    private static function unserializeData($data)
    {
        return unserialize($data);
    }

    public static function set($key, $value, $expiration = 0): void
    {
        self::$redis = self::connect();
        $key         = self::$prefix . $key;
        $value       = self::serializeData($value); // 序列化数据
        self::$redis->set($key, $value);
        if ($expiration > 0) {
            self::$redis->expire($key, $expiration);
        } elseif ($expiration == 0) {
            self::$redis->persist($key);
        }
        self::disconnect();
    }

    public static function get($key): mixed
    {
        self::$redis = self::connect();
        $key         = self::$prefix . $key;
        $value       = self::$redis->get($key);
        $value       = self::unserializeData($value); // 反序列化数据
        self::disconnect();
        return $value;
    }

    public static function delete($key): bool|int|Redis
    {
        self::$redis = self::connect();
        $key         = self::$prefix . $key;
        $result      = self::$redis->del($key);
        self::disconnect();
        return $result;
    }

    public static function flush(): bool|Redis
    {
        self::$redis = self::connect();
        $result      = self::$redis->flushDB();
        self::disconnect();
        return $result;
    }

    public static function getCached($key, $default = null, $expiration = 0, callable $callback = null)
    {
        $value = self::get($key);
        if ($value === false) {
            if ($callback !== null && is_callable($callback)) {
                $value = call_user_func($callback);
                self::set($key, $value, $expiration);
            } else {
                $value = $default;
                self::set($key, $value, $expiration);
            }
        }
        return $value;
    }
}

