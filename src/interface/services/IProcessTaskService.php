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


use madong\ingenious\interface\model\IProcessTask;


interface IProcessTaskService
{

    public function created(object $param): ?IProcessTask;

    public function updated(object $param): bool;

    public function findById(string|int $id): ?IProcessTask;

    public function list(object $param): array;

}
