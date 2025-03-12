SET NAMES utf8;
SET
FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `{{$pk}}admin_admin`;
CREATE TABLE `{{$pk}}admin_admin`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `username`    varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名，登陆使用',
    `password`    varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户密码',
    `nickname`    varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户昵称',
    `status`      tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态：1正常,2禁用 默认1',
    `token`       varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'token',
    `create_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` timestamp NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` timestamp NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='管理表';

DROP TABLE IF EXISTS `{{$pk}}admin_admin_role`;
CREATE TABLE `{{$pk}}admin_admin_role`
(
    `id`       int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `admin_id` int(11) DEFAULT NULL COMMENT '用户ID',
    `role_id`  int(11) DEFAULT NULL COMMENT '角色ID',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='管理-角色中间表';

DROP TABLE IF EXISTS `{{$pk}}admin_admin_log`;
CREATE TABLE `{{$pk}}admin_admin_log`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `uid`         int(11) DEFAULT NULL COMMENT '管理员ID',
    `url`         varchar(255) NOT NULL DEFAULT '' COMMENT '操作页面',
    `desc`        text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '日志内容',
    `ip`          varchar(20)  NOT NULL DEFAULT '' COMMENT '操作IP',
    `user_agent`  text         NOT NULL COMMENT 'User-Agent',
    `create_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='管理员日志';

DROP TABLE IF EXISTS `{{$pk}}admin_admin_permission`;
CREATE TABLE `{{$pk}}admin_admin_permission`
(
    `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `admin_id`      int(11) DEFAULT NULL COMMENT '用户ID',
    `permission_id` int(11) DEFAULT NULL COMMENT '权限ID',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='管理-权限中间表';

DROP TABLE IF EXISTS `{{$pk}}admin_permission`;
CREATE TABLE `{{$pk}}admin_permission`
(
    `id`     int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `pid`    int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
    `title`  varchar(50) DEFAULT NULL COMMENT '名称',
    `href`   varchar(50) NOT NULL COMMENT '地址',
    `icon`   varchar(50) DEFAULT NULL COMMENT '图标',
    `sort`   tinyint(4) NOT NULL DEFAULT '99' COMMENT '排序',
    `type`   tinyint(1) DEFAULT '1' COMMENT '菜单',
    `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
    PRIMARY KEY (`id`),
    KEY      `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 COMMENT='权限表';

INSERT INTO `{{$pk}}admin_permission` (`id`, `pid`, `title`, `href`, `icon`, `sort`, `type`, `status`)
VALUES (1, 0, '后台权限', '', 'layui-icon layui-icon-username', 2, 0, 1),
       (2, 1, '管理员', '/admin.admin/index', '', 1, 1, 1),
       (3, 2, '新增管理员', '/admin.admin/add', '', 1, 1, 1),
       (4, 2, '编辑管理员', '/admin.admin/edit', '', 1, 1, 1),
       (5, 2, '修改管理员状态', '/admin.admin/status', '', 1, 1, 1),
       (6, 2, '删除管理员', '/admin.admin/remove', '', 1, 1, 1),
       (7, 2, '批量删除管理员', '/admin.admin/batchRemove', '', 1, 1, 1),
       (8, 2, '管理员分配角色', '/admin.admin/role', '', 1, 1, 1),
       (9, 2, '管理员分配直接权限', '/admin.admin/permission', '', 1, 1, 1),
       (10, 2, '管理员回收站', '/admin.admin/recycle', '', 1, 1, 1),
       (11, 1, '角色管理', '/admin.role/index', '', 99, 1, 1),
       (12, 11, '新增角色', '/admin.role/add', '', 99, 1, 1),
       (13, 11, '编辑角色', '/admin.role/edit', '', 99, 1, 1),
       (14, 11, '删除角色', '/admin.role/remove', '', 99, 1, 1),
       (15, 11, '角色分配权限', '/admin.role/permission', '', 99, 1, 1),
       (16, 11, '角色回收站', '/admin.role/recycle', '', 99, 1, 1),
       (17, 1, '菜单权限', '/admin.permission/index', '', 99, 1, 1),
       (18, 17, '新增菜单', '/admin.permission/add', '', 99, 1, 1),
       (19, 17, '编辑菜单', '/admin.permission/edit', '', 99, 1, 1),
       (20, 17, '修改菜单状态', '/admin.permission/status', '', 99, 1, 1),
       (21, 17, '删除菜单', '/admin.permission/remove', '', 99, 1, 1),
       (22, 0, '系统管理', '', 'layui-icon layui-icon-set', 3, 0, 1),
       (23, 22, '后台日志', '/admin.admin/log', '', 2, 1, 1),
       (24, 23, '清空管理员日志', '/admin.admin/removeLog', '', 1, 1, 1),
       (25, 22, '系统设置', '/config/index', '', 1, 1, 1),
       (26, 22, '图片管理', '/admin.photo/index', '', 2, 1, 1),
       (27, 26, '新增图片文件夹', '/admin.photo/add', '', 2, 1, 1),
       (28, 26, '删除图片文件夹', '/admin.photo/del', '', 2, 1, 1),
       (29, 26, '图片列表', '/admin.photo/list', '', 2, 1, 1),
       (30, 26, '添加单图', '/admin.photo/addPhoto', '', 2, 1, 1),
       (31, 26, '添加多图', '/admin.photo/addPhotos', '', 2, 1, 1),
       (32, 26, '删除图片', '/admin.photo/remove', '', 2, 1, 1),
       (33, 26, '批量删除图片', '/admin.photo/batchRemove', '', 2, 1, 1),
       (34, 0, '业务流程', '', 'layui-icon layui-icon-set', 99, 0, 1),
       (35, 34, '基础功能', '', '', 99, 0, 1),
       (36, 35, '发起', '/wf.launch/index', '', 99, 1, 1),
       (37, 35, '待办', '/wf.todo/index', '', 99, 1, 1),
       (38, 35, '待阅', '/wf.carbon/index', '', 99, 1, 1),
       (39, 35, '流程定义', '/wf.define/index', '', 99, 1, 1),
       (40, 34, '流程设置', '', '', 99, 0, 1),
       (41, 40, '流程类型', '/wf.category/index', '', 99, 1, 1),
       (42, 40, '表单设计', '/wf.form/index', '', 99, 1, 1),
       (43, 40, '流程设计', '/wf.designer/index', '', 99, 1, 1),
       (44, 40, '委托管理', '/wf.surrogate/index', '', 99, 1, 1),
       (45, 34, '流程查询', '', '', 99, 0, 1),
       (46, 45, '我的申请', '/wf.instance/index', '', 99, 1, 1),
       (47, 45, '我的收藏', '/wf.favorite/index', '', 99, 1, 1),
       (48, 45, '我的已办', '/wf.done/index', '', 99, 1, 1);


DROP TABLE IF EXISTS `{{$pk}}admin_role`;
CREATE TABLE `{{$pk}}admin_role`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `name`        varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci  DEFAULT NULL COMMENT '名称',
    `desc`        varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '描述',
    `create_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` timestamp NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` timestamp NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 COMMENT='角色表';

DROP TABLE IF EXISTS `{{$pk}}admin_role_permission`;
CREATE TABLE `{{$pk}}admin_role_permission`
(
    `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `role_id`       int(11) DEFAULT NULL COMMENT '角色ID',
    `permission_id` int(11) DEFAULT NULL COMMENT '权限ID',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='角色-权限中间表';

INSERT INTO `{{$pk}}admin_role` (`id`, `name`, `desc`, `create_time`, `update_time`, `delete_time`)
VALUES (1, '超级管理员', '拥有所有管理权限', '2020-09-01 11:01:34', '2020-09-01 11:01:34', NULL);

DROP TABLE IF EXISTS `{{$pk}}admin_photo`;
CREATE TABLE `{{$pk}}admin_photo`
(
    `id`          int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `name`        varchar(50) NOT NULL COMMENT '文件名称',
    `href`        varchar(255) DEFAULT NULL COMMENT '文件路径',
    `path`        varchar(30)  DEFAULT NULL COMMENT '路径',
    `mime`        varchar(50) NOT NULL COMMENT 'mime类型',
    `size`        varchar(30) NOT NULL COMMENT '大小',
    `type`        tinyint(1) NOT NULL DEFAULT '1' COMMENT '1本地2阿里云3七牛云',
    `ext`         varchar(10)  DEFAULT NULL COMMENT '文件后缀',
    `create_time` timestamp NULL DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='图片表';



DROP TABLE IF EXISTS `{{$pk}}wf_process_cc_instance`;
CREATE TABLE `{{$pk}}wf_process_cc_instance`
(
    `id`                  bigint(20) NOT NULL COMMENT '主键',
    `process_instance_id` bigint(20) NOT NULL COMMENT '流程实例ID',
    `process_task_id`     bigint(20) NULL DEFAULT NULL COMMENT '任务ID',
    `actor_id`            bigint(20) NOT NULL COMMENT '被抄送人ID',
    `state`               int(11) NULL DEFAULT 0 COMMENT '抄送状态(1:已读；0：未读)',
    `create_time`         bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_by`           bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`         bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_by`           bigint(36) NULL DEFAULT NULL COMMENT '更新用户',
    `remark`              longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX                 `idx_pccins_pinsid`(`process_instance_id`) USING BTREE,
    INDEX                 `idx_pccins_actor_id`(`actor_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程实例抄送' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_define`;
CREATE TABLE `{{$pk}}wf_process_define`
(
    `id`           bigint(20) UNSIGNED NOT NULL COMMENT '主键',
    `type_id`      bigint(20) NULL DEFAULT NULL COMMENT '流程分类',
    `icon`         longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT 'icon',
    `name`         varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '唯一编码',
    `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '显示名称',
    `description`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '流程描述',
    `enabled`      int(11) NULL DEFAULT 0 COMMENT '流程是否可用(1可用；0不可用)',
    `is_active`    tinyint(1) NULL DEFAULT 0 COMMENT '是否活跃版本(1是 0否)',
    `content`      json NULL COMMENT '流程模型定义',
    `version`      float(3, 1
) UNSIGNED NULL DEFAULT 1.0 COMMENT '版本',
  `create_time` bigint(10) NULL DEFAULT NULL COMMENT '创建时间',
  `create_user` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  `update_user` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
  `delete_time` bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_define_name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程定义' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_define_favorite`;
CREATE TABLE `{{$pk}}wf_process_define_favorite`
(
    `id`                bigint(20) NOT NULL COMMENT 'ID',
    `user_id`           bigint(20) NULL DEFAULT NULL COMMENT '用户ID',
    `process_define_id` bigint(20) NULL DEFAULT NULL COMMENT '流程定义ID',
    `create_time`       bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time`       bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `remark`            text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '流程收藏表' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_design`;
CREATE TABLE `{{$pk}}wf_process_design`
(
    `id`           bigint(20) NOT NULL COMMENT '主键',
    `name`         varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '唯一编码',
    `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '显示名称',
    `description`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '描述',
    `type_id`      bigint(20) NULL DEFAULT NULL COMMENT '流程分类',
    `icon`         varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
    `is_deployed`  tinyint(1) NULL DEFAULT 0 COMMENT '是否已部署',
    `create_time`  bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_user`  bigint(36) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`  bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_user`  bigint(36) NULL DEFAULT NULL COMMENT '更新用户',
    `delete_time`  bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
    `remark`       longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX          `idx_process_designer_name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程设计' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_design_history`;
CREATE TABLE `{{$pk}}wf_process_design_history`
(
    `id`                varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
    `process_design_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '流程设计ID',
    `content`           json NULL COMMENT '流程模型定义',
    `create_time`       bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_user`       bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `version`           float(3, 1
) NULL DEFAULT 1.0 COMMENT '版本',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_design_his_pdid`(`process_design_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程设计历史' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_form`;
CREATE TABLE `{{$pk}}wf_process_form`
(
    `id`           bigint(20) NOT NULL COMMENT '主键',
    `name`         varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '唯一编码',
    `display_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '显示名称',
    `description`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '描述',
    `type_id`      bigint(20) NULL DEFAULT NULL COMMENT '流程分类',
    `icon`         varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
    `enabled`      tinyint(1) NULL DEFAULT 0 COMMENT '是否禁用',
    `create_time`  bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_user`  bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`  bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_user`  bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
    `delete_time`  bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
    `remark`       text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX          `idx_process_form_name`(`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '表单设计' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_form_history`;
CREATE TABLE `{{$pk}}wf_process_form_history`
(
    `id`              bigint(20) NOT NULL COMMENT '主键',
    `process_form_id` bigint(20) NOT NULL COMMENT 'ID',
    `content`         json NULL COMMENT '模型定义',
    `create_time`     bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_user`     varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建用户',
    `version`         float(3, 1
) NULL DEFAULT 1.0 COMMENT '版本',
  `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_process_design_his_pdid`(`process_form_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '表单设计历史' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_instance`;
CREATE TABLE `{{$pk}}wf_process_instance`
(
    `id`                varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
    `parent_id`         varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程ID，子流程实例才有值',
    `process_define_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程定义ID',
    `state`             int(11) NULL DEFAULT NULL COMMENT '流程实例状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
    `parent_node_name`  varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程依赖的节点名称',
    `business_no`       varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '业务编号',
    `operator`          varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程发起人',
    `variable`          json NULL COMMENT '附属变量json存储',
    `expire_time`       int(3) NULL DEFAULT NULL COMMENT '期望完成时间',
    `create_time`       int(11) NULL DEFAULT NULL COMMENT '创建时间',
    `create_user`       varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`       int(11) NULL DEFAULT NULL COMMENT '更新时间',
    `update_user`       varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '更新用户',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX               `idx_process_instance_pfid`(`process_define_id`) USING BTREE,
    INDEX               `idx_process_instance_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程实例' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_instance_history`;
CREATE TABLE `{{$pk}}wf_process_instance_history`
(
    `id`                varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '主键',
    `parent_id`         varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程ID，子流程实例才有值',
    `process_define_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程定义ID',
    `state`             int(11) NULL DEFAULT NULL COMMENT '流程实例状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
    `parent_node_name`  varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '父流程依赖的节点名称',
    `business_no`       varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '业务编号',
    `operator`          varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流程发起人',
    `variable`          json NULL COMMENT '附属变量json存储',
    `expire_time`       int(3) NULL DEFAULT NULL COMMENT '期望完成时间',
    `create_time`       int(3) NULL DEFAULT NULL COMMENT '创建时间',
    `create_user`       varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`       int(3) NULL DEFAULT NULL COMMENT '更新时间',
    `update_user`       varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '更新用户',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX               `idx_process_instance_pfid`(`process_define_id`) USING BTREE,
    INDEX               `idx_process_instance_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程实例' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_surrogate`;
CREATE TABLE `{{$pk}}wf_process_surrogate`
(
    `id`                bigint(20) NOT NULL COMMENT '主键',
    `process_define_id` bigint(20) NULL DEFAULT NULL COMMENT '流程定义id',
    `operator`          bigint(20) NOT NULL COMMENT '授权人',
    `surrogate`         bigint(64) NOT NULL COMMENT '代理人',
    `start_time`        bigint(20) NULL DEFAULT NULL COMMENT '授权开始时间',
    `end_time`          bigint(20) NULL DEFAULT NULL COMMENT '授权结束时间',
    `enabled`           tinyint(1) NULL DEFAULT 1 COMMENT '是否启用',
    `create_time`       bigint(10) NULL DEFAULT NULL COMMENT '创建时间',
    `create_by`         bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`       bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_by`         bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程委托代理' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_task`;
CREATE TABLE `{{$pk}}wf_process_task`
(
    `id`                  bigint(36) NOT NULL COMMENT '主键',
    `process_instance_id` bigint(36) NOT NULL COMMENT '流程实例ID',
    `task_name`           varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务名称编码',
    `display_name`        varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务显示名称',
    `task_type`           int(11) NULL DEFAULT NULL COMMENT '任务类型(0：主办任务；1：协办任务)',
    `perform_type`        int(11) NULL DEFAULT NULL COMMENT '参与类型(0：普通参与；1：会签参与)',
    `task_state`          int(11) NULL DEFAULT NULL COMMENT '任务状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
    `operator`            varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理人',
    `finish_time`         bigint(10) NULL DEFAULT NULL COMMENT '任务完成时间',
    `expire_time`         bigint(20) NULL DEFAULT NULL COMMENT '任务期待完成时间',
    `form_key`            varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理表单KEY',
    `task_parent_id`      bigint(20) NULL DEFAULT NULL COMMENT '父任务ID',
    `variable`            json NULL COMMENT '附属变量json存储',
    `create_time`         bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_by`           bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`         bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_by`           bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX                 `idx_process_task_piid`(`process_instance_id`) USING BTREE,
    INDEX                 `idx_process_task_name`(`task_name`) USING BTREE,
    INDEX                 `idx_process_task_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_task_actor`;
CREATE TABLE `{{$pk}}wf_process_task_actor`
(
    `id`              bigint(20) NOT NULL COMMENT '主键',
    `process_task_id` bigint(20) NOT NULL COMMENT '流程任务ID',
    `actor_id`        bigint(20) NOT NULL COMMENT '参与者ID',
    `create_time`     bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_by`       bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`     bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_by`       bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX             `idx_process_task_actor_ptid`(`process_task_id`) USING BTREE,
    INDEX             `idx_process_task_actor_aid`(`actor_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务和参与人关系' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_task_actor_history`;
CREATE TABLE `{{$pk}}wf_process_task_actor_history`
(
    `id`              bigint(20) NOT NULL COMMENT '主键',
    `process_task_id` bigint(20) NOT NULL COMMENT '流程任务ID',
    `actor_id`        bigint(20) NOT NULL COMMENT '参与者ID',
    `create_time`     bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_by`       bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`     bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_by`       bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX             `idx_process_task_actor_ptid`(`process_task_id`) USING BTREE,
    INDEX             `idx_process_task_actor_aid`(`actor_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务和参与人关系' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_task_history`;
CREATE TABLE `{{$pk}}wf_process_task_history`
(
    `id`                  bigint(36) NOT NULL COMMENT '主键',
    `process_instance_id` bigint(36) NOT NULL COMMENT '流程实例ID',
    `task_name`           varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务名称编码',
    `display_name`        varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务显示名称',
    `task_type`           int(11) NULL DEFAULT NULL COMMENT '任务类型(0：主办任务；1：协办任务)',
    `perform_type`        int(11) NULL DEFAULT NULL COMMENT '参与类型(0：普通参与；1：会签参与)',
    `task_state`          int(11) NULL DEFAULT NULL COMMENT '任务状态(10：进行中；20：已完成；30：已撤回；40：强行中止；50：挂起；99：已废弃)',
    `operator`            varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理人',
    `finish_time`         bigint(10) NULL DEFAULT NULL COMMENT '任务完成时间',
    `expire_time`         bigint(20) NULL DEFAULT NULL COMMENT '任务期待完成时间',
    `form_key`            varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '任务处理表单KEY',
    `task_parent_id`      bigint(20) NULL DEFAULT NULL COMMENT '父任务ID',
    `variable`            json NULL COMMENT '附属变量json存储',
    `create_time`         bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `create_by`           bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `update_time`         bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_by`           bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX                 `idx_process_task_piid`(`process_instance_id`) USING BTREE,
    INDEX                 `idx_process_task_name`(`task_name`) USING BTREE,
    INDEX                 `idx_process_task_operator`(`operator`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '流程任务' ROW_FORMAT = DYNAMIC;


DROP TABLE IF EXISTS `{{$pk}}wf_process_type`;
CREATE TABLE `{{$pk}}wf_process_type`
(
    `id`          bigint(20) NOT NULL COMMENT '主键',
    `pid`         bigint(20) NULL DEFAULT 0 COMMENT '父id',
    `icon`        varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'icon',
    `name`        varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '分组名称',
    `sort`        int(11) NULL DEFAULT 0 COMMENT '排序',
    `enabled`     tinyint(3) NULL DEFAULT 1 COMMENT '1启用 0禁用',
    `create_time` bigint(20) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` bigint(20) NULL DEFAULT NULL COMMENT '更新时间',
    `update_user` bigint(20) NULL DEFAULT NULL COMMENT '更新用户',
    `create_user` bigint(20) NULL DEFAULT NULL COMMENT '创建用户',
    `delete_time` bigint(20) NULL DEFAULT NULL COMMENT '是否删除',
    `remark`      longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '模型分组' ROW_FORMAT = DYNAMIC;



INSERT INTO `{{$pk}}wf_process_type` (`id`, `pid`, `icon`, `name`, `sort`, `enabled`)
VALUES (249854145362927614, 0, '', '行政', 0, 1),
       (249854145362927619, 0, '', '人事', 0, 1),
       (249854145362927621, 0, '', '总务', 0, 1),
       (249854145362927622, 0, '', '其他', 0, 1);

