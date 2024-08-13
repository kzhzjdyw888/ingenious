<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
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
