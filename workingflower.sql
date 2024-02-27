/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3306
 Source Schema         : mldong

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 26/05/2023 16:59:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sys_role
-- ----------------------------
DROP TABLE IF EXISTS `sys_role`;
CREATE TABLE `sys_role`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色名称',
  `role_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '角色标识(唯一)',
  `role_type` int(6) NULL DEFAULT 10 COMMENT '角色类型(10->管理员|ADMIN,20->流程审核员|WORKFLOW)',
  `data_scope` int(6) UNSIGNED NULL DEFAULT 10 COMMENT '数据范围(10->所有数据权限|ALL,20->部门数据权限|DEPT,30->部门及以下数据权限|DEPT_CHILD,40->仅本人数据权限|MYSELF,50->自定义数据权限|CUSTOM)',
  `is_enabled` tinyint(1) NULL DEFAULT 2 COMMENT '是否启用(1->禁用|NO,2->启用|YES)',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` datetime(3) NOT NULL COMMENT '创建时间',
  `update_time` datetime(3) NOT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否删除(1->未删除|NO,2->已删除|YES)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色' ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for sys_role_access
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_access`;
CREATE TABLE `sys_role_access`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` bigint(20) UNSIGNED NOT NULL COMMENT '角色id',
  `access` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '权限标识',
  `create_time` datetime(3) NOT NULL COMMENT '创建时间',
  `update_time` datetime(3) NOT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否删除(1->未删除|NO,2->已删除|YES)',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_sys_role_access`(`access`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色权限关系' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for sys_role_dept
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_dept`;
CREATE TABLE `sys_role_dept`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` bigint(20) UNSIGNED NOT NULL COMMENT '角色id',
  `dept_id` bigint(20) UNSIGNED NOT NULL COMMENT '角色id',
  `create_time` datetime(3) NOT NULL COMMENT '创建时间',
  `update_time` datetime(3) NOT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否删除(1->未删除|NO,2->已删除|YES)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色部门关系表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for sys_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `sys_role_menu`;
CREATE TABLE `sys_role_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `role_id` bigint(20) UNSIGNED NOT NULL COMMENT '角色id',
  `menu_id` bigint(20) UNSIGNED NOT NULL COMMENT '菜单id',
  `create_time` datetime(3) NOT NULL COMMENT '创建时间',
  `update_time` datetime(3) NOT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否删除(1->未删除|NO,2->已删除|YES)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 46 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色菜单关系' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for sys_task
-- ----------------------------
DROP TABLE IF EXISTS `sys_task`;
CREATE TABLE `sys_task`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 禁用 1暂停 2启用',
  `rule` char(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '定时任务规则',
  `type` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '1 执行http请求 2 执行shell 3.mysql',
  `remark` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '任务备注',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `time_out` int(3) NOT NULL DEFAULT 5 COMMENT '执行超时时间',
  `command` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '执行命令',
  `params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '执行参数',
  `http_method` tinyint(1) NULL DEFAULT NULL COMMENT 'http 执行方式 1 get 2. post',
  `delete_time` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_task_log
-- ----------------------------
DROP TABLE IF EXISTS `sys_task_log`;
CREATE TABLE `sys_task_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL COMMENT '任务id',
  `status_code` int(4) NOT NULL COMMENT '使用http状态码  200 成功  其他失败',
  `result_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '返回结果',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1420 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '任务执行日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sys_user
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `real_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '姓名',
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '头像',
  `email` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `mobile_phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `telephone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '电话',
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '密码',
  `salt` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '加盐',
  `sex` int(6) UNSIGNED NULL DEFAULT 1 COMMENT '性别(1->男|MALE,2->女|FEMALE,3->未知|UNKNOWN)',
  `is_locked` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否锁定(2->已锁定|YES,1->未锁定|NO)',
  `dept_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '部门id',
  `post_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '岗位id',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `create_time` datetime(3) NOT NULL COMMENT '创建时间',
  `update_time` datetime(3) NOT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否删除(1->未删除|NO,2->已删除|YES)',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `real_name`(`real_name`) USING BTREE,
  INDEX `user_name`(`user_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户' ROW_FORMAT = COMPACT;

-- ----------------------------
-- Table structure for sys_user_role
-- ----------------------------
DROP TABLE IF EXISTS `sys_user_role`;
CREATE TABLE `sys_user_role`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` bigint(20) UNSIGNED NOT NULL COMMENT '用户id',
  `role_id` bigint(20) UNSIGNED NOT NULL COMMENT '角色id',
  `create_time` datetime(3) NOT NULL COMMENT '创建时间',
  `update_time` datetime(3) NOT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否删除(1->未删除|NO,2->已删除|YES)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户角色关系表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_cc_order
-- ----------------------------
DROP TABLE IF EXISTS `wf_cc_order`;
CREATE TABLE `wf_cc_order`  (
  `order_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程实例ID',
  `actor_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '参与者ID',
  `creator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发起人',
  `create_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '抄送时间',
  `finish_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '完成时间',
  `status` tinyint(1) NULL DEFAULT NULL COMMENT '状态',
  INDEX `IDX_CCORDER_ORDER`(`order_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '抄送实例表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_flow
-- ----------------------------
DROP TABLE IF EXISTS `wf_flow`;
CREATE TABLE `wf_flow`  (
  `id` varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'id',
  `type_id` varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分组ID',
  `process_id` varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '流程定义ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `desc` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '显示名称',
  `state` tinyint(3) UNSIGNED NOT NULL DEFAULT 2 COMMENT '是否禁用(2->已禁用|YES,1->未禁用|NO)',
  `creator` varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '创建人',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `last_update` varchar(36) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上次更新人',
  `last_update_time` datetime(0) NULL DEFAULT NULL COMMENT '上次更新时间',
  `is_deleted` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '是否删除(1->未删除|NO,2->已删除|YES)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `主键`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '*工作流表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_flow_type
-- ----------------------------
DROP TABLE IF EXISTS `wf_flow_type`;
CREATE TABLE `wf_flow_type`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
  `pid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '父id',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分组名称',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `creator` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户ID',
  `create_time` datetime(3) NOT NULL COMMENT '创建时间',
  `update_time` datetime(3) NULL DEFAULT NULL COMMENT '更新时间',
  `is_deleted` tinyint(3) NOT NULL DEFAULT 1 COMMENT '1未删除  2删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '模型分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_hist_order
-- ----------------------------
DROP TABLE IF EXISTS `wf_hist_order`;
CREATE TABLE `wf_hist_order`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键ID',
  `process_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '流程定义ID',
  `order_state` tinyint(1) NOT NULL COMMENT '状态',
  `creator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '发起人',
  `create_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '发起时间',
  `end_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '完成时间',
  `expire_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '期望完成时间',
  `priority` tinyint(1) NULL DEFAULT NULL COMMENT '优先级',
  `parent_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '父流程ID',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '流程实例编号',
  `variable` json NULL COMMENT '附属变量json存储',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `IDX_HIST_ORDER_PROCESSID`(`process_id`) USING BTREE,
  INDEX `IDX_HIST_ORDER_NO`(`order_no`) USING BTREE,
  INDEX `FK_HIST_ORDER_PARENTID`(`parent_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '历史流程实例表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_hist_task
-- ----------------------------
DROP TABLE IF EXISTS `wf_hist_task`;
CREATE TABLE `wf_hist_task`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键ID',
  `order_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '流程实例ID',
  `task_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务名称',
  `display_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务显示名称',
  `node_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '节点类型',
  `task_type` tinyint(1) NOT NULL COMMENT '任务类型',
  `perform_type` tinyint(1) NULL DEFAULT NULL COMMENT '参与类型',
  `task_state` tinyint(1) NOT NULL COMMENT '任务状态',
  `operator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理人',
  `create_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务创建时间',
  `finish_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务完成时间',
  `expire_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务期望完成时间',
  `action_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理url',
  `parent_task_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父任务ID',
  `variable` json NULL COMMENT '附属变量json存储',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `IDX_HIST_TASK_ORDER`(`order_id`) USING BTREE,
  INDEX `IDX_HIST_TASK_TASKNAME`(`task_name`) USING BTREE,
  INDEX `IDX_HIST_TASK_PARENTTASK`(`parent_task_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '历史任务表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_hist_task_actor
-- ----------------------------
DROP TABLE IF EXISTS `wf_hist_task_actor`;
CREATE TABLE `wf_hist_task_actor`  (
  `task_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务ID',
  `actor_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '参与者ID',
  INDEX `IDX_HIST_TASKACTOR_TASK`(`task_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '历史任务参与者表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_model_designer
-- ----------------------------
DROP TABLE IF EXISTS `wf_model_designer`;
CREATE TABLE `wf_model_designer`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
  `model_group_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '分组ID',
  `model_type` int(6) UNSIGNED NOT NULL DEFAULT 10 COMMENT '模型类型(10->流程|PROCESS,20->表单|FORM)',
  `model_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '模型名称',
  `variable` json NULL COMMENT '模板内容',
  `user_id` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户ID',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
  `is_deleted` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '模型设计' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_model_designer_his
-- ----------------------------
DROP TABLE IF EXISTS `wf_model_designer_his`;
CREATE TABLE `wf_model_designer_his`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
  `model_designer_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '模型设计ID',
  `content` longblob NULL COMMENT '模型定义',
  `create_time` datetime(3) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime(3) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_wf_model_d_his_id`(`model_designer_id`) USING BTREE,
  INDEX `idx_wf_mdh_create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '模型设计历史' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_model_group
-- ----------------------------
DROP TABLE IF EXISTS `wf_model_group`;
CREATE TABLE `wf_model_group`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分组名称',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
  `user_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户ID',
  `create_time` datetime(3) NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime(3) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '模型分组' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_order
-- ----------------------------
DROP TABLE IF EXISTS `wf_order`;
CREATE TABLE `wf_order`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键ID',
  `parent_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程ID',
  `process_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '流程定义ID',
  `creator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发起人',
  `create_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '发起时间',
  `expire_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '期望完成时间',
  `last_update_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上次更新时间',
  `last_updator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '上次更新人',
  `priority` int(5) NULL DEFAULT NULL COMMENT '优先级',
  `parent_node_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程依赖的节点名称',
  `order_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程实例编号',
  `variable` json NULL COMMENT '附属变量json存储',
  `version` int(3) NULL DEFAULT NULL COMMENT '版本',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程实例表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_process
-- ----------------------------
DROP TABLE IF EXISTS `wf_process`;
CREATE TABLE `wf_process`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键ID',
  `type` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程类型',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程名称',
  `display_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程显示名称',
  `instance_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '实例url',
  `state` tinyint(1) NULL DEFAULT NULL COMMENT '流程是否可用',
  `content` json NULL COMMENT '流程模型定义',
  `version` int(2) NULL DEFAULT NULL COMMENT '版本',
  `create_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建时间',
  `creator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建人',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `IDX_PROCESS_NAME`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程定义表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_surrogate
-- ----------------------------
DROP TABLE IF EXISTS `wf_surrogate`;
CREATE TABLE `wf_surrogate`  (
  `id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键ID',
  `process_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程名称',
  `operator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '授权人',
  `surrogate` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '代理人',
  `odate` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '操作时间',
  `sdate` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '开始时间',
  `edate` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '结束时间',
  `state` tinyint(1) NULL DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `IDX_SURROGATE_OPERATOR`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '委托代理表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_task
-- ----------------------------
DROP TABLE IF EXISTS `wf_task`;
CREATE TABLE `wf_task`  (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '主键ID',
  `order_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '流程实例ID',
  `task_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '任务名称',
  `display_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '任务显示名称',
  `task_type` tinyint(1) NOT NULL COMMENT '任务类型',
  `task_state` tinyint(1) NOT NULL DEFAULT 1 COMMENT '任务状态',
  `perform_type` tinyint(1) NULL DEFAULT NULL COMMENT '参与类型',
  `operator` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '任务处理人',
  `create_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '任务创建时间',
  `finish_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '任务完成时间',
  `expire_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '任务期望完成时间',
  `action_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '任务处理的url',
  `variable` json NULL COMMENT '附属变量json存储',
  `version` tinyint(1) NULL DEFAULT NULL COMMENT '版本',
  `parent_task_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '父任务ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `IDX_TASK_ORDER`(`order_id`) USING BTREE,
  INDEX `IDX_TASK_TASKNAME`(`task_name`) USING BTREE,
  INDEX `IDX_TASK_PARENTTASK`(`parent_task_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '任务表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for wf_task_actor
-- ----------------------------
DROP TABLE IF EXISTS `wf_task_actor`;
CREATE TABLE `wf_task_actor`  (
  `task_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务ID',
  `actor_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '参与者ID',
  INDEX `IDX_TASKACTOR_TASK`(`task_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '任务参与者表' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
