
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for wf_process_cc_instance
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_cc_instance`;
CREATE TABLE `wf_process_cc_instance`  (
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
-- Table structure for wf_process_define
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_define`;
CREATE TABLE `wf_process_define`  (
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
-- Table structure for wf_process_define_favorite
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_define_favorite`;
CREATE TABLE `wf_process_define_favorite`  (
  `id` bigint(20) NOT NULL COMMENT 'ID',
  `user_id` bigint(20) NULL DEFAULT NULL COMMENT '用户ID',
  `process_define_id` bigint(20) NULL DEFAULT NULL COMMENT '流程定义ID',
  `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '流程收藏表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_process_design
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_design`;
CREATE TABLE `wf_process_design`  (
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
-- Table structure for wf_process_design_history
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_design_history`;
CREATE TABLE `wf_process_design_history`  (
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
-- Table structure for wf_process_form
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_form`;
CREATE TABLE `wf_process_form`  (
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
-- Table structure for wf_process_form_history
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_form_history`;
CREATE TABLE `wf_process_form_history`  (
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
-- Table structure for wf_process_instance
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_instance`;
CREATE TABLE `wf_process_instance`  (
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
-- Table structure for wf_process_instance_history
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_instance_history`;
CREATE TABLE `wf_process_instance_history`  (
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
-- Table structure for wf_process_surrogate
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_surrogate`;
CREATE TABLE `wf_process_surrogate`  (
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
-- Table structure for wf_process_task
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_task`;
CREATE TABLE `wf_process_task`  (
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
-- Table structure for wf_process_task_actor
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_task_actor`;
CREATE TABLE `wf_process_task_actor`  (
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
-- Table structure for wf_process_task_actor_history
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_task_actor_history`;
CREATE TABLE `wf_process_task_actor_history`  (
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
-- Table structure for wf_process_task_history
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_task_history`;
CREATE TABLE `wf_process_task_history`  (
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
-- Table structure for wf_process_type
-- ----------------------------
DROP TABLE IF EXISTS `wf_process_type`;
CREATE TABLE `wf_process_type`  (
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
-- Records of wf_process_type
-- ----------------------------
INSERT INTO `wf_process_type` VALUES (249854145362927614, 0, '', '行政', 0, 1, 1706087406, 1737643576, 1, 1, NULL, '');
INSERT INTO `wf_process_type` VALUES (249854145362927617, 0, '', '业务流程', 0, 1, 1706172538, 1706172538, 1, 1, NULL, '');
INSERT INTO `wf_process_type` VALUES (249854145362927618, 0, '', 'OA', 10, 1, 1706087453, 1737643458, 1, 1, NULL, '');
INSERT INTO `wf_process_type` VALUES (249854145362927619, 0, '', '人事', 0, 1, 1706087412, 1706171963, 1, 1, NULL, '');
INSERT INTO `wf_process_type` VALUES (249854145362927620, 0, '', '集团流程库', -1, 1, 1706172341, 1735975519, 1, 1, NULL, '');
INSERT INTO `wf_process_type` VALUES (249854145362927621, 0, '', '总务', 0, 1, 1706088738, 1738832371, 1, 1, NULL, '');
INSERT INTO `wf_process_type` VALUES (249854145362927622, 0, '', '其他', 0, 1, 1706172567, 1706172567, 1, 1, NULL, '');

SET FOREIGN_KEY_CHECKS = 1;
