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

namespace ingenious\cfg;

use ingenious\core\ServiceContext;
use ingenious\event\EventManager;
use ingenious\event\ProcessEventService;
use ingenious\ex\LFlowException;
use ingenious\interface\ConfigurationInterface;
use ingenious\parser\handler\CustomParser;
use ingenious\parser\handler\DecisionParser;
use ingenious\parser\handler\EndParser;
use ingenious\parser\handler\ForkParser;
use ingenious\parser\handler\JoinParser;
use ingenious\parser\handler\StartParser;
use ingenious\parser\handler\TaskParser;
use ingenious\parser\handler\WfSubProcessParser;
use ingenious\service\ProcessCcInstanceService;
use ingenious\service\ProcessDefineService;
use ingenious\service\ProcessDesignService;
use ingenious\service\ProcessInstanceService;
use ingenious\service\ProcessTaskService;
use ingenious\service\ProcessTypesService;

/**
 * 流程引擎配置类
 *
 * @author Mr.April
 * @since  1.0
 */
class Configuration implements ConfigurationInterface
{
    public function __construct($config = [])
    {
        ServiceContext::setContext($config);

        //默认服务配置
        ServiceContext::put("decision", new DecisionParser());
        ServiceContext::put("end", new EndParser());
        ServiceContext::put("fork", new ForkParser());
        ServiceContext::put("join", new JoinParser());
        ServiceContext::put("start", new StartParser());
        ServiceContext::put("task", new TaskParser());
        ServiceContext::put("custom", new CustomParser());
        ServiceContext::put("wfSubProcess", new WfSubProcessParser());

        //注册服务
        ServiceContext::put("processTaskService", new ProcessTaskService());
        ServiceContext::put("processDefineService", new ProcessDefineService());
        ServiceContext::put("processInstanceService", new ProcessInstanceService());
        ServiceContext::put("processTypesService", new ProcessTypesService());
        ServiceContext::put("processCcInstanceService", new ProcessCcInstanceService());
        ServiceContext::put("processDesignService", new ProcessDesignService());
        //事件服务配置
        ServiceContext::put('eventService', new ProcessEventService());
    }

    /**
     * 根据键值加载全局配置文件
     *
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
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
}
