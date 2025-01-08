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

use madong\ingenious\interface\model\IProcessType;

/**
 * 流程类型服务类
 *
 * @author Mr.April
 * @since  1.0
 */
interface IProcessTypeService
{
    public function created(object $param): ?IProcessType;

    public function del(string|array|int $data): array;

    public function updated(object $param): bool;

    public function list(object $param): array;

    public function listByType(object $param): array;

    public function findById(string|int $id): ?IProcessType;
}

