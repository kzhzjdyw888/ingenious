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

use ingenious\interface\Expression;
use RulerZ\Compiler\Compiler;
use RulerZ\RulerZ;
use RulerZ\Target\Native\Native;

class ExpressionUtil implements Expression
{

    public static function eval(string $expr, object|array $args): bool
    {
        try {
            // 创建一个RulerZ编译器
            $compiler = Compiler::create();

            // 创建RulerZ实例
            $rulerZ = new RulerZ($compiler, [new Native(['length' => 'strlen'])]);

            // 应用规则到对象
            $result = $rulerZ->satisfies($args, $expr);
            return $result ?? false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
