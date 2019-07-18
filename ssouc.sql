CREATE DATABASE IF NOT EXISTS `ssouc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ssouc`;

CREATE TABLE `app` (
  `id` int(11) NOT NULL,
  `app_id` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `main_page` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_url` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0废弃1开放2内部3审核中',
  `is_show` int(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '类型',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '内容',
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '记录用户名',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `create_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0.0.0.0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `type` int(1) NOT NULL DEFAULT '1',
  `is_show` int(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `menu` (`id`, `father_id`, `name`, `icon`, `uri`, `type`, `is_show`, `create_time`, `update_time`) VALUES
(1, 0, '系统管理', 'gears', '', 1, 1, '2018-02-18 04:46:23', '2018-03-02 13:09:30'),
(2, 1, '用户列表', 'user-circle-o', 'admin/user/list', 1, 1, '2018-02-18 04:46:23', ''),
(3, 1, '角色列表', 'users', 'admin/role/list', 1, 1, '2018-02-18 04:46:23', ''),
(4, 1, '菜单管理', 'bars', 'admin/menu/list', 1, 1, '2018-02-18 04:46:23', ''),
(5, 1, '操作记录列表', 'list-alt', 'admin/sys/log/list', 1, 1, '2018-02-18 04:46:23', ''),
(8, 1, '修改系统参数', 'gear', 'admin/sys/setting/list', 1, 1, '2018-03-01 21:16:51', '2019-03-17 22:30:14'),
(10, 0, '示例页面', 'file', '', 1, 1, '2018-03-14 06:39:43', '2018-03-14 22:42:10'),
(12, 10, '空白页', 'file-o', 'show/blank', 1, 1, '2018-03-14 06:41:59', '0000-00-00 00:00:00'),
(17, 0, '常用链接', 'link', '', 1, 1, '2019-03-23 15:49:14', '0000-00-00 00:00:00'),
(18, 17, '图标库', 'circle-o', 'show/jumpout/http%3A%2F%2Fwww.fontawesome.com.cn%2Ffaicons%2F', 1, 1, '2019-03-23 15:53:45', '0000-00-00 00:00:00'),
(19, 17, '数据库管理', 'circle-o', 'show/jumpout/https%3A%2F%2Fwww.xshgzs.com%2FdbAdmin%2F', 1, 1, '2019-03-24 00:20:50', '0000-00-00 00:00:00'),
(20, 17, 'SSO用户中心', 'circle-o', 'show/jumpout/https%3A%2F%2Fssouc.xshgzs.com', 1, 1, '2019-03-24 00:22:09', '0000-00-00 00:00:00'),
(21, 17, 'Github仓库', 'circle-o', 'show/jumpout//https%3A%2F%2Fgithub.com%2FSmallOyster%2FRBAC-CodeIgniter', 1, 1, '2019-05-25 15:20:31', '2019-05-26 09:41:50'),
(22, 0, '应用 App', 'unlock', '', 1, 1, '2019-01-05 03:34:05', '2019-01-05 11:34:05'),
(23, 22, '应用列表', 'window-restore', 'app/list', 1, 1, '2019-01-05 03:34:28', '2019-01-19 08:18:24'),
(24, 22, '登录记录（内部）', 'ban', '#', 1, 1, '2019-01-05 03:34:44', '2019-01-19 13:46:31'),
(25, 22, '应用管理', 'gears', '', 1, 1, '2019-01-05 03:35:24', '2019-01-05 11:35:24'),
(26, 25, '应用管理', 'list-alt', 'app/manageList', 1, 1, '2019-01-05 03:36:28', '2019-01-23 13:52:45'),
(27, 25, '申请新增应用', 'plus-circle', 'app/applyNew', 1, 1, '2019-01-05 03:38:22', '2019-01-23 13:52:21'),
(28, 25, '审核应用申请', 'check-square-o', 'app/audit', 1, 1, '2019-01-05 03:40:44', '2019-01-23 13:52:32');

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `receiver` text COLLATE utf8_unicode_ci NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `praise` int(11) NOT NULL DEFAULT '0',
  `read_count` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `qr_login_token` (
  `id` int(11) NOT NULL,
  `ticket` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_id` char(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '-1取消0过期1正常2已被扫3成功4无权',
  `session_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.0.0.0',
  `expire_time` bigint(20) NOT NULL COMMENT 'Unix时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role` (
  `id` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色名称',
  `remark` text COLLATE utf8_unicode_ci COMMENT '备注',
  `is_default` int(1) NOT NULL DEFAULT '0' COMMENT '是否为默认角色',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='角色表';

INSERT INTO `role` (`id`, `name`, `remark`, `is_default`, `create_time`, `update_time`) VALUES
('g3sa86', '普通用户', '', 1, '2019-05-17 14:02:49', '2019-07-18 10:41:40'),
('1p2wx4', '超级管理员', '拥有全部权限', 0, '2018-02-18 01:33:20', '2019-03-17 22:06:08');

CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL,
  `role_id` varchar(6) COLLATE utf8_unicode_ci NOT NULL COMMENT '角色ID',
  `menu_id` int(11) NOT NULL COMMENT '菜单ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `role_permission` (`id`, `role_id`, `menu_id`) VALUES
(157, '1p2wx4', 1),
(158, '1p2wx4', 2),
(159, '1p2wx4', 3),
(160, '1p2wx4', 4),
(161, '1p2wx4', 5),
(162, '1p2wx4', 8),
(163, '1p2wx4', 10),
(164, '1p2wx4', 12),
(165, '1p2wx4', 17),
(166, '1p2wx4', 18),
(167, '1p2wx4', 19),
(168, '1p2wx4', 20),
(169, '1p2wx4', 21),
(170, '1p2wx4', 22),
(171, '1p2wx4', 23),
(172, '1p2wx4', 24),
(173, '1p2wx4', 25),
(174, '1p2wx4', 26),
(175, '1p2wx4', 27),
(176, '1p2wx4', 28),
(177, 'g3sa86', 10),
(178, 'g3sa86', 12),
(179, 'g3sa86', 22),
(180, 'g3sa86', 23);

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `chinese_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` varchar(19) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `setting` (`id`, `name`, `chinese_name`, `value`, `create_time`, `update_time`) VALUES
(1, 'sessionPrefix', 'Session名称前缀', 'OTSSOV2_', '2018-03-05 03:55:19', '2019-07-18 10:38:45'),
(2, 'systemName', '系统名称', '生蚝科技统一身份认证平台V2.0', '2018-03-05 03:55:19', '2019-07-18 10:38:55'),
(3, 'apiPath', 'API接口目录', 'https://ssouc.xshgzs.com/api/', '2019-02-23 09:29:55', '2019-07-18 10:39:00'),
(8, 'SSOUCAppId', 'SSO用户中心AppId', 'otsso_0e4d2dbq0ak9fbu6dc6', '2019-01-25 18:39:45', '2019-02-03 01:44:00');

CREATE TABLE `third_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '对应user表的id',
  `method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '第三方英文名称小写（可选github/workwechat）',
  `third_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '第三方用户ID',
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
  `role_id` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色ID',
  `status` int(1) NOT NULL DEFAULT '2',
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `extra_param` varchar(16113) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{}' COMMENT '扩展字段，用JSON储存',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` varchar(19) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `app`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_id` (`app_id`);

ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `login_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `qr_login_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket` (`ticket`);

ALTER TABLE `role`
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `id` (`id`);

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
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `login_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `qr_login_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `role_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `third_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
