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

namespace madong\ingenious\interface;

interface IConfiguration
{
    /**
     * @param array $config
     */
    public function __construct(array $config = []);

    /**
     * 获取配置目录下的对应配置
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getConfig(string $key, mixed $default): mixed;
}
