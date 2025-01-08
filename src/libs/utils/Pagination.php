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

namespace madong\ingenious\libs\utils;

use stdClass;

class Pagination
{
    private static int $limitMax = 1000;
    private static int $defaultLimit = 10;

    public static function setLimits(int $limitMax, int $defaultLimit): void
    {
        self::$limitMax = $limitMax;
        self::$defaultLimit = $defaultLimit;
    }

    public static function getPageValue(array|object $input, bool $isPage = true): array
    {
        $page = $limit = 0;

        if ($isPage) {
            $input = is_array($input) ? (object)$input : ($input instanceof stdClass || is_object($input) ? $input : (object)[]);
            $page  = $input->page ?? 0;
            $limit = $input->limit ?? 0;
        }

        if ($limit > self::$limitMax) {
            $limit = self::$limitMax;
        }

        return [(int)$page, (int)$limit, self::$defaultLimit];
    }
}
