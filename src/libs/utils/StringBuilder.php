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

class StringBuilder
{
    private string $string;

    public function __construct(string $initialString = '')
    {
        $this->string = $initialString; // 使用可选参数初始化字符串
    }

    public function append(string $value): static
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
        return array_filter(explode(',', rtrim($this->string, ','))); // 去除尾部逗号并过滤空值
    }

    public function trim(string $character = ','): static
    {
        $this->string = rtrim($this->string, $character); // 使用 rtrim 去除尾部字符
        return $this;
    }
}
