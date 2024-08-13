<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
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
