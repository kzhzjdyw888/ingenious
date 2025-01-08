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


use madong\ingenious\interface\model\IProcessDefineFavorite;

/**
 * 流程定义收藏服务
 *
 * @author Mr.April
 * @since  1.0
 */
interface IProcessDefineFavoriteService
{
    public function created($param): ?IProcessDefineFavorite;

    public function del(string|array|int $data): array;

    public function list($param): array;

    public function findById(string $id): ?IProcessDefineFavorite;
}

