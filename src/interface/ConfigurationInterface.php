<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
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
    public static function getConfig(string $key): mixed;

    /**
     * 日志路径
     *
     * @return string
     */
    public function logPath(): string;

}
