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
