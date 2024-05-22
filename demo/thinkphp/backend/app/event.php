<?php
// 事件定义文件
return [
    'bind' => [
    ],

    'listen' => [
        'AppInit'         => [],
        'HttpRun'         => [],
        'HttpEnd'         => [],
        'LogLevel'        => [],
        'LogWrite'        => [],
        'CrontabListener' => [\app\listener\crontab\SystemCrontabListener::class],//定时任务事件
    ],

    'subscribe' => [
    ],
];
