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

namespace ingenious\enums;

/**
 * @author Mr.April
 * @since  1.0
 */
interface ProcessConst
{
    // 业务流程号
    const  BUSINESS_NO = "BUSINESS_NO";
    // 超级管理员ID
    const  ADMIN_ID = "flow.admin";
    // 自动执行ID
    const  AUTO_ID = "flow.auto";
    const  PROCESS_NAME_KEY = "name";
    const  PROCESS_DISPLAY_NAME_KEY = "displayName";
    const  PROCESS_TYPE = "type";
    // 流程定义id，key
    const  PROCESS_DEFINE_ID_KEY = "process_define_id";
    // 流程设计id，key
    const  PROCESS_DESIGN_ID_KEY = "process_design_id";
    // 流程任务id
    const  PROCESS_TASK_ID_KEY = "processTaskId";
    // 流程实例id
    const  PROCESS_INSTANCE_ID_KEY = "processInstanceId";
    // 表单数据前辍
    const  FORM_DATA_PREFIX = "f_";
    // 任务表单数据前辍
    const  TASK_FORM_DATA_PREFIX = "tf_";
    // 审批意见
    const  APPROVAL_COMMENT = "tf_approvalComment";
    // 审批提交附件
    const  APPROVAL_ATTACHMENT = "tf_approvalAttachment";
    // 下一节点执行人
    const  NEXT_NODE_OPERATOR = "tf_nextNodeOperator";
    // 抄送人
    const  CC_ACTORS = "tf_ccActors";
    // 用户ID
    const  USER_USER_ID = "u_userId";
    // 用户姓名
    const  USER_REAL_NAME = "u_realName";
    // 用户所属部门ID
    const  USER_DEPT_ID = "u_deptId";
    // 用户所属部门名称
    const  USER_DEPT_NAME = "u_deptName";
    // 用户所属岗位id
    const  USER_POST_ID = "u_postId";
    // 用户所属岗位名称
    const  USER_POST_NAME = "u_postName";
    // 提交类型
    const  SUBMIT_TYPE = "submitType";
    // 自动生成的标题
    const  AUTO_GEN_TITLE = "autoGenTitle";
    // 节点名称
    const  TASK_NAME = "taskName";
    // 是否第一个任务节点
    const  IS_FIRST_TASK_NODE = "isFirstTaskNode";

    // 会签变量前辍
    const  COUNTERSIGN_VARIABLE_PREFIX = "csv_";
    // 活跃的会签操作人数
    const  NR_OF_ACTIVATE_INSTANCES = "nrOfActivateInstances";
    // 循环计数器，办理人在列表中的索引
    const  LOOP_COUNTER = "loopCounter";
    // 会签总实例数
    const  NR_OF_INSTANCES = "nrOfInstances";
    // 会签已完成实例数
    const  NR_OF_COMPLETED_INSTANCES = "nrOfCompletedInstances";
    // 会签操作人列表
    const  COUNTERSIGN_OPERATOR_LIST = "operatorList";
    // 会签类型 PARALLEL表示并行会签，SEQUENTIAL表示串行会签
    const  COUNTERSIGN_TYPE = "countersignType";
    // 会签不同意标识
    const  COUNTERSIGN_DISAGREE_FLAG = "countersignDisagreeFlag";
    const  ACTOR_IDS_KEY = "actorIds";
    // 自定义节点默认返回值变量
    const  CUSTOM_RETURN_VAL = "custom_return_val";

    //创建人用户ID
    const  CREATE_USER = "create_user";
}
