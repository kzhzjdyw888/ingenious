<?php

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------
use think\facade\Env;

return [
    // 默认日志记录通道
    'default'      => Env::get('log.channel', 'file'),
    // 日志记录级别
    'level'        => ['error', 'warning', 'fail', 'success', 'info', 'notice', 'crontab', 'phoenix'],
    // 日志类型记录的通道 ['error'=>'email',...]
    'type_channel' => [],

    //是否开启业务成功日志
    'success_log'  => false,
    //是否开启业务失败日志
    'fail_log'     => false,
    //是否开启定时任务日志
    'timer_log'    => false,
    // 关闭全局日志写入
    'close'        => false,
    // 全局日志处理 支持闭包
    'processor'    => null,

    // 日志通道列表
    'channels'     => [
        'file' => [
            // 日志记录方式
            'type'           => 'File',
            // 日志保存目录
            'path'           => app()->getRuntimePath() . 'log' . DIRECTORY_SEPARATOR,
            // 单文件日志写入
            'single'         => false,
            // 独立日志级别
            'apart_level'    => ['error', 'fail', 'success', 'crontab', 'phoenix'],
            // 最大日志文件数量
            'max_files'      => 60,
            'time_format'    => 'Y-m-d H:i:s',
            'format'         => '%s|%s|%s',
            // 是否实时写入
            'realtime_write' => false,
        ],
        // 其它日志通道配置
    ],

];
