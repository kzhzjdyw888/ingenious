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

namespace madong\ingenious\enums;

use madong\ingenious\interface\IEnum;
use madong\ingenious\libs\traits\EnumTrait;

/**
 * @author Mr.April
 * @since  1.0
 */
enum ProcessConstEnum: string implements IEnum
{
    use EnumTrait;

    case BUSINESS_NO = "business_no"; // 业务流程号
    case ADMIN_ID = "flow.admin"; // 超级管理员ID
    case AUTO_ID = "flow_auto"; // 自动执行ID
    case PROCESS_NAME_KEY = "name"; // 流程名称
    case PROCESS_DISPLAY_NAME_KEY = "display_name"; // 流程显示名称
    case PROCESS_TYPE = "type"; // 流程类型
    case PROCESS_DEFINE_ID_KEY = "process_define_id"; // 流程定义ID
    case PROCESS_DESIGN_ID_KEY = "process_design_id"; // 流程设计ID
    case PROCESS_FORM_KEY = "form_data"; // 流程表单key
    case PROCESS_FORM_ID_KEY = "process_form_id"; // 流程表单ID
    case PROCESS_TASK_ID_KEY = "process_task_id"; // 流程任务ID
    case PROCESS_INSTANCE_ID_KEY = "process_instance_id"; // 流程实例ID
    case FORM_DATA_PREFIX = "f_"; // 表单数据前辍
    case TASK_FORM_DATA_PREFIX = "tf_"; // 任务表单数据前辍
    case APPROVAL_COMMENT = "tf_approval_comment"; // 审批意见
    case APPROVAL_ATTACHMENT = "tf_approval_attachment"; // 审批提交附件
    case NEXT_NODE_OPERATOR = "tf_next_node_operator"; // 下一节点执行人
    case CC_ACTORS = "tf_cc_actors"; // 抄送人
    case USER_USER_ID = "u_user_id"; // 用户ID
    case USER_USER_NAME = "u_user_name"; // 用户账号
    case USER_REAL_NAME = "u_real_name"; // 用户姓名
    case USER_DEPT_ID = "u_dept_id"; // 用户所属部门ID
    case USER_DEPT_NAME = "u_dept_name"; // 用户所属部门名称
    case USER_POST_ID = "u_post_id"; // 用户所属岗位ID
    case USER_POST_NAME = "u_post_name"; // 用户所属岗位名称
    case SUBMIT_TYPE = "submit_type"; // 提交类型
    case AUTO_GEN_TITLE = "auto_gen_title"; // 自动生成的标题
    case PROCESS_SUMMARY = "process_summary"; // 摘要
    case TASK_NAME = "task_name"; // 节点名称
    case IS_FIRST_TASK_NODE = "is_first_task_node"; // 是否第一个任务节点
    case COUNTERSIGN_VARIABLE_PREFIX = "csv_"; // 会签变量前辍
    case NR_OF_ACTIVATE_INSTANCES = "nr_of_activate_instances"; // 活跃的会签操作人数
    case LOOP_COUNTER = "loop_counter"; // 循环计数器
    case NR_OF_INSTANCES = "nr_of_instances"; // 会签总实例数
    case NR_OF_COMPLETED_INSTANCES = "nr_of_completed_instances"; // 会签已完成实例数
    case COUNTERSIGN_OPERATOR_LIST = "operator_list"; // 会签操作人列表
    // 会签类型 PARALLEL表示并行会签，SEQUENTIAL表示串行会签
    case COUNTERSIGN_TYPE = "countersign_type"; // 会签类型
    case COUNTERSIGN_DISAGREE_FLAG = "countersign_disagree_flag"; // 会签不同意标识
    case ACTOR_IDS_KEY = "actor_ids"; // 演员ID列表
    case CUSTOM_RETURN_VAL = "custom_return_val"; // 自定义节点返回值
    case CREATE_USER = "create_by"; // 创建人用户ID
    case QUERY_PAGE_KEY = "page"; // 页面
    case QUERY_SIZE_KEY = "limit"; // 每页记录数量
    case QUERY_ORDER_KEY = "order"; // 排序key

    case OPERATOR_KEY = "operator";//操作人key

    public function label(): string
    {
        return match ($this) {
            self::BUSINESS_NO => '业务流程号',
            self::ADMIN_ID => '超级管理员ID',
            self::AUTO_ID => '自动执行ID',
            self::PROCESS_NAME_KEY => '流程名称',
            self::PROCESS_DISPLAY_NAME_KEY => '流程显示名称',
            self::PROCESS_TYPE => '流程类型',
            self::PROCESS_DEFINE_ID_KEY => '流程定义ID',
            self::PROCESS_DESIGN_ID_KEY => '流程设计ID',
            self::PROCESS_FORM_KEY => '流程表单KEY',
            self::PROCESS_FORM_ID_KEY => '流程表单ID',
            self::PROCESS_TASK_ID_KEY => '流程任务ID',
            self::PROCESS_INSTANCE_ID_KEY => '流程实例ID',
            self::FORM_DATA_PREFIX => '表单数据前辍',
            self::TASK_FORM_DATA_PREFIX => '任务表单数据前辍',
            self::APPROVAL_COMMENT => '审批意见',
            self::APPROVAL_ATTACHMENT => '审批提交附件',
            self::NEXT_NODE_OPERATOR => '下一节点执行人',
            self::CC_ACTORS => '抄送人',
            self::USER_USER_ID => '用户ID',
            self::USER_USER_NAME => '用户账号',
            self::USER_REAL_NAME => '用户姓名',
            self::USER_DEPT_ID => '用户所属部门ID',
            self::USER_DEPT_NAME => '用户所属部门名称',
            self::USER_POST_ID => '用户所属岗位ID',
            self::USER_POST_NAME => '用户所属岗位名称',
            self::SUBMIT_TYPE => '提交类型',
            self::AUTO_GEN_TITLE => '自动生成的标题',
            self::PROCESS_SUMMARY => '摘要',
            self::TASK_NAME => '节点名称',
            self::IS_FIRST_TASK_NODE => '是否第一个任务节点',
            self::COUNTERSIGN_VARIABLE_PREFIX => '会签变量前辍',
            self::NR_OF_ACTIVATE_INSTANCES => '活跃的会签操作人数',
            self::LOOP_COUNTER => '循环计数器',
            self::NR_OF_INSTANCES => '会签总实例数',
            self::NR_OF_COMPLETED_INSTANCES => '会签已完成实例数',
            self::COUNTERSIGN_OPERATOR_LIST => '会签操作人列表',
            self::COUNTERSIGN_TYPE => '会签类型',
            self::COUNTERSIGN_DISAGREE_FLAG => '会签不同意标识',
            self::ACTOR_IDS_KEY => '演员ID列表',
            self::CUSTOM_RETURN_VAL => '自定义节点返回值',
            self::CREATE_USER => '创建人用户ID',
            self::QUERY_PAGE_KEY => '页面',
            self::QUERY_SIZE_KEY => '每页记录数量',
            self::QUERY_ORDER_KEY => '排序key',
            self::OPERATOR_KEY => '操作人key'
        };
    }
}
