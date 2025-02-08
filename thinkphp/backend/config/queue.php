<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    'default'     => 'redis',
    'prefix'      => 'phoenix_',
    'connections' => [
        'sync'     => [
            'type' => 'sync',
        ],
        'database' => [
            'type'       => 'database',
            'queue'      => 'default',
            'table'      => 'jobs',
            'connection' => null,
        ],
        'redis'    => [
            'type'     => 'redis',
            'queue'      => 'PHOENIX' . Env::get('queue.queue_name', ''),
            'host'       => Env::get('redis.redis_hostname', '127.0.0.1'),
            'port'       => Env::get('redis.port', 6379),
            'password'   => Env::get('redis.redis_password', ''),
            'select'     => Env::get('redis.select', 0),
            'timeout'    => 0,
            'persistent' => false,
        ],
    ],
    'failed'      => [
        'type'  => 'none',
        'table' => 'failed_jobs',
    ],
];
