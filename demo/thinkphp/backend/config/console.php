<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'workerman' => \phoenix\command\Workerman::class,
        'timer'     => \phoenix\command\Timer::class,
        'util'      => \phoenix\command\Util::class,
        'npm'       => \phoenix\command\Npm::class
    ],
];
