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
