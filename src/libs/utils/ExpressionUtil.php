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
