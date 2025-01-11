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

namespace madong\ingenious\parser\handler;

use madong\helper\Dict;
use madong\ingenious\model\logicflow\LfNode;
use madong\ingenious\model\NodeModel;
use madong\ingenious\model\TaskModel;
use madong\ingenious\parser\AbstractINodeParser;
use madong\ingenious\parser\INodeParser;
use madong\ingenious\enums\CountersignTypeEnum;
use madong\ingenious\enums\ProcessTaskPerformTypeEnum;
use madong\ingenious\enums\ProcessTaskTypeEnum;

class TaskParser extends AbstractINodeParser
{
    public function parseNode(LfNode $lfNode): void
    {
        $model      = $this->nodeModel;
        $properties = $lfNode->getProperties();
        if (!empty($properties)) {
            $model->setForm($properties->get(INodeParser::FORM_KEY));
            $model->setAssignee($properties->get(INodeParser::ASSIGNEE_KEY));
            $model->setAssigneeFormKey($properties->get(INodeParser::ASSIGNEE_FORM_KEY));
            $model->setGroupKey($properties->get(INodeParser::GROUP_KEY));
            $model->setAssignmentHandler($properties->get(INodeParser::ASSIGNMENT_HANDLE_KEY));
            $model->setTaskType(ProcessTaskTypeEnum::codeOf($properties->get(INodeParser::TASK_TYPE_KEY), ProcessTaskTypeEnum::MAJOR->value));
            $model->setPerformType(ProcessTaskPerformTypeEnum::codeOf($properties->get(INodeParser::PERFORM_TYPE_KEY) ?? 0, ProcessTaskPerformTypeEnum::NORMAL->value));
            $model->setReminderTime($properties->get(INodeParser::REMINDER_TIME_KEY));
            $model->setReminderRepeat($properties->get(INodeParser::REMINDER_REPEAT_KEY));
            $model->setExpireTime($properties->get(INodeParser::EXPIRE_TIME_KEY));
            $model->setAutoExecute($properties->get(INodeParser::AUTH_EXECUTE_KEY));
            $model->setCallback($properties->get(INodeParser::CALLBACK_KEY));
            // 解析候选人属性
            $model->setCandidateUsers($properties->get(INodeParser::EXT_FIELD_CANDIDATE_USERS_KET));
            $model->setCandidateGroups($properties->get(INodeParser::EXT_FIELD_CANDIDATE_GROUPS_KEY));
            $model->setCandidateHandler($properties->get(INodeParser::EXT_FIELD_CANDIDATE_HANDLER_KEY));
            // 解析会签属性
            $model->setCountersignType(CountersignTypeEnum::codeOf($properties->get(INodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY), CountersignTypeEnum::PARALLEL->value));
            $model->setCountersignCompletionCondition($properties->get(INodeParser::EXT_FIELD_COUNTERSIGN_COMPLETION_CONDITION_KEY));
            // 自定义扩展属性
            $field = $properties->get(INodeParser::EXT_FIELD_KEY);
            if ($field != null) {
                $ext = new Dict();
                $ext->putAll($field);
                $model->setExt($ext);
                // 解析候选人属性
                $model->setCandidateUsers($properties->get(INodeParser::EXT_FIELD_CANDIDATE_USERS_KET, $ext->get(INodeParser::EXT_FIELD_CANDIDATE_USERS_KET, '')));
                $model->setCandidateGroups($properties->get(INodeParser::EXT_FIELD_CANDIDATE_GROUPS_KEY, $ext->get(INodeParser::EXT_FIELD_CANDIDATE_GROUPS_KEY, '')));
                $model->setCandidateHandler($properties->get(INodeParser::EXT_FIELD_CANDIDATE_HANDLER_KEY, $ext->get(INodeParser::EXT_FIELD_CANDIDATE_HANDLER_KEY, '')));
                // 解析会签属性
                $model->setCountersignType(CountersignTypeEnum::codeOf($properties->get(INodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY, $ext->get(INodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY, ''))));
                $model->setCountersignCompletionCondition($properties->get(INodeParser::EXT_FIELD_COUNTERSIGN_COMPLETION_CONDITION_KEY, $ext->get(INodeParser::EXT_FIELD_COUNTERSIGN_COMPLETION_CONDITION_KEY, '')));
            }
        }
    }

    public function newModel(): NodeModel
    {
        return new TaskModel();
    }

}
