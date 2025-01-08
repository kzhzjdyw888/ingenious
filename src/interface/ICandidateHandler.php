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


use madong\ingenious\model\TaskModel;

/**
 * 候选人处理接口
 *
 * @author Mr.April
 * @since  1.0
 */
interface ICandidateHandler
{

    /**
     * 根据任务模型参数获取候选人信息
     *
     * @param \madong\ingenious\model\TaskModel $model
     *
     * @return array
     */
    public function handle(TaskModel $model):array;

}
