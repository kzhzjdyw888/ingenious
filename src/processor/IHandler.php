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


namespace madong\ingenious\processor;



use madong\ingenious\interface\IExecution;

/**
 * 流程各模型操控处理接口
 *
 * @author Mr.April
 * @since  1.0
 */
interface IHandler
{

    /**
     * 子类需要实现的方法，来处理具体的操作
     * @param \madong\ingenious\interface\IExecution $execution
     */
    public function handle(IExecution $execution): void;
}

