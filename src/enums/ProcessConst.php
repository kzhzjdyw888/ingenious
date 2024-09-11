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

namespace ingenious\enums;

/**
 * @author Mr.April
 * @since  1.0
 */
interface ProcessConst
{
    // 业务流程号
    const  BUSINESS_NO = "business_no";
    // 超级管理员ID
    const  ADMIN_ID = "flow.admin";
    // 自动执行ID
    const  AUTO_ID = "flow_auto";
    const  PROCESS_NAME_KEY = "name";
    const  PROCESS_DISPLAY_NAME_KEY = "display_name";
    const  PROCESS_TYPE = "type";
    // 流程定义id，key
    const  PROCESS_DEFINE_ID_KEY = "process_define_id";
    // 流程设计id，key
    const  PROCESS_DESIGN_ID_KEY = "process_design_id";

    const  PROCESS_FORM_ID_KEY = "process_form_id";
    // 流程任务id
    const  PROCESS_TASK_ID_KEY = "process_task_id";
    // 流程实例id
    const  PROCESS_INSTANCE_ID_KEY = "process_instance_id";
    // 表单数据前辍
    const  FORM_DATA_PREFIX = "f_";
    // 任务表单数据前辍
    const  TASK_FORM_DATA_PREFIX = "tf_";
    // 审批意见
    const  APPROVAL_COMMENT = "tf_approval_comment";
    // 审批提交附件
    const  APPROVAL_ATTACHMENT = "tf_approval_attachment";
    // 下一节点执行人
    const  NEXT_NODE_OPERATOR = "tf_next_node_operator";
    // 抄送人
    const  CC_ACTORS = "tf_cc_actors";
    // 用户ID
    const  USER_USER_ID = "u_user_id";
    // 用户账号
    const  USER_USER_NAME = "u_user_name";
    // 用户姓名
    const  USER_REAL_NAME = "u_real_name";
    // 用户所属部门ID
    const  USER_DEPT_ID = "u_dept_id";
    // 用户所属部门名称
    const  USER_DEPT_NAME = "u_dept_name";
    // 用户所属岗位id
    const  USER_POST_ID = "u_post_id";
    // 用户所属岗位名称
    const  USER_POST_NAME = "u_post_name";
    // 提交类型
    const  SUBMIT_TYPE = "submit_type";
    // 自动生成的标题
    const  AUTO_GEN_TITLE = "auto_gen_title";
    //摘要
    const PROCESS_SUMMARY = "process_summary";
    // 节点名称
    const  TASK_NAME = "task_name";
    // 是否第一个任务节点
    const  IS_FIRST_TASK_NODE = "is_first_task_node";

    // 会签变量前辍
    const  COUNTERSIGN_VARIABLE_PREFIX = "csv_";
    // 活跃的会签操作人数
    const  NR_OF_ACTIVATE_INSTANCES = "nr_of_activate_instances";
    // 循环计数器，办理人在列表中的索引
    const  LOOP_COUNTER = "loop_counter";
    // 会签总实例数
    const  NR_OF_INSTANCES = "nr_of_instances";
    // 会签已完成实例数
    const  NR_OF_COMPLETED_INSTANCES = "nr_of_completed_instances";
    // 会签操作人列表
    const  COUNTERSIGN_OPERATOR_LIST = "operator_list";
    // 会签类型 PARALLEL表示并行会签，SEQUENTIAL表示串行会签
    const  COUNTERSIGN_TYPE = "countersign_type";
    // 会签不同意标识
    const  COUNTERSIGN_DISAGREE_FLAG = "countersign_disagree_flag";
    const  ACTOR_IDS_KEY = "actor_ids";
    // 自定义节点默认返回值变量
    const  CUSTOM_RETURN_VAL = "custom_return_val";

    //创建人用户ID
    const  CREATE_USER = "create_user";

    const QUERY_PAGE_KEY = "page";//页面

    const QUERY_SIZE_KEY = "limit";//每页记录数量

    const QUERY_ORDER_KEY = "order";//排序key

}
