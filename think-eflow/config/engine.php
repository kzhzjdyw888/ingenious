<?php

use madong\ingenious\interface\IAssignment;
use madong\ingenious\interface\IAuthenticatedUser;
use madong\ingenious\interface\ICandidateHandler;
use madong\ingenious\interface\IProcessUser;
use madong\ingenious\interface\services\IProcessCcInstanceService;
use madong\ingenious\interface\services\IProcessDefineFavoriteService;
use madong\ingenious\interface\services\IProcessDefineService;
use madong\ingenious\interface\services\IProcessDesignHistoryService;
use madong\ingenious\interface\services\IProcessDesignService;
use madong\ingenious\interface\services\IProcessFormHistoryService;
use madong\ingenious\interface\services\IProcessFormService;
use madong\ingenious\interface\services\IProcessInstanceHistoryService;
use madong\ingenious\interface\services\IProcessInstanceService;
use madong\ingenious\interface\services\IProcessSurrogateService;
use madong\ingenious\interface\services\IProcessTaskActorHistoryService;
use madong\ingenious\interface\services\IProcessTaskActorService;
use madong\ingenious\interface\services\IProcessTaskHistoryService;
use madong\ingenious\interface\services\IProcessTaskService;
use madong\ingenious\interface\services\IProcessTypeService;
use madong\think\wf\services\ProcessCcInstanceService;
use madong\think\wf\services\ProcessDefineFavoriteService;
use madong\think\wf\services\ProcessDefineService;
use madong\think\wf\services\ProcessDesignHistoryService;
use madong\think\wf\services\ProcessDesignService;
use madong\think\wf\services\ProcessFormHistoryService;
use madong\think\wf\services\ProcessFormService;
use madong\think\wf\services\ProcessInstanceHistoryService;
use madong\think\wf\services\ProcessInstanceService;
use madong\think\wf\services\ProcessSurrogateService;
use madong\think\wf\services\ProcessTaskActorHistoryService;
use madong\think\wf\services\ProcessTaskActorService;
use madong\think\wf\services\ProcessTaskHistoryService;
use madong\think\wf\services\ProcessTaskService;
use madong\think\wf\services\ProcessTypeService;
use app\common\extra\AuthenticatedUser;
use app\common\extra\CustomCandidateHandler;
use app\common\extra\ProcessUser;
use app\common\extra\AssignmentDefault;

return [
    // 服务依赖注入
    'service_dependencies' => [
        IProcessCcInstanceService::class       => \DI\create(ProcessCcInstanceService::class),
        IProcessDefineService::class           => \DI\create(ProcessDefineService::class),
        IProcessDefineFavoriteService::class   => \DI\create(ProcessDefineFavoriteService::class),
        IProcessDesignService::class           => \DI\create(ProcessDesignService::class),
        IProcessDesignHistoryService::class    => \DI\create(ProcessDesignHistoryService::class),
        IProcessFormService::class             => \DI\create(ProcessFormService::class),
        IProcessFormHistoryService::class      => \DI\create(ProcessFormHistoryService::class),
        IProcessInstanceService::class         => \DI\create(ProcessInstanceService::class),
        IProcessInstanceHistoryService::class  => \DI\create(ProcessInstanceHistoryService::class),
        IProcessSurrogateService::class        => \DI\create(ProcessSurrogateService::class),
        IProcessTaskService::class             => \DI\create(ProcessTaskService::class),
        IProcessTaskHistoryService::class      => \DI\create(ProcessTaskHistoryService::class),
        IProcessTaskActorService::class        => \DI\create(ProcessTaskActorService::class),
        IProcessTaskActorHistoryService::class => \DI\create(ProcessTaskActorHistoryService::class),
        IProcessTypeService::class             => \DI\create(ProcessTypeService::class),
    ],
    // 动态类依赖注入
    'dynamic_dependencies' => [
        IAuthenticatedUser::class => AuthenticatedUser::class,     //当前系统用户
        IAssignment::class        => AssignmentDefault::class,     //默认参与者输出
        ICandidateHandler::class  => CustomCandidateHandler::class,//候选人输出
        IProcessUser::class       => ProcessUser::class,           //流程用户搜索
    ],
    // 扩展配置参数
    'extension_parameters' => [
        'debug'     => true,
        'logs_path' => runtime_path() . '/logs/wf/',
        'redis'     => [
            'host'       => '127.0.0.1',
            'password'   => null,
            'port'       => 6379,
            'database'   => 0,
            'expire'     => 0,
            'prefix'     => 'l:',
            'tag_prefix' => 'wf:',
            'timeout'    => 0,
        ],
    ],
];
