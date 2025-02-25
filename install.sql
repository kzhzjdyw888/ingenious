
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
-- ----------------------------
-- Table structure for wa_wf_crontab
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_crontab`;
CREATE TABLE `wa_wf_crontab`  (
  `id` bigint(20) UNSIGNED NOT NULL,
  `biz_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '业务ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务标题',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '任务类型1 url,2 eval,3 shell',
  `task_cycle` tinyint(1) NOT NULL DEFAULT 1 COMMENT '任务周期',
  `cycle_rule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '任务周期规则',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务表达式',
  `target` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '调用任务字符串',
  `running_times` int(11) NOT NULL DEFAULT 0 COMMENT '已运行次数',
  `last_running_time` int(11) NOT NULL DEFAULT 0 COMMENT '上次运行时间',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '任务状态状态0禁用,1启用',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `delete_time` int(11) NOT NULL DEFAULT 0 COMMENT '软删除时间',
  `singleton` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否单次执行0是,1不是',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_crontab_log
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_crontab_log`;
CREATE TABLE `wa_wf_crontab_log`  (
  `id` bigint(20) UNSIGNED NOT NULL,
  `crontab_id` bigint(20) UNSIGNED NOT NULL COMMENT '任务id',
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务调用目标字符串',
  `log` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '任务执行日志',
  `return_code` tinyint(1) NOT NULL DEFAULT 0 COMMENT '执行返回状态,0成功,1失败',
  `running_time` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '执行所用时间',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `crontab_id`(`crontab_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务执行日志表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_cc_instance
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_cc_instance`;
CREATE TABLE `wa_wf_process_cc_instance`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `process_instance_id` bigint(20) NOT NULL COMMENT '流程实例ID',
  `process_task_id` bigint(20) NULL DEFAULT NULL COMMENT '任务ID',
  `actor_id` bigint(20) NOT NULL COMMENT '被抄送人ID',
  `state` int(11) NULL DEFAULT 0 COMMENT '抄送状态(1:已读；0：未读)',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_by` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_by` bigint(36) NULL DEFAULT NULL COMMENT '更新用户',
  `remark` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_pccins_pinsid`(`process_instance_id`) USING BTREE,
  INDEX `idx_pccins_actor_id`(`actor_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程实例抄送' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_define
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_define`;
CREATE TABLE `wa_wf_process_define`  (
  `id` bigint(20) UNSIGNED NOT NULL COMMENT '主键',
  `type_id` bigint(20) NULL DEFAULT NULL COMMENT '流程分类',
  `icon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT 'icon',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '唯一编码',
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '显示名称',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '流程描述',
  `enabled` int(11) NULL DEFAULT 0 COMMENT '流程是否可用(1可用；0不可用)',
  `is_active` tinyint(1) NULL DEFAULT 0 COMMENT '是否活跃版本(1是 0否)',
  `content` json NULL COMMENT '流程模型定义',
  `version` float(3, 1) UNSIGNED NULL DEFAULT 1.0 COMMENT '版本',
  `create_time` bigint(10) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_user` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  `delete_time` bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_define_name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程定义' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_define_favorite
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_define_favorite`;
CREATE TABLE `wa_wf_process_define_favorite`  (
  `id` bigint(20) NOT NULL COMMENT 'ID',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户ID',
  `process_define_id` bigint(20) NULL DEFAULT NULL COMMENT '流程定义ID',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '流程收藏表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_design
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_design`;
CREATE TABLE `wa_wf_process_design`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '唯一编码',
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '显示名称',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '描述',
  `type_id` bigint(20) NULL DEFAULT NULL COMMENT '流程分类',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `is_deployed` tinyint(1) NULL DEFAULT 0 COMMENT '是否已部署',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` bigint(36) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_user` bigint(36) NULL DEFAULT NULL COMMENT '更新用户',
  `delete_time` bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
  `remark` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_designer_name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程设计' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_design_history
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_design_history`;
CREATE TABLE `wa_wf_process_design_history`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
  `process_design_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '流程设计ID',
  `content` json NULL COMMENT '流程模型定义',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `version` float(3, 1) NULL DEFAULT 1.0 COMMENT '版本',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_design_his_pdid`(`process_design_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程设计历史' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_form
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_form`;
CREATE TABLE `wa_wf_process_form`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '唯一编码',
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '显示名称',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '描述',
  `type_id` bigint(20) NULL DEFAULT NULL COMMENT '流程分类',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `enabled` tinyint(1) NULL DEFAULT 0 COMMENT '是否禁用',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_user` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  `delete_time` bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_form_name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '表单设计' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_form_history
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_form_history`;
CREATE TABLE `wa_wf_process_form_history`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `process_form_id` bigint(20) NOT NULL COMMENT 'ID',
  `content` json NULL COMMENT '模型定义',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建用户',
  `version` float(3, 1) NULL DEFAULT 1.0 COMMENT '版本',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_design_his_pdid`(`process_form_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '表单设计历史' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_instance
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_instance`;
CREATE TABLE `wa_wf_process_instance`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
  `parent_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程ID，子流程实例才有值',
  `process_define_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程定义ID',
  `state` int(11) NULL DEFAULT NULL COMMENT '流程实例状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
  `parent_node_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程依赖的节点名称',
  `business_no` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '业务编号',
  `operator` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程发起人',
  `variable` json NULL COMMENT '附属变量json存储',
  `expire_time` int(3) NULL DEFAULT NULL COMMENT '期望完成时间',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  `update_user` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '更新用户',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_instance_pfid`(`process_define_id`) USING BTREE,
  INDEX `idx_process_instance_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程实例' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_instance_history
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_instance_history`;
CREATE TABLE `wa_wf_process_instance_history`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
  `parent_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程ID，子流程实例才有值',
  `process_define_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程定义ID',
  `state` int(11) NULL DEFAULT NULL COMMENT '流程实例状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
  `parent_node_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程依赖的节点名称',
  `business_no` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '业务编号',
  `operator` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程发起人',
  `variable` json NULL COMMENT '附属变量json存储',
  `expire_time` int(3) NULL DEFAULT NULL COMMENT '期望完成时间',
  `create_time` int(3) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` int(3) NULL DEFAULT NULL COMMENT '更新时间',
  `update_user` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '更新用户',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_instance_pfid`(`process_define_id`) USING BTREE,
  INDEX `idx_process_instance_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程实例' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_surrogate
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_surrogate`;
CREATE TABLE `wa_wf_process_surrogate`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `process_define_id` bigint(20) NULL DEFAULT NULL COMMENT '流程定义id',
  `operator` bigint(20) NOT NULL COMMENT '授权人',
  `surrogate` bigint(64) NOT NULL COMMENT '代理人',
  `start_time` bigint(20) NULL DEFAULT NULL COMMENT '授权开始时间',
  `end_time` bigint(20) NULL DEFAULT NULL COMMENT '授权结束时间',
  `enabled` tinyint(1) NULL DEFAULT 1 COMMENT '是否启用',
  `create_time` bigint(10) NULL DEFAULT NULL COMMENT '创建时间',
  `create_by` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_by` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程委托代理' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_task
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_task`;
CREATE TABLE `wa_wf_process_task`  (
  `id` bigint(36) NOT NULL COMMENT '主键',
  `process_instance_id` bigint(36) NOT NULL COMMENT '流程实例ID',
  `task_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务名称编码',
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务显示名称',
  `task_type` int(11) NULL DEFAULT NULL COMMENT '任务类型(0：主办任务；1：协办任务)',
  `perform_type` int(11) NULL DEFAULT NULL COMMENT '参与类型(0：普通参与；1：会签参与)',
  `task_state` int(11) NULL DEFAULT NULL COMMENT '任务状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
  `operator` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理人',
  `finish_time` bigint(10) NULL DEFAULT NULL COMMENT '任务完成时间',
  `expire_time` bigint(20) NULL DEFAULT NULL COMMENT '任务期待完成时间',
  `form_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理表单KEY',
  `task_parent_id` bigint(20) NULL DEFAULT NULL COMMENT '父任务ID',
  `variable` json NULL COMMENT '附属变量json存储',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_by` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_by` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_task_piid`(`process_instance_id`) USING BTREE,
  INDEX `idx_process_task_name`(`task_name`) USING BTREE,
  INDEX `idx_process_task_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_task_actor
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_task_actor`;
CREATE TABLE `wa_wf_process_task_actor`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `process_task_id` bigint(20) NOT NULL COMMENT '流程任务ID',
  `actor_id` bigint(20) NOT NULL COMMENT '参与者ID',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_by` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_by` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_task_actor_ptid`(`process_task_id`) USING BTREE,
  INDEX `idx_process_task_actor_aid`(`actor_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务和参与人关系' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_task_actor_history
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_task_actor_history`;
CREATE TABLE `wa_wf_process_task_actor_history`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `process_task_id` bigint(20) NOT NULL COMMENT '流程任务ID',
  `actor_id` bigint(20) NOT NULL COMMENT '参与者ID',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_by` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_by` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_task_actor_ptid`(`process_task_id`) USING BTREE,
  INDEX `idx_process_task_actor_aid`(`actor_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务和参与人关系' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_task_history
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_task_history`;
CREATE TABLE `wa_wf_process_task_history`  (
  `id` bigint(36) NOT NULL COMMENT '主键',
  `process_instance_id` bigint(36) NOT NULL COMMENT '流程实例ID',
  `task_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务名称编码',
  `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务显示名称',
  `task_type` int(11) NULL DEFAULT NULL COMMENT '任务类型(0：主办任务；1：协办任务)',
  `perform_type` int(11) NULL DEFAULT NULL COMMENT '参与类型(0：普通参与；1：会签参与)',
  `task_state` int(11) NULL DEFAULT NULL COMMENT '任务状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
  `operator` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理人',
  `finish_time` bigint(10) NULL DEFAULT NULL COMMENT '任务完成时间',
  `expire_time` bigint(20) NULL DEFAULT NULL COMMENT '任务期待完成时间',
  `form_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理表单KEY',
  `task_parent_id` bigint(20) NULL DEFAULT NULL COMMENT '父任务ID',
  `variable` json NULL COMMENT '附属变量json存储',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `create_by` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_by` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_task_piid`(`process_instance_id`) USING BTREE,
  INDEX `idx_process_task_name`(`task_name`) USING BTREE,
  INDEX `idx_process_task_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_process_type
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_process_type`;
CREATE TABLE `wa_wf_process_type`  (
  `id` bigint(20) NOT NULL COMMENT '主键',
  `pid` bigint(20) NULL DEFAULT 0 COMMENT '父id',
  `icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'icon',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分组名称',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `enabled` tinyint(3) NULL DEFAULT 1 COMMENT '1启用 0禁用',
  `create_time` bigint(20) NOT NULL COMMENT '创建时间',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_user` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  `create_user` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `delete_time` bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
  `remark` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '模型分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_task_run_data
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_task_run_data`;
CREATE TABLE `wa_wf_task_run_data`  (
  `id` bigint(20) NOT NULL COMMENT 'ID',
  `type` int(11) NULL DEFAULT NULL COMMENT '处理类型',
  `task_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '任务名称',
  `bean_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'webman名称',
  `biz_id` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '业务id',
  `variable` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '扩展属性JSON',
  `result` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '执行结果',
  `allow_max_error_count` int(11) NULL DEFAULT NULL COMMENT '最大执行失败次数',
  `error_count` int(11) NULL DEFAULT 0 COMMENT '运行失败次数',
  `next_execution_time` int(11) NULL DEFAULT NULL COMMENT '下次执行时间',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '任务运行中的数据' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_task_run_history_data
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_task_run_history_data`;
CREATE TABLE `wa_wf_task_run_history_data`  (
  `id` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ID',
  `type` int(11) NULL DEFAULT NULL COMMENT '任务类型',
  `task_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '任务名称',
  `bean_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'webman名称',
  `biz_id` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '业务id',
  `variable` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '扩展属性JSON',
  `task_state` int(255) NULL DEFAULT NULL COMMENT '0 结束  1活动',
  `result` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '最大执行失败次数',
  `error_count` int(11) NULL DEFAULT NULL COMMENT '运行失败次数',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '任务运行的历史数据' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wa_wf_test
-- ----------------------------
DROP TABLE IF EXISTS `wa_wf_test`;
CREATE TABLE `wa_wf_test`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '总价',
  `itemlist` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '请购清单',
  `business_no` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '关联流程单据编号',
  `state` int(11) NULL DEFAULT 10 COMMENT '审批状态',
  `applicat` varchar(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '申请人',
  `delete_time` int(11) NULL DEFAULT NULL COMMENT '是否删除',
  `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of wa_wf_crontab
-- ----------------------------
INSERT INTO `wa_wf_crontab` VALUES (8, NULL, '调用远程链接', 1, 4, '{\"month\":null,\"week\":null,\"day\":null,\"hour\":null,\"minute\":\"10\",\"second\":null}', '*/10 * * * *', 'http://www.baidu.com', 22, 1738825653, 0, 1713749636, 0, 1);

-- ----------------------------
-- Records of wa_wf_process_type
-- ----------------------------
INSERT INTO `wa_wf_process_type` VALUES (249852146785460224, 0, NULL, '行政123', 0, 1, 1733823096, 1734168686, 1, 1, NULL, NULL);
INSERT INTO `wa_wf_process_type` VALUES (249854145362927614, 0, '', '行政', 0, 1, 1706087406, 1737643576, 1, 1, NULL, '');
INSERT INTO `wa_wf_process_type` VALUES (249854145362927616, 0, '', '行政123', 0, 1, 1733823335, 1734168686, 1, 1, NULL, NULL);
INSERT INTO `wa_wf_process_type` VALUES (249854145362927617, 0, '', '业务流程', 0, 1, 1706172538, 1706172538, 1, 1, NULL, '');
INSERT INTO `wa_wf_process_type` VALUES (249854145362927618, 0, '', 'OA', 10, 1, 1706087453, 1737643458, 1, 1, NULL, '');
INSERT INTO `wa_wf_process_type` VALUES (249854145362927619, 0, '', '人事', 0, 1, 1706087412, 1706171963, 1, 1, NULL, '');
INSERT INTO `wa_wf_process_type` VALUES (249854145362927620, 0, '', '集团流程库', -1, 1, 1706172341, 1735975519, 1, 1, NULL, '');
INSERT INTO `wa_wf_process_type` VALUES (249854145362927621, 0, '', '总务', 0, 1, 1706088738, 1738832371, 1, 1, NULL, '');
INSERT INTO `wa_wf_process_type` VALUES (249854145362927622, 0, '', '其他', 0, 1, 1706172567, 1706172567, 1, 1, NULL, '');

SET FOREIGN_KEY_CHECKS = 1;
