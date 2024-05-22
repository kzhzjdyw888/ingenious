<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */

namespace ingenious\interface;

interface ConfigurationInterface
{

    /**
     * @param array $config
     */
    public function __construct(array $config = []);

    /**
     * 获取配置目录下的对应配置
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getConfig(string $key): mixed;

}
