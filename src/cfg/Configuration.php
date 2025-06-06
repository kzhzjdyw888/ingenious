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

namespace madong\ingenious\cfg;

use madong\ingenious\core\ServiceContext;
use madong\ingenious\event\ProcessEventService;
use madong\ingenious\ex\LFlowException;
use madong\ingenious\interface\IConfiguration;
use madong\ingenious\interface\services\IProcessCcInstanceService;
use madong\ingenious\interface\services\IProcessDefineFavoriteService;
use madong\ingenious\interface\services\IProcessDefineService;
use madong\ingenious\interface\services\IProcessDesignHistoryService;
use madong\ingenious\interface\services\IProcessDesignService;
use madong\ingenious\interface\services\IProcessFormHistoryService;
use madong\ingenious\interface\services\IProcessInstanceService;
use madong\ingenious\interface\services\IProcessSurrogateService;
use madong\ingenious\interface\services\IProcessTaskActorService;
use madong\ingenious\interface\services\IProcessTaskService;
use madong\ingenious\interface\services\IProcessTypeService;
use madong\ingenious\libs\utils\Logger;
use madong\ingenious\parser\handler\CustomParser;
use madong\ingenious\parser\handler\DecisionParser;
use madong\ingenious\parser\handler\EndParser;
use madong\ingenious\parser\handler\ForkParser;
use madong\ingenious\parser\handler\JoinParser;
use madong\ingenious\parser\handler\StartParser;
use madong\ingenious\parser\handler\TaskParser;
use madong\ingenious\parser\handler\WfSubProcessParser;
use madong\ingenious\scheduling\SchedulerIProcessEventListener;

/**
 *
 * 流程引擎配置类
 * @author Mr.April
 * @since  1.0
 */
class Configuration implements IConfiguration
{

    public function __construct($config = [])
    {
        try {
            $dependencies = $config['service_dependencies'] ?? [];//依赖注入
            $dependency   = $config['dynamic_dependencies'] ?? [];//动态依赖
            $extensions   = $config['extension_parameters'] ?? [];//扩展参数
            ServiceContext::setContext($dependencies);//注册服务
            ServiceContext::registerBatch($dependency);
            ServiceContext::extensions($extensions);
            ServiceContext::setBackupConfig($config);
            $this->defaultService();
        } catch (\Throwable $e) {
            Logger::debug($e->getMessage());
            throw new  LFlowException($e->getMessage(), [], $e->getCode());
        }
    }

    public function getConfig(string $key, mixed $default = null): mixed
    {
        // 获取当前脚本的绝对路径
        $scriptPath = $_SERVER['SCRIPT_FILENAME'];
        // 获取当前脚本所在的目录路径
        $scriptDir = dirname($scriptPath);
        // 获取根目录的路径
        $rootPath = realpath($scriptDir . '/../');
        //根目录下的config目录
        $file = $rootPath . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'ingenious.php';
        if (!file_exists($file)) {
            throw new LFlowException('Add the engine configuration file first!');
        }
        $ret = require($file);
        return $ret[$key] ?? $default;
    }

    /**
     * 默认引擎服务注册
     */
    private function defaultService(): void
    {
        $parse = [
            "decision"     => DecisionParser::class,
            "end"          => EndParser::class,
            "fork"         => ForkParser::class,
            "join"         => JoinParser::class,
            "start"        => StartParser::class,
            "task"         => TaskParser::class,
            "custom"       => CustomParser::class,
            "wfSubProcess" => WfSubProcessParser::class,
        ];
        $event = [
            "processEventService"           => ProcessEventService::class,
            "schedulerProcessEventListener" => SchedulerIProcessEventListener::class,
        ];
        ServiceContext::registerBatch(array_merge($parse, $event));
    }
}
