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


use madong\ingenious\interface\model\IProcessSurrogate;


interface IProcessSurrogateService
{
    public function created(object $param): ?IProcessSurrogate;

    public function updated(object $param): bool;

    public function del(string|array|int $data): array;

    public function list(object $param): array;

    public function findById(string|int $id): ?IProcessSurrogate;

    public function getSurrogate(string|int $operator, string|int $processName): ?string;
}
