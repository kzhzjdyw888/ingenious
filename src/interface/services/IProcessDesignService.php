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

use madong\ingenious\interface\model\IProcessDesign;

interface IProcessDesignService
{
    public function created(object $param): ?IProcessDesign;

    public function updated(object $param): bool;

    public function del(string|array|int $data): array;

    public function list(object $param): array;

    public function findById(string $id): ?IProcessDesign;

    public function updateDefine(object $jsonObject): bool;

    public function deploy(string|int $processDesignId, string|int $operation): bool;

    public function redeploy(string $processDesignId, string|int $operation): bool;
}
