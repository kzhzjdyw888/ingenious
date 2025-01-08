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

namespace madong\ingenious\interface\services;

use madong\ingenious\interface\model\IProcessTaskActor;

/**
 * 流程任务和参与人服务类
 *
 * @author Mr.April
 * @since  1.0
 */
interface IProcessTaskActorService
{

    public function created(object $param): ?IProcessTaskActor;

    public function updated(object $param): bool;

    public function findById(string $id): ?IProcessTaskActor;

    public function list(object $param): array;

}
