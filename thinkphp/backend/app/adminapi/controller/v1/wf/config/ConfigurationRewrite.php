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

namespace app\adminapi\controller\v1\wf\config;

use app\adminapi\controller\v1\wf\api\LoginUserHolder;
use app\adminapi\controller\v1\wf\handler\Assignment;
use app\adminapi\controller\v1\wf\handler\AssignmentHandler;
use ingenious\core\ServiceContext;
use ingenious\event\ProcessEventService;
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
use think\facade\Config;

class ConfigurationRewrite implements ConfigurationInterface
{
    const FILE_PREFIX = 'ingenious.';

    public function __construct(array $config = [])
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

    public function getConfig(string $key, mixed $default = null): mixed
    {
        if (empty($key)) {
            return $default ?? null;
        }
        $keys = self::FILE_PREFIX . $key;
        return Config::get($keys, $default);
    }
}
