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


use madong\ingenious\interface\model\IProcessTaskHistory;

interface IProcessTaskHistoryService
{
    public function created(object $param): ?IProcessTaskHistory;

    public function updated(object $param): bool;

    public function findById(string|int $id): ?IProcessTaskHistory;

    public function list(object $param): array;
}
