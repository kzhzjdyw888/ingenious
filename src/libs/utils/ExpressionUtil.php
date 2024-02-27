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
