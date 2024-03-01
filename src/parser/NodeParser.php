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

namespace ingenious\parser;

use ingenious\model\logicflow\LfEdge;
use ingenious\model\logicflow\LfNode;
use ingenious\model\NodeModel;

interface NodeParser
{
    const NODE_NAME_PREFIX = "ingenious:"; // 节点名称前辍
    const TEXT_VALUE_KEY = "value"; // 文本值
    const WIDTH_KEY = "width"; // 节点宽度
    const HEIGHT_KEY = "height"; // 节点高度
    const PRE_INTERCEPTORS_KEY = "preInterceptors"; // 前置拦截器
    const POST_INTERCEPTORS_KEY = "postInterceptors"; // 后置拦截器
    const EXPR_KEY = "expr"; // 表达式key
    const HANDLE_CLASS_KEY = "handleClass"; // 表达式处理类
    const FORM_KEY = "form"; // 表单标识
    const ASSIGNEE_KEY = "assignee"; // 参与人
    const ASSIGNMENT_HANDLE_KEY = "assignmentHandler"; // 参与人处理类
    const TASK_TYPE_KEY = "taskType"; // 任务类型(主办/协办)
    const PERFORM_TYPE_KEY = "performType"; // 参与类型(普通参与/会签参与)
    const REMINDER_TIME_KEY = "reminderTime"; // 提醒时间
    const REMINDER_REPEAT_KEY = "reminderRepeat"; // 重复提醒间隔
    const EXPIRE_TIME_KEY = "expireTime"; // 期待任务完成时间变量key
    const AUTH_EXECUTE_KEY = "autoExecute"; // 到期是否自动执行Y/N
    const CALLBACK_KEY = "callback"; // 自动执行回调类
    const EXT_FIELD_KEY = "field"; // 自定义扩展属性
    const EXT_FIELD_CANDIDATE_USERS_KET = "candidateUsers";
    const EXT_FIELD_CANDIDATE_GROUPS_KEY = "candidateGroups";
    const EXT_FIELD_CANDIDATE_HANDLER_KEY = "candidateHandler";
    const EXT_FIELD_COUNTERSIGN_TYPE_KEY = "countersignType"; // 会签类型
    const EXT_FIELD_COUNTERSIGN_COMPLETION_CONDITION_KEY = "countersignCompletionCondition"; // 会签完成条件
    const CLASS_KEY = "clazz"; // 类路径
    const METHOD_NAME_KEY = "methodName"; // 方法名
    const ARGS_KEY = "args"; // 方法入参
    const RETURN_VAL_KEY = "val"; // 返回变量名
    const VERSION_KEY = "version"; // 版本号

    /**
     * 节点属性解析方法，由解析类完成解析
     *
     * @param \ingenious\model\logicflow\LfNode       $lfNode LogicFlow节点对象
     * @param \ingenious\model\logicflow\LfEdge|array $edges  所有边对象
     */
    public function parse(LfNode $lfNode, LfEdge|array $edges): void;

    /**
     * 解析完成后，提供返回NodeModel对象
     */
    public function getModel():NodeModel;

}
