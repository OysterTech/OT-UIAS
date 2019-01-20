CREATE DATABASE IF NOT EXISTS `ssouc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ssouc`;

CREATE TABLE `app` (
  `id` int(11) NOT NULL,
  `app_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_page` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_url` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0废弃1开放2内部3审核中',
  `is_show` int(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `login_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `login_token` (
  `id` int(11) NOT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `user_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `father_id` int(11) NOT NULL COMMENT '父菜单ID（0为主菜单）',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `icon` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '图标名（FA）',
  `uri` text COLLATE utf8_unicode_ci COMMENT '链接URL',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `menu` (`id`, `father_id`, `name`, `icon`, `uri`, `create_time`, `update_time`) VALUES
(1, 0, '通知 Notice', 'bullhorn', NULL, '2019-01-05 11:31:36', '2019-01-05 11:31:36'),
(2, 1, '通知列表', 'list-ul', 'notice/list', '2019-01-05 11:32:18', '2019-01-19 08:17:10'),
(3, 1, '通知管理', 'gears', NULL, '2019-01-05 11:32:51', '2019-01-05 11:32:51'),
(4, 3, '通知列表管理', 'list-alt', 'admin/notice/list', '2019-01-05 11:33:15', '2019-01-19 08:17:19'),
(5, 3, '发布新通知', 'plus-circle', 'admin/notice/publish', '2019-01-05 11:33:43', '2019-01-19 08:17:58'),
(6, 0, '应用 App', 'unlock', NULL, '2019-01-05 11:34:05', '2019-01-05 11:34:05'),
(7, 6, '应用列表', 'window-restore', 'app/list', '2019-01-05 11:34:28', '2019-01-19 08:18:24'),
(8, 6, '登录记录（内部）', 'ban', '#', '2019-01-05 11:34:44', '2019-01-19 13:46:31'),
(9, 6, '应用管理', 'gears', NULL, '2019-01-05 11:35:24', '2019-01-05 11:35:24'),
(10, 9, '应用列表管理', 'list-alt', 'php', '2019-01-05 11:36:28', '2019-01-19 08:16:13'),
(11, 9, '申请新增应用', 'plus-circle', 'php', '2019-01-05 11:38:22', '2019-01-19 08:16:13'),
(12, 9, '审核应用申请', 'check-square-o', 'php', '2019-01-05 11:40:44', '2019-01-19 08:16:13'),
(18, 0, '系统管理 System', 'wrench', NULL, '2019-01-05 11:43:57', '2019-01-05 11:43:57'),
(20, 18, '用户列表', 'user-circle-o', 'admin/user/list', '2019-01-05 11:44:48', '2019-01-19 08:19:33'),
(22, 18, '角色管理', 'users', 'admin/role/list', '2019-01-05 11:45:59', '2019-01-19 08:19:38'),
(23, 18, '菜单管理', 'bars', 'admin/menu/list', '2019-01-05 11:46:18', '2019-01-19 08:19:52'),
(24, 18, '系统配置', 'gears', 'admin/setting/list', '2019-01-05 11:46:40', '2019-01-19 08:20:01');

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `receiver` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `praise` int(11) NOT NULL DEFAULT '0',
  `read_count` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `remark` text COLLATE utf8_unicode_ci COMMENT '备注',
  `is_default` int(1) NOT NULL DEFAULT '0' COMMENT '是否为默认角色',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL COMMENT '角色ID',
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chinese_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `setting` (`id`, `name`, `chinese_name`, `value`, `create_time`, `update_time`) VALUES
(1, 'openReg', '是否开放注册（0关闭1开放）', '0', '2018-07-18 13:09:35', '2018-07-20 11:14:37'),
(2, 'sessionPrefix', 'SESSION前缀', 'OTSSO_', '2019-01-19 01:26:12', '2019-01-19 01:26:12'),
(3, 'apiPath', 'API接口网址', 'https://ssouc.xshgzs.com/api/', '2019-01-19 05:40:31', '2019-01-19 13:24:01'),
(4, 'systemName', '系统名称', '生蚝科技用户中心', '2019-01-19 08:33:54', '2019-01-19 08:33:54');

CREATE TABLE `third_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '对应user表的id',
  `method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '第三方英文名称小写（可选github/workwechat）',
  `third_id` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '第三方用户ID',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` varchar(19) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `union_id` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nick_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salt` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_permission` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `status` int(1) NOT NULL DEFAULT '2',
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_param` varchar(16183) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{}' COMMENT '扩展字段，用JSON储存',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `app`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_id` (`app_id`);

ALTER TABLE `login_log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `login_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `third_user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `union_id` (`union_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `phone` (`phone`);


ALTER TABLE `app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `login_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `login_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `role_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `third_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
