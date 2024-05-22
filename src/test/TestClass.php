<?php
/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */

namespace ingenious\test;

use ingenious\core\ProcessEngines;
use ingenious\enums\ProcessConst;
use ingenious\libs\utils\Dict;
use ingenious\libs\utils\Logger;
use ingenious\libs\utils\ProcessFlowUtils;
use ingenious\model;
use ingenious\service\ProcessDefineService;
use ingenious\service\ProcessDesignHisService;
use ingenious\service\ProcessInstanceService;
use ingenious\service\ProcessSurrogateService;
use ingenious\service\ProcessTaskService;
use think\facade\Db;


class TestClass
{

    /**流程设计***********************************************************************************************************************/
    //创建流程设计历史记录
    public function createDesignHis($param): bool
    {
        $param->process_design_id = '373c17e7-54bf-4e2e-8205-87a8818d2bd1';
        $param->content           = (object)[];
        $param->create_user       = '123';
        $processDesignHisService  = new ProcessDesignHisService();
        return $processDesignHisService->create($param);
    }

    //更新流程设计历史记录
    public function updateDesignHis($param): bool
    {
        $param->process_design_id = '373c17e7-54bf-4e2e-8205-87a8818d2bd1';
        $param->content           = (object)[];
        $param->create_user       = '1234';
        $param->id                = '0ef9b6c4-1c3a-4ea5-8f5c-555bb8231aba';
        $processDesignHisService  = new ProcessDesignHisService();
        return $processDesignHisService->update($param);
    }

    public function designHisPage($param): array
    {
        $processDesignHisService = new ProcessDesignHisService();
        return $processDesignHisService->page($param);
    }

    public function designHisLast($param): db\ProcessDesignHis|model\ProcessDesignHis|null
    {
        $processDesignId         = $param->id;
        $processDesignHisService = new ProcessDesignHisService();
        return $processDesignHisService->getLatestByProcessDesignId($processDesignId);
    }

    /**流程定义******************************************************************************************************************/

    public function defineCreate($param): bool
    {
        $param->type_id       = '';
        $param->name          = 'test';
        $param->display_name  = '测试添加';
        $param->description   = '描述';
        $param->state         = 0;
        $param->content       = (object)[];
        $param->create_user   = '123';
        $processDefineService = new ProcessDefineService();
        return $processDefineService->create($param);
    }

    public function defineUpdate($param): bool
    {
        $param->id            = 'a8a81338-49cd-49f1-be84-9e0c6a4149f2';
        $param->name          = 'test';
        $param->display_name  = '测试添加更新';
        $param->description   = '描述';
        $param->state         = 0;
        $param->content       = (object)[];
        $param->create_user   = '1235';
        $processDefineService = new ProcessDefineService();
        return $processDefineService->update($param);

    }

    public function definePage($param): array
    {
        $processDefineService = new ProcessDefineService();
        return $processDefineService->page($param);

    }

    public function defineFindById($param): db\ProcessDefine
    {
        $processDefineService = new ProcessDefineService();
        return $processDefineService->findById($param->id);
    }

    public function defineUnDeploy($param)
    {
        $processDefineService = new ProcessDefineService();
        $operation            = '123';
        return $processDefineService->unDeploy($param->id, $operation);
    }

    public function defineGetDefineJsonStr($param)
    {
        $processDefineService = new ProcessDefineService();
        return $processDefineService->getDefineJsonStr($param->id);
    }

    public function defineUpAndDown($param)
    {
        $processDefineService = new ProcessDefineService();
        return $processDefineService->upAndDown($param);
    }

    public function defineGetLastByName($param)
    {
        $processDefineService = new ProcessDefineService();
        return $processDefineService->getLastByName($param->name);
    }

    public function defineGetProcessDefineByVersion($param)
    {
        $processDefineService = new ProcessDefineService();
        return $processDefineService->getProcessDefineByVersion($param->name, 4);
    }

    /**流程实例*****************************************************************************************************************/

    public function instancePage($param): array
    {
        $processDefineService = new ProcessInstanceService();
        return $processDefineService->page($param);
    }

    public function instanceFinishProcessInstance($param)
    {
        $processDefineService = new ProcessInstanceService();
        return $processDefineService->finishProcessInstance($param->id);
    }

    public function instanceCreateProcessInstance($param): db\ProcessInstance
    {
        $processDefineService   = new ProcessDefineService();
        $processInstanceService = new ProcessInstanceService();
        $processDefine          = $processDefineService->findById($param->id);
        $args                   = (object)[
            'test' => '12',
        ];
        return $processInstanceService->createProcessInstance($processDefine, '123', $args, '', '');
    }

    public function instanceAddVariable($param): db\ProcessInstance
    {
        $processInstanceService = new ProcessInstanceService();
        $args                   = new Dict();
        $args->putAll([
            'test' => '12',
            'name' => '测试',
        ]);
        return $processInstanceService->addVariable($param->id, $args);
    }

    public function instanceRemoveVariable($param): db\ProcessInstance
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->removeVariable($param->id, 'u_deptId,f_title,aaaa');
    }

    public function instanceInterrupt($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->interrupt($param->id, 'admin');
    }

    public function instanceResume($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->resume($param->id, 'admin');
    }

    public function instancePending($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->Pending($param->id, 'admin');
    }

    public function instanceActivate($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->activate($param->id, 'admin');
    }

    public function startAndExecute($param)
    {
        $processInstanceService = new ProcessInstanceService();
        $args                   = new Dict();
        $args->putAll([
            ProcessConst::USER_USER_ID => 'admin',//用户id
            'f_uid'                    => '001',
        ]);
        return $processInstanceService->startAndExecute($param->id, $args);
    }

    public function instanceHighLight($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->highLight((string)$param->id);
    }

    public function instanceApprovalRecord($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->approvalRecord((string)$param->id);
    }

    public function instanceWithdraw($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->withdraw((string)$param->id, 'test');
    }

    public function instanceUpdateCCStatus($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->updateCCStatus((string)$param->id, 'test');
    }

    public function instanceCreateCCInstance($param)
    {
        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->createCCInstance((string)$param->id, '1', 'test,admin');
    }

    public function instanceUpdateCountersignVariable($param)
    {

        $processInstanceService = new ProcessInstanceService();
        return $processInstanceService->updateCountersignVariable((string)$param->id, '1', 'test,admin');
    }

    /**流程代理*****************************************************************************************************************/
    public function surrogatePage($param)
    {
        $processSurrogateService = new ProcessSurrogateService();
        return $processSurrogateService->page($param);
    }

    public function surrogateGetSurrogate($param)
    {
        $processSurrogateService = new ProcessSurrogateService();
        return $processSurrogateService->getSurrogate('admin', 'leave');
    }

    /** 流程任务*****************************************************************************************************************/

    public function taskFindById($param)
    {
        $processTaskService = new ProcessTaskService();
        return $processTaskService->findById('1729326781630730241');
    }

    public function addTaskActor($param)
    {
        $processTaskService = new ProcessTaskService();
        return $processTaskService->addTaskActor('1729326781630730241', ['admin', '1686404946814533633']);
    }

    public function addCandidateActor($param)
    {
        $processTaskService = new ProcessTaskService();
        return $processTaskService->addCandidateActor('1729326781630730241', ['admin', '1686404946814533633']);
    }

    public function removeTaskActor($param)
    {
        $processTaskService = new ProcessTaskService();
        return $processTaskService->removeTaskActor('1729326781630730241', 'admin');
    }

    public function getTaskActors($param)
    {
        $processTaskService = new ProcessTaskService();
        return $processTaskService->getTaskActors('1729326781630730241');
    }

    public function todoList($param)
    {
        $processTaskService = new ProcessTaskService();
        return $processTaskService->todoList($param);
    }

    public function doneList($param)
    {
        $processTaskService = new ProcessTaskService();
        return $processTaskService->doneList($param);
    }

    /*接口测试*******************************************************************************************/

    public function startProcessInstanceById($param)
    {
        $processEngines = new ProcessEngines();
        $args           = ProcessFlowUtils::variableToDict((object)['uid' => '1']);
        return $processEngines->startProcessInstanceById($param['id'], $param['operator'], $args, '', '');
    }

    public function executeAndJumpTask($param)
    {
        $processEngines = new ProcessEngines();
        $args           = ProcessFlowUtils::variableToDict((object)['f_uid' => '1', 'f_day' => 3, 'u_udd' => 're']);
        return $processEngines->executeAndJumpTask($param['id'], $param['operator'], $args, '', '');
    }

    public function executeProcessTask($param)
    {
        $processEngines = new ProcessEngines();
        $args           = ProcessFlowUtils::variableToDict((object)['f_uid' => '1', 'f_day' => 3, 'u_udd' => 're']);
        return $processEngines->executeProcessTask($param['id'], $param['operator'], $args);
    }

    public function executeAndJumpToEnd($param)
    {
//        $config         = ['getUser' => new UserAssign()];
        $processEngines = new ProcessEngines([]);
        $args           = ProcessFlowUtils::variableToDict((object)['f_uid' => '1', 'f_day' => 3, 'u_udd' => 're']);
        return $processEngines->executeAndJumpToEnd($param['id'], $param['operator'], $args);
    }

    public function index($param, $operation)
    {
//        $param = (object)$param;
        $engines   = new ProcessEngines();
//        dump($engines);exit;
//        $testClass = new ProcessDesignService();
//        return $testClass->redeploy($param->id, $operation);
        Db::startTrans();
//        $result = $this->executeAndJumpToEnd($param);

//        $result = new StringBuilder();
//        $result->append('123,');
//        $result->append('321,');

//        dump($result->toArray());

//        $logFilePath  = __DIR__ . '/logs';
//        $logLevel     = Logger::DEBUG;
//        $monthsToKeep = 3;
//
//        $log = Log::getLogger('debug', $logFilePath, $logLevel);
//        $log->info('This is an info message.');
//        $log=new Log();
//CustomLogger::getInstance()->deleteOldLogs();
//        Logs::getInstance()->deleteOldLogs();
//        Logs::getInstance()->info('This is an info message.');

//        CustomLogger::getInstance()->info('This is an info message.');

// 删除旧的跨月日志
//        Log::deleteOldLogs($logFilePath, $monthsToKeep);
//        Logs::log('debug','测试');
//        Logs::log('info','infooo');
//        Logger::info('message');
        Logger::debug('message');
//        Logger::notice('notice');
//        Logger::deleteOldLogs();

//        Db::commit();
        dump('启动成功12');
        exit;
    }



}

