<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace ingenious\libs\utils;

class StringBuilder
{
    private string $string;

    public function __construct()
    {
        $this->string = ''; // 使用空格初始化字符串
    }

    public function append($value): static
    {
        $this->string .= $value;
        return $this; // 允许链式调用
    }

    public function toString(): string
    {
        return $this->string;
    }

    public function toArray(): array
    {
        $length = strlen($this->string);
        if ($length > 0 && $this->string[$length - 1] === ',') {
            return explode(',', substr($this->string, 0, $length - 1));
        }
        return [];

    }

    function removeTrailingComma(): static
    {
        $length = strlen($this->string);
        if ($length > 0 && $this->string[$length - 1] === ',') {
            $this->string = substr($this->string, 0, $length - 1);
        }
        return $this;
    }
}
