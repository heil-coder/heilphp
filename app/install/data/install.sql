-- -----------------------------
-- HeilPHP 
-- Date : 2018-02-10 06:36
-- -----------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------
-- Table structure for `heilphp_config`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_config`;
CREATE TABLE `heilphp_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NULL  COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `heilphp_config`
-- -----------------------------
INSERT INTO `heilphp_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '1', '', '网站标题前台显示标题', '1518215910', '1518215910', '1', 'HeilPHP内容管理框架', '0');
INSERT INTO `heilphp_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '2', '网站描述', '1', '', '网站搜索引擎描述', '1518215910', '1518215910', '1', 'HeilPHP内容管理框架', '1');
INSERT INTO `heilphp_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1518215910', '1518215910', '1', 'ThinkPHP,OneThink', '8');
INSERT INTO `heilphp_config` VALUES ('4', 'WEB_SITE_CLOSE', '4', '关闭站点', '1', '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', '1518215910', '1518215910', '1', '1', '1');
INSERT INTO `heilphp_config` VALUES ('10', 'WEB_SITE_ICP', '1', '网站备案号', '1', '', '设置在网站底部显示的备案号，如“琼ICP备16001170号-2“', '1518215910', '1518215910', '1', '', '9');


-- -----------------------------
-- Table structure for `heilphp_member`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_member`;
CREATE TABLE `heilphp_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码md5(字典排序)',
  `salt` char(10) NOT NULL DEFAULT '' COMMENT '密码salt',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '手机',
  `nickname` char(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别(0:未知/保密 1:男 2:女)',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(15) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='会员表';


-- -----------------------------
-- Table structure for `heilphp_auth_extend`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_auth_extend`;
CREATE TABLE `heilphp_auth_extend` (
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';


-- -----------------------------
-- Table structure for `heilphp_auth_group`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_auth_group`;
CREATE TABLE `heilphp_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(30) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '组类型',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态 第2位(0:未删除 1:已删除) 第1位(0:禁用 1:可用)',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `heilphp_auth_group`
-- -----------------------------
INSERT INTO `heilphp_auth_group` VALUES ('1', 'admin', '1', '默认用户组', '', '1', '1,2,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,81,82,83,84,86,87,88,89,90,91,92,93,94,95,96,97,100,102,103,105,106');
INSERT INTO `heilphp_auth_group` VALUES ('2', 'admin', '1', '测试用户', '测试用户', '1', '1,2,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,82,83,84,88,89,90,91,92,93,96,97,100,102,103,195');

-- -----------------------------
-- Table structure for `heilphp_auth_group_access`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_auth_group_access`;
CREATE TABLE `heilphp_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- -----------------------------
-- Table structure for `heilphp_auth_rule`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_auth_rule`;
CREATE TABLE `heilphp_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '规则状态 第2位(0:未删除 1:已删除) 第1位(0:禁用 1:可用)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=217 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Table structure for `heilphp_menu`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_menu`;
CREATE TABLE `heilphp_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏 0:否 1:是',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=122 DEFAULT CHARSET=utf8;
