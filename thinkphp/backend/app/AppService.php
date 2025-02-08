<?php
declare (strict_types=1);

namespace app;

use phoenix\services\GroupDataService;
use phoenix\services\SystemConfigService;
use phoenix\utils\Json;
use think\Service;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public array $bind = [
        'json' => Json::class,
        'sysConfig'    => SystemConfigService::class,
        'sysGroupData' => GroupDataService::class,
    ];

    public function boot()
    {
        // 服务启动
        defined('DS') || define('DS', DIRECTORY_SEPARATOR);
    }
}
