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

namespace ingenious\parser\handler;

use ingenious\enums\CountersignTypeEnum;
use ingenious\enums\ProcessTaskPerformTypeEnum;
use ingenious\enums\ProcessTaskTypeEnum;
use ingenious\libs\utils\Dict;
use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;
use ingenious\model\TaskModel;
use ingenious\parser\AbstractNodeParser;
use ingenious\parser\NodeParser;

class TaskParser extends AbstractNodeParser
{
    public function parseNode(LfNode $lfNode): void
    {
        $model      = $this->nodeModel;
        $properties = $lfNode->getProperties();
        if (!empty($properties)) {
            $model->setForm($properties->get(NodeParser::FORM_KEY));
            $model->setAssignee($properties->get(NodeParser::ASSIGNEE_KEY));
            $model->setAssignmentHandler($properties->get(NodeParser::ASSIGNMENT_HANDLE_KEY));
            $model->setTaskType(ProcessTaskTypeEnum::codeOf($properties->get(NodeParser::TASK_TYPE_KEY), ProcessTaskTypeEnum::MAJOR));
            $model->setPerformType(ProcessTaskPerformTypeEnum::codeOf($properties->get(NodeParser::PERFORM_TYPE_KEY) ?? 0, ProcessTaskPerformTypeEnum::NORMAL));
            $model->setReminderTime($properties->get(NodeParser::REMINDER_TIME_KEY));
            $model->setReminderRepeat($properties->get(NodeParser::REMINDER_REPEAT_KEY));
            $model->setExpireTime($properties->get(NodeParser::EXPIRE_TIME_KEY));
            $model->setAutoExecute($properties->get(NodeParser::AUTH_EXECUTE_KEY));
            $model->setCallback($properties->get(NodeParser::CALLBACK_KEY));
            // 解析候选人属性
            $model->setCandidateUsers($properties->get(NodeParser::EXT_FIELD_CANDIDATE_USERS_KET));
            $model->setCandidateGroups($properties->get(NodeParser::EXT_FIELD_CANDIDATE_GROUPS_KEY));
            $model->setCandidateHandler($properties->get(NodeParser::EXT_FIELD_CANDIDATE_HANDLER_KEY));
            // 解析会签属性
            $model->setCountersignType(CountersignTypeEnum::codeOf($properties->get(NodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY), CountersignTypeEnum::PARALLEL));
            $model->setCountersignCompletionCondition($properties->get(NodeParser::EXT_FIELD_COUNTERSIGN_COMPLETION_CONDITION_KEY));
            // 自定义扩展属性
            $field = $properties->get(NodeParser::EXT_FIELD_KEY);
            if ($field != null) {
                $ext = new Dict();
                $ext->putAll($field);
                $model->setExt($ext);
                // 解析候选人属性
                $model->setCandidateUsers($properties->get(NodeParser::EXT_FIELD_CANDIDATE_USERS_KET, $ext->get(NodeParser::EXT_FIELD_CANDIDATE_USERS_KET, '')));
                $model->setCandidateGroups($properties->get(NodeParser::EXT_FIELD_CANDIDATE_GROUPS_KEY, $ext->get(NodeParser::EXT_FIELD_CANDIDATE_GROUPS_KEY, '')));
                $model->setCandidateHandler($properties->get(NodeParser::EXT_FIELD_CANDIDATE_HANDLER_KEY, $ext->get(NodeParser::EXT_FIELD_CANDIDATE_HANDLER_KEY, '')));
                // 解析会签属性
                $model->setCountersignType(CountersignTypeEnum::codeOf($properties->get(NodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY, $ext->get(NodeParser::EXT_FIELD_COUNTERSIGN_TYPE_KEY, ''))));
                $model->setCountersignCompletionCondition($properties->get(NodeParser::EXT_FIELD_COUNTERSIGN_COMPLETION_CONDITION_KEY, $ext->get(NodeParser::EXT_FIELD_COUNTERSIGN_COMPLETION_CONDITION_KEY, '')));
            }
        }
    }

    public function newModel(): NodeModel
    {
        return new TaskModel();
    }

}
