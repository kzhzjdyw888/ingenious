<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */

namespace ingenious\libs\utils;

use stdClass;

/**
 * 分页帮助类
 *
 * @author Mr.April
 * @since  1.0
 */
class PageParam
{

    /**
     * 获取分页参数
     *
     * @param array|object $input
     * @param bool         $isPage
     *
     * @return int[]
     */
    public static function getPageValue(array|object $input, bool $isPage = true): array
    {
        $page = $limit = 0;
        if ($isPage) {
            $input = is_array($input) ? (object)$input : ($input instanceof stdClass || is_object($input) ? $input : (object)[]);
            $page  = $input->page ?? 0;
            $limit = $input->limit ?? 0;
        }
        $limitMax     = 1000;
        $defaultLimit = 10;
        if ($limit > $limitMax) {
            $limit = $limitMax;
        }
        return [(int)$page, (int)$limit, (int)$defaultLimit];
    }
}
