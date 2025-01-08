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

namespace madong\ingenious\scheduling;



use madong\ingenious\interface\IDict;

/**
 * 调度器接口，与具体的定时调度框架无关
 *
 * @author Mr.April
 * @since  1.0
 */
interface IScheduler
{
    const SOURCE_ID_KEY = "source_id";
    const SOURCE_TYPE_KEY = "source_type";

    /**
     * 添加作用调度器
     * @param string                                   $jobId
     * @param \madong\ingenious\interface\IDict $args
     */
    public function addJob(string $jobId, IDict $args): void;

    /**
     * 从调度器中删除作业
     *
     * @param string $jobId
     */
    public function removeJob(string $jobId);

}
