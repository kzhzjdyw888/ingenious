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

namespace ingenious\scheduling;

use ingenious\libs\utils\Dict;

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
     * 添加作业到调度器
     *
     * @param String                 $jobId
     * @param \ingenious\libs\utils\Dict $args
     */
    public function addJob(string $jobId, Dict $args): void;

    /**
     * 从调度器中删除作业
     *
     * @param string $jobId
     */
    public function removeJob(string $jobId);

}
