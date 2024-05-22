<?php

return [
    'log_path' => str_replace('\\', '/', dirname(__DIR__)) . '/runtime/ingenious/log/',//日志路径tp可以直接助手函数
    'is_debug' => true,//是否调试模式
    'redis'    => [
        'host'       => '127.0.0.1',
        'port'       => '6379',
        'password'   => '',
        'expire'     => 0,
        'prefix'     => 'l:',
        'tag_prefix' => 'ingenious:',
        'select'     => 0,
        'timeout'    => 0,
    ],
];
