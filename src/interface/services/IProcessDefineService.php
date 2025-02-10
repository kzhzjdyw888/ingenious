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

use madong\ingenious\interface\model\IProcessDefine;
use madong\ingenious\model\ProcessModel;

interface IProcessDefineService
{
    public function created(object $param): ?IProcessDefine;

    public function updated(object $param): bool;

    public function del(string|int|array $data): array;

    public function list(object $param): array;

    public function findById(string $id): ?IProcessDefine;

    public function deploy($param, string $operation): bool;

    public function redeploy(string|int $processDefineId, object $inputStream, string|int $operation): bool;

    public function enable(string|int|array $data, string|int $operation): array;

    public function disable(string|int|array $data, string|int $operation): array;

    public function getById(string $processDefineId): ?IProcessDefine;

    public function updateState(string $processDefineId, string|int $state, string|int $operation): void;

    public function getProcessModel(string $processDefineId): ?ProcessModel;

    public function processDefineToModel(IProcessDefine $processDefine): ?ProcessModel;

    public function getDefineJsonStr(string $processDefineId): ?string;

    public function getDefineJsonObject(string $processDefineId): \stdClass|string|bool;

    public function getLastByName(string $name): ?IProcessDefine;

    public function getProcessDefineByVersion(string $name, int|float $version): ?IProcessDefine;
}

