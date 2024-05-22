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

use ingenious\model\TaskModel;

/**
 * 候选人处理接口
 * @author Mr.April
 * @since  1.0
 */
interface CandidateHandler
{

    /**
     * 根据任务模型参数获取候选人信息
     *
     * @param \ingenious\model\TaskModel $model
     *
     * @return array
     */
    public function handle(TaskModel $model):array;

}
