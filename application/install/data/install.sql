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
INSERT INTO `heilphp_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1518215910', '1518215910', '1', 'ThinkPHP,HeilPHP', '8');
INSERT INTO `heilphp_config` VALUES ('4', 'WEB_SITE_CLOSE', '4', '关闭站点', '1', '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', '1518215910', '1518215910', '1', '1', '1');
INSERT INTO `heilphp_config` VALUES ('9', 'WEB_SITE_ICP', '1', '网站备案号', '1', '', '设置在网站底部显示的备案号，如“琼ICP备16001170号-2“', '1518215910', '1518215910', '1', '', '9');
INSERT INTO `heilphp_config` VALUES ('10', 'CONFIG_TYPE_LIST', '3', '配置类型列表', '4', '', '主要用于数据解析和页面表单的生成', '1378898976', '1379235348', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', '2');
INSERT INTO `heilphp_config` VALUES ('11', 'DOCUMENT_POSITION', '3', '文档推荐位', '2', '', '文档推荐位，推荐到多个位置KEY值相加即可', '1379053380', '1379235329', '1', '1:列表推荐\r\n2:频道推荐\r\n4:首页推荐', '3');
INSERT INTO `heilphp_config` VALUES ('12', 'DOCUMENT_DISPLAY', '3', '文档可见性', '2', '', '文章可见性仅影响前台显示，后台不收影响', '1379056370', '1379235322', '1', '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', '4');
INSERT INTO `heilphp_config` VALUES ('20', 'CONFIG_GROUP_LIST', '3', '配置分组', '4', '', '配置分组', '1379228036', '1384418383', '1', '1:基本\r\n2:内容\r\n3:用户\r\n4:系统', '4');
INSERT INTO `heilphp_config` VALUES ('21', 'HOOKS_TYPE', '3', '钩子的类型', '4', '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', '1379313397', '1379313407', '1', '1:视图\r\n2:控制器', '6');
INSERT INTO `heilphp_config` VALUES ('23', 'OPEN_DRAFTBOX', '4', '是否开启草稿功能', '2', '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', '1379484332', '1379484591', '1', '1', '1');
INSERT INTO `heilphp_config` VALUES ('24', 'DRAFT_AOTOSAVE_INTERVAL', '0', '自动保存草稿时间', '2', '', '自动保存草稿的时间间隔，单位：秒', '1379484574', '1386143323', '1', '60', '2');
INSERT INTO `heilphp_config` VALUES ('25', 'LIST_ROWS', '0', '后台每页记录数', '2', '', '后台数据每页显示记录数', '1379503896', '1380427745', '1', '10', '10');
INSERT INTO `heilphp_config` VALUES ('26', 'USER_ALLOW_REGISTER', '4', '是否允许用户注册', '3', '0:关闭注册\r\n1:允许注册', '是否开放用户注册', '1379504487', '1379504580', '1', '1', '3');
INSERT INTO `heilphp_config` VALUES ('27', 'CODEMIRROR_THEME', '4', '预览插件的CodeMirror主题', '4', '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', '1379814385', '1384740813', '1', 'ambiance', '3');
INSERT INTO `heilphp_config` VALUES ('28', 'DATA_BACKUP_PATH', '1', '数据库备份根路径', '4', '', '路径必须以 / 结尾', '1381482411', '1381482411', '1', './Data/', '5');
INSERT INTO `heilphp_config` VALUES ('29', 'DATA_BACKUP_PART_SIZE', '0', '数据库备份卷大小', '4', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '1381482488', '1381729564', '1', '20971520', '7');
INSERT INTO `heilphp_config` VALUES ('30', 'DATA_BACKUP_COMPRESS', '4', '数据库备份文件是否启用压缩', '4', '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1381729544', '1', '1', '9');
INSERT INTO `heilphp_config` VALUES ('31', 'DATA_BACKUP_COMPRESS_LEVEL', '4', '数据库备份文件压缩级别', '4', '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1381713408', '1', '9', '10');
INSERT INTO `heilphp_config` VALUES ('32', 'DEVELOP_MODE', '4', '开启开发者模式', '4', '0:关闭\r\n1:开启', '是否开启开发者模式', '1383105995', '1383291877', '1', '1', '11');
INSERT INTO `heilphp_config` VALUES ('33', 'ALLOW_VISIT', '3', '不受限控制器方法', '0', '', '', '1386644047', '1386644741', '1', '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture', '0');
INSERT INTO `heilphp_config` VALUES ('34', 'DENY_VISIT', '3', '超管专限控制器方法', '0', '', '仅超级管理员可访问的控制器方法', '1386644141', '1386644659', '1', '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:Authmanager/updateRules\r\n7:Authmanager/tree', '0');
INSERT INTO `heilphp_config` VALUES ('36', 'ADMIN_ALLOW_IP', '2', '后台允许访问IP', '4', '', '多个用逗号分隔，如果不配置表示不限制IP访问', '1387165454', '1387165553', '1', '', '12');


-- -----------------------------
-- Table structure for `heilphp_member`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_member`;
CREATE TABLE `heilphp_member` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `nickname` char(30) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别(0:未知/保密 1:男 2:女)',
  `birthday` date NOT NULL DEFAULT '1000-01-01' COMMENT '生日',
  `qq` char(15) NOT NULL DEFAULT '' COMMENT 'qq号',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '会员状态',
  `delete_time` bigint(10) unsigned DEFAULT NULL COMMENT '删除时间',
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
  `rules` text  COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态 0:禁用 1:可用',
  `delete_time` bigint(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `heilphp_auth_group`
-- -----------------------------
INSERT INTO `heilphp_auth_group` VALUES ('1', 'admin', '1', '默认用户组', '','','1',null);
INSERT INTO `heilphp_auth_group` VALUES ('2', 'admin', '1', '测试用户', '测试用户', '','1',null);

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `heilphp_menu`
-- -----------------------------
INSERT INTO `heilphp_menu` VALUES ('1', '首页', '0', '1', 'Index/index', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('2', '内容', '0', '2', 'Article/index', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('3', '文档列表', '2', '0', 'article/index', '1', '', '内容', '0','1');
INSERT INTO `heilphp_menu` VALUES ('4', '新增', '3', '0', 'article/add', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('5', '编辑', '3', '0', 'article/edit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('6', '改变状态', '3', '0', 'article/setStatus', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('7', '保存', '3', '0', 'article/update', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('8', '保存草稿', '3', '0', 'article/autoSave', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('9', '移动', '3', '0', 'article/move', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10', '复制', '3', '0', 'article/copy', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('11', '粘贴', '3', '0', 'article/paste', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('12', '导入', '3', '0', 'article/batchOperate', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('13', '回收站', '2', '0', 'article/recycle', '1', '', '内容', '0','1');
INSERT INTO `heilphp_menu` VALUES ('14', '还原', '13', '0', 'article/permit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('15', '清空', '13', '0', 'article/clear', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('16', '用户', '0', '3', 'User/index', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('17', '用户信息', '16', '0', 'User/index', '0', '', '用户管理', '0','1');
INSERT INTO `heilphp_menu` VALUES ('18', '新增用户', '17', '0', 'User/add', '0', '添加新用户', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('19', '用户行为', '16', '0', 'User/action', '0', '', '行为管理', '0','1');
INSERT INTO `heilphp_menu` VALUES ('20', '新增用户行为', '19', '0', 'User/addaction', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('21', '编辑用户行为', '19', '0', 'User/editaction', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('22', '保存用户行为', '19', '0', 'User/saveAction', '0', '\"用户->用户行为\"保存编辑和新增的用户行为', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('23', '变更行为状态', '19', '0', 'User/setStatus', '0', '\"用户->用户行为\"中的启用,禁用和删除权限', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('24', '禁用会员', '17', '0', 'User/changeStatus?method=forbidUser', '0', '\"用户->用户信息\"中的禁用', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('25', '启用会员', '17', '0', 'User/changeStatus?method=resumeUser', '0', '\"用户->用户信息\"中的启用', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('26', '删除会员', '17', '0', 'User/changeStatus?method=deleteUser', '0', '\"用户->用户信息\"中的删除', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('27', '权限管理', '16', '0', 'Authmanage/index', '0', '', '用户管理', '0','1');
INSERT INTO `heilphp_menu` VALUES ('28', '删除', '27', '0', 'Authmanage/changeStatus?method=deleteGroup', '0', '删除用户组', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('29', '禁用', '27', '0', 'Authmanage/changeStatus?method=forbidGroup', '0', '禁用用户组', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('30', '恢复', '27', '0', 'Authmanage/changeStatus?method=resumeGroup', '0', '恢复已禁用的用户组', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('31', '新增', '27', '0', 'Authmanage/createGroup', '0', '创建新的用户组', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('32', '编辑', '27', '0', 'Authmanage/editGroup', '0', '编辑用户组名称和描述', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('33', '保存用户组', '27', '0', 'Authmanage/writeGroup', '0', '新增和编辑用户组的\"保存\"按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('34', '授权', '27', '0', 'Authmanage/group', '0', '\"后台 \\ 用户 \\ 用户信息\"列表页的\"授权\"操作按钮,用于设置用户所属用户组', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('35', '访问授权', '27', '0', 'Authmanage/access', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"访问授权\"操作按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('36', '成员授权', '27', '0', 'Authmanage/user', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"成员授权\"操作按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('37', '解除授权', '27', '0', 'Authmanage/removeFromGroup', '0', '\"成员授权\"列表页内的解除授权操作按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('38', '保存成员授权', '27', '0', 'Authmanage/addToGroup', '0', '\"用户信息\"列表页\"授权\"时的\"保存\"按钮和\"成员授权\"里右上角的\"添加\"按钮)', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('39', '分类授权', '27', '0', 'Authmanage/category', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"分类授权\"操作按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('40', '保存分类授权', '27', '0', 'Authmanage/addToCategory', '0', '\"分类授权\"页面的\"保存\"按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('41', '模型授权', '27', '0', 'Authmanage/modelauth', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"模型授权\"操作按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('42', '保存模型授权', '27', '0', 'Authmanage/addToModel', '0', '\"分类授权\"页面的\"保存\"按钮', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('43', '扩展', '0', '7', 'Addons/index', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('44', '插件管理', '43', '1', 'Addons/index', '0', '', '扩展', '0','1');
INSERT INTO `heilphp_menu` VALUES ('45', '创建', '44', '0', 'Addons/create', '0', '服务器上创建插件结构向导', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('46', '检测创建', '44', '0', 'Addons/checkForm', '0', '检测插件是否可以创建', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('47', '预览', '44', '0', 'Addons/preview', '0', '预览插件定义类文件', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('48', '快速生成插件', '44', '0', 'Addons/build', '0', '开始生成插件结构', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('49', '设置', '44', '0', 'Addons/config', '0', '设置插件配置', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('50', '禁用', '44', '0', 'Addons/disable', '0', '禁用插件', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('51', '启用', '44', '0', 'Addons/enable', '0', '启用插件', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('52', '安装', '44', '0', 'Addons/install', '0', '安装插件', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('53', '卸载', '44', '0', 'Addons/uninstall', '0', '卸载插件', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('54', '更新配置', '44', '0', 'Addons/saveconfig', '0', '更新插件配置处理', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('55', '插件后台列表', '44', '0', 'Addons/adminList', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('56', 'URL方式访问插件', '44', '0', 'Addons/execute', '0', '控制是否有权限通过url访问插件控制器方法', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('57', '钩子管理', '43', '2', 'Addons/hooks', '0', '', '扩展', '0','1');
INSERT INTO `heilphp_menu` VALUES ('58', '模型管理', '68', '3', 'modelmanage/index', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('59', '新增', '58', '0', 'modelmanage/add', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('60', '编辑', '58', '0', 'modelmanage/edit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('61', '改变状态', '58', '0', 'modelmanage/setStatus', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('62', '保存数据', '58', '0', 'modelmanage/update', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('63', '属性管理', '68', '0', 'Attribute/index', '1', '网站属性配置。', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('64', '新增', '63', '0', 'Attribute/add', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('65', '编辑', '63', '0', 'Attribute/edit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('66', '改变状态', '63', '0', 'Attribute/setStatus', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('67', '保存数据', '63', '0', 'Attribute/update', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('68', '系统', '0', '4', 'Config/group', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('69', '网站设置', '68', '1', 'Config/group', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('70', '配置管理', '68', '4', 'Config/index', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('71', '编辑', '70', '0', 'Config/edit', '0', '新增编辑和保存配置', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('72', '删除', '70', '0', 'Config/del', '0', '删除配置', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('73', '新增', '70', '0', 'Config/add', '0', '新增配置', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('74', '保存', '70', '0', 'Config/save', '0', '保存配置', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('75', '菜单管理', '68', '5', 'Menu/index', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('76', '导航管理', '68', '6', 'Channel/index', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('77', '新增', '76', '0', 'Channel/add', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('78', '编辑', '76', '0', 'Channel/edit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('79', '删除', '76', '0', 'Channel/del', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('80', '分类管理', '68', '2', 'Category/index', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('81', '编辑', '80', '0', 'Category/edit', '0', '编辑和保存栏目分类', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('82', '新增', '80', '0', 'Category/add', '0', '新增栏目分类', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('83', '删除', '80', '0', 'Category/remove', '0', '删除栏目分类', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('84', '移动', '80', '0', 'Category/operate/type/move', '0', '移动栏目分类', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('85', '合并', '80', '0', 'Category/operate/type/merge', '0', '合并栏目分类', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('86', '备份数据库', '68', '0', 'Datamanage/index?type=export', '0', '', '数据备份', '0','1');
INSERT INTO `heilphp_menu` VALUES ('87', '备份', '86', '0', 'Datamanage/export', '0', '备份数据库', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('88', '优化表', '86', '0', 'Datamanage/optimize', '0', '优化数据表', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('89', '修复表', '86', '0', 'Datamanage/repair', '0', '修复数据表', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('90', '还原数据库', '68', '0', 'Datamanage/index?type=import', '0', '', '数据备份', '0','1');
INSERT INTO `heilphp_menu` VALUES ('91', '恢复', '90', '0', 'Datamanage/import', '0', '数据库恢复', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('92', '删除', '90', '0', 'Datamanage/del', '0', '删除备份文件', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('93', '其他', '0', '5', 'other', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('96', '新增', '75', '0', 'Menu/add', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('98', '编辑', '75', '0', 'Menu/edit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('106', '行为日志', '16', '0', 'Action/log', '0', '', '行为管理', '0','1');
INSERT INTO `heilphp_menu` VALUES ('108', '修改密码', '16', '0', 'User/editPassword', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('109', '修改昵称', '16', '0', 'User/editNickname', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('110', '查看行为日志', '106', '0', 'action/detail', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('111', '删除行为日志', '106', '0', 'action/remove', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('112', '新增数据', '58', '0', 'think/add', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('113', '编辑数据', '58', '0', 'think/edit', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('114', '导入', '75', '0', 'Menu/import', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('115', '生成', '58', '0', 'modelmanage/generate', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('116', '新增钩子', '57', '0', 'Addons/addHook', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('117', '编辑钩子', '57', '0', 'Addons/edithook', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('118', '文档排序', '3', '0', 'Article/sort', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('119', '排序', '70', '0', 'Config/sort', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('120', '排序', '75', '0', 'Menu/sort', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('121', '排序', '76', '0', 'Channel/sort', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('122', '数据列表', '58', '0', 'think/lists', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('123', '审核列表', '3', '0', 'Article/examine', '1', '', '', '0','1');

INSERT INTO `heilphp_menu` VALUES ('10001', 'SEO设置', '68', '10001', 'seo/index', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10002', '新增', '10001', '0', 'seo/add', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10003', '编辑', '10001', '0', 'seo/edit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10004', '启用', '10001', '0', 'seo/changeStatus?method=resume', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10005', '禁用', '10001', '0', 'seo/changeStatus?method=forbid', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10006', '删除', '10001', '0', 'seo/changeStatus?method=delete', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10007', '排序', '10001', '0', 'seo/sort', '0', '', '', '0','1');

INSERT INTO `heilphp_menu` VALUES ('10101', '广告位', '68', '10101', 'adPosition/index', '0', '', '系统设置', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10102', '新增', '10101', '0', 'adPosition/add', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10103', '编辑', '10101', '0', 'adPosition/edit', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10104', '启用', '10101', '0', 'adPosition/changeStatus?method=resume', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10105', '禁用', '10101', '0', 'adPosition/changeStatus?method=forbid', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10106', '删除', '10101', '0', 'adPosition/changeStatus?method=delete', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10107', '排序', '10101', '0', 'adPosition/sort', '1', '', '', '0','1');

INSERT INTO `heilphp_menu` VALUES ('10201', '广告', '68', '0', 'ad/index', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10202', '新增', '10201', '0', 'ad/add', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10203', '编辑', '10201', '0', 'ad/edit', '0', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10204', '启用', '10201', '0', 'ad/changeStatus?method=resume', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10205', '禁用', '10201', '0', 'ad/changeStatus?method=forbid', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10206', '删除', '10201', '0', 'ad/changeStatus?method=delete', '1', '', '', '0','1');
INSERT INTO `heilphp_menu` VALUES ('10207', '排序', '10201', '0', 'ad/sort', '1', '', '', '0','1');

-- -----------------------------
-- Table structure for `heilphp_addons`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_addons`;
CREATE TABLE `heilphp_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- -----------------------------
-- Records of `heilphp_addons`
-- -----------------------------
INSERT INTO `heilphp_addons` VALUES ('2', 'SiteStat', '站点统计信息', '统计站点的基础信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"1\",\"display\":\"1\",\"status\":\"0\"}', 'thinkphp', '0.1', '1379512015', '0');
INSERT INTO `heilphp_addons` VALUES ('3', 'DevTeam', '开发团队信息', '开发团队成员信息', '1', '{\"title\":\"OneThink\\u5f00\\u53d1\\u56e2\\u961f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379512022', '0');
INSERT INTO `heilphp_addons` VALUES ('4', 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379512036', '0');
INSERT INTO `heilphp_addons` VALUES ('15', 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1383126253', '0');
INSERT INTO `heilphp_addons` VALUES ('16', 'Uploader', '文件上传工具', '用于文件上传', '1', '', 'HeilPHP', '0.1', '1536277835', '0');

INSERT INTO `heilphp_addons` VALUES ('17', 'MapBuilder', '地图构建器', '用于构建第三方地图', '1', '', 'HeilPHP', '0.1', '1536752372', '0');

-- -----------------------------
-- Table structure for `heilphp_hooks`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_hooks`;
CREATE TABLE `heilphp_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NULL  COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `heilphp_hooks`
-- -----------------------------
INSERT INTO `heilphp_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '', '1');
INSERT INTO `heilphp_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop', '1');
INSERT INTO `heilphp_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', 'Attachment', '1');
INSERT INTO `heilphp_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'Attachment,SocialComment', '1');
INSERT INTO `heilphp_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', '', '1');
INSERT INTO `heilphp_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', 'Attachment', '1');
INSERT INTO `heilphp_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor', '1');
INSERT INTO `heilphp_hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin', '1');
INSERT INTO `heilphp_hooks` VALUES ('13', 'adminIndex', '首页小格子个性化显示', '1', '1382596073', 'SiteStat,SystemInfo,DevTeam', '1');
INSERT INTO `heilphp_hooks` VALUES ('14', 'topicComment', '评论提交方式扩展钩子。', '1', '1380163518', 'Editor', '1');
INSERT INTO `heilphp_hooks` VALUES ('16', 'app_begin', '应用开始', '2', '1384481614', '', '1');
INSERT INTO `heilphp_hooks` VALUES ('17', 'fileUploader', '文件上传工具初始化钩子', '1', '1384481614','Uploader', '1');
INSERT INTO `heilphp_hooks` VALUES ('18', 'getCoordinate', '获取地理位置坐标钩子', '1', '1536752443','MapBuilder', '1');

-- -----------------------------
-- Table structure for `heilphp_action`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_action`;
CREATE TABLE `heilphp_action` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` varchar(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NULL  COMMENT '行为规则',
  `log` text NULL  COMMENT '日志规则',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `delete_time` bigint(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

-- -----------------------------
-- Records of `heilphp_action`
-- -----------------------------
INSERT INTO `heilphp_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user|get_nickname]在[time|time_format]登录了后台', '1', '1', '1387181220',null);
INSERT INTO `heilphp_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', '2', '0', '1380173180',null);
INSERT INTO `heilphp_action` VALUES ('3', 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', '2', '1', '1383285646',null);
INSERT INTO `heilphp_action` VALUES ('4', 'add_document', '发表文档', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', '[user|get_nickname]在[time|time_format]发表了一篇文章。\r\n表[model]，记录编号[record]。', '2', '0', '1386139726',null);
INSERT INTO `heilphp_action` VALUES ('5', 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', '2', '0', '1383285551',null);
INSERT INTO `heilphp_action` VALUES ('6', 'update_config', '更新配置', '新增或修改或删除配置', '', '', '1', '1', '1383294988',null);
INSERT INTO `heilphp_action` VALUES ('7', 'update_model', '更新模型', '新增或修改模型', '', '', '1', '1', '1383295057',null);
INSERT INTO `heilphp_action` VALUES ('8', 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', '1', '1', '1383295963',null);
INSERT INTO `heilphp_action` VALUES ('9', 'update_channel', '更新导航', '新增或修改或删除导航', '', '', '1', '1', '1383296301',null);
INSERT INTO `heilphp_action` VALUES ('10', 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', '1', '1', '1383296392',null);
INSERT INTO `heilphp_action` VALUES ('11', 'update_category', '更新分类', '新增或修改或删除分类', '', '', '1', '1', '1383296765',null);

-- -----------------------------
-- Table structure for `heilphp_action_log`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_action_log`;
CREATE TABLE `heilphp_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- -----------------------------
-- Table structure for `heilphp_model`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_model`;
CREATE TABLE `heilphp_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text NULL  COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text NULL  COMMENT '属性列表（表的字段）',
  `attribute_alias` varchar(255) NOT NULL DEFAULT '' COMMENT '属性别名定义',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text NULL  COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `engine_type` varchar(25) NOT NULL DEFAULT 'MyISAM' COMMENT '数据库引擎',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='文档模型表';

-- -----------------------------
-- Records of `heilphp_model`
-- -----------------------------
INSERT INTO `heilphp_model` VALUES ('1', 'document', '基础文档', '0', '', '1', '{\"1\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\"]}', '1:基础', '', '','', '', '', 'id:编号\r\ntitle:标题:[EDIT]\r\ntype:类型\r\nupdate_time:最后更新\r\nstatus:状态\r\nview:浏览\r\nid:操作:[EDIT]|编辑,[DELETE]|删除', '0', '', '', '1383891233', '1384507827', '1', 'MyISAM');
INSERT INTO `heilphp_model` VALUES ('2', 'article', '文章', '1', '', '1', '{\"1\":[\"3\",\"24\",\"2\",\"5\"],\"2\":[\"9\",\"13\",\"19\",\"10\",\"12\",\"16\",\"17\",\"26\",\"20\",\"14\",\"11\",\"25\"]}', '1:基础,2:扩展', '','', '', '', '', '', '0', '', '', '1383891243', '1387260622', '1', 'MyISAM');
INSERT INTO `heilphp_model` VALUES ('3', 'download', '下载', '1', '', '1', '{\"1\":[\"3\",\"28\",\"30\",\"32\",\"2\",\"5\",\"31\"],\"2\":[\"13\",\"10\",\"27\",\"9\",\"12\",\"16\",\"17\",\"19\",\"11\",\"20\",\"14\",\"29\"]}', '1:基础,2:扩展', '', '','', '', '', '', '0', '', '', '1383891252', '1387260449', '1', 'MyISAM');

-- -----------------------------
-- Table structure for `heilphp_attribute`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_attribute`;
CREATE TABLE `heilphp_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `validate_rule` varchar(255) NOT NULL DEFAULT '',
  `validate_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `error_info` varchar(100) NOT NULL DEFAULT '',
  `validate_type` varchar(25) NOT NULL DEFAULT '',
  `auto_rule` varchar(100) NOT NULL DEFAULT '',
  `auto_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auto_type` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
    KEY `model_id` (`model_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='模型属性表';

-- -----------------------------
-- Records of `heilphp_attribute`
-- -----------------------------
INSERT INTO `heilphp_attribute` VALUES ('1', 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', '0', '', '1', '0', '1', '1383891233', '1384508362','', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('2', 'name', '标识', 'char(40) NOT NULL ', 'string', '', '同一根节点下标识不重复', '1', '', '1', '0', '1', '1383891233', '1383894743', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('3', 'title', '标题', 'char(80) NOT NULL ', 'string', '', '文档标题', '1', '', '1', '0', '1', '1383891233', '1383894778', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('4', 'category_id', '所属分类', 'int(10) unsigned NOT NULL ', 'string', '', '', '0', '', '1', '0', '1', '1383891233', '1384508336', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('5', 'description', '描述', 'char(140) NOT NULL ', 'textarea', '', '', '1', '', '1', '0', '1', '1383891233', '1383894927', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('6', 'root', '根节点', 'int(10) unsigned NOT NULL ', 'num', '0', '该文档的顶级文档编号', '0', '', '1', '0', '1', '1383891233', '1384508323', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('7', 'pid', '所属ID', 'int(10) unsigned NOT NULL ', 'num', '0', '父文档编号', '0', '', '1', '0', '1', '1383891233', '1384508543', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('8', 'model_id', '内容模型ID', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '该文档所对应的模型', '0', '', '1', '0', '1', '1383891233', '1384508350', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('9', 'type', '内容类型', 'tinyint(3) unsigned NOT NULL ', 'select', '2', '', '1', '1:目录\r\n2:主题\r\n3:段落', '1', '0', '1', '1383891233', '1384511157', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('10', 'position', '推荐位', 'smallint(5) unsigned NOT NULL ', 'checkbox', '0', '多个推荐则将其推荐值相加', '1', '[DOCUMENT_POSITION]', '1', '0', '1', '1383891233', '1383895640', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('11', 'link_id', '外链', 'int(10) unsigned NOT NULL ', 'num', '0', '0-非外链，大于0-外链ID,需要函数进行链接与编号的转换', '1', '', '1', '0', '1', '1383891233', '1383895757', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('12', 'cover_id', '封面', 'int(10) unsigned NOT NULL ', 'picture', '0', '0-无封面，大于0-封面图片ID，需要函数处理', '1', '', '1', '0', '1', '1383891233', '1384147827', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('13', 'display', '可见性', 'tinyint(3) unsigned NOT NULL ', 'radio', '1', '', '1', '0:不可见\r\n1:所有人可见', '1', '0', '1', '1383891233', '1386662271', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `heilphp_attribute` VALUES ('14', 'deadline', '截至时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '0-永久有效', '1', '', '1', '0', '1', '1383891233', '1387163248', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `heilphp_attribute` VALUES ('15', 'attach', '附件数量', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '', '0', '', '1', '0', '1', '1383891233', '1387260355', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `heilphp_attribute` VALUES ('16', 'view', '浏览量', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '1', '0', '1', '1383891233', '1383895835', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('17', 'comment', '评论数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '1', '0', '1', '1383891233', '1383895846', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('18', 'extend', '扩展统计字段', 'int(10) unsigned NOT NULL ', 'num', '0', '根据需求自行使用', '0', '', '1', '0', '1', '1383891233', '1384508264', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('19', 'level', '优先级', 'int(10) unsigned NOT NULL ', 'num', '0', '越高排序越靠前', '1', '', '1', '0', '1', '1383891233', '1383895894', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('20', 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', '1', '', '1', '0', '1', '1383891233', '1383895903', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('21', 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', '0', '', '1', '0', '1', '1383891233', '1384508277', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('22', 'status', '数据状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', '0', '-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿', '1', '0', '1', '1383891233', '1384508496', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('23', 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', '0', '0:html\r\n1:ubb\r\n2:markdown', '2', '0', '1', '1383891243', '1384511049', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('24', 'content', '文章内容', 'text NOT NULL ', 'editor', '', '', '1', '', '2', '0', '1', '1383891243', '1383896225', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('25', 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '参照display方法参数的定义', '1', '', '2', '0', '1', '1383891243', '1383896190', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('26', 'bookmark', '收藏数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '2', '0', '1', '1383891243', '1383896103', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('27', 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', '0', '0:html\r\n1:ubb\r\n2:markdown', '3', '0', '1', '1383891252', '1387260461', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `heilphp_attribute` VALUES ('28', 'content', '下载详细描述', 'text NOT NULL ', 'editor', '', '', '1', '', '3', '0', '1', '1383891252', '1383896438', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('29', 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '', '1', '', '3', '0', '1', '1383891252', '1383896429', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('30', 'file_id', '文件ID', 'int(10) unsigned NOT NULL ', 'file', '0', '需要函数处理', '1', '', '3', '0', '1', '1383891252', '1383896415', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('31', 'download', '下载次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '3', '0', '1', '1383891252', '1383896380', '', '0', '', '', '', '0', '');
INSERT INTO `heilphp_attribute` VALUES ('32', 'size', '文件大小', 'bigint(20) unsigned NOT NULL ', 'num', '0', '单位bit', '1', '', '3', '0', '1', '1383891252', '1383896371', '', '0', '', '', '', '0', '');

-- -----------------------------
-- Table structure for `heilphp_category`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_category`;
CREATE TABLE `heilphp_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `template_index` varchar(100) NOT NULL DEFAULT '' COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '列表绑定模型',
  `model_sub` varchar(100) NOT NULL DEFAULT '' COMMENT '子文档绑定模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `allow_publish` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text NULL  COMMENT '扩展设置',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '数据状态',
  `icon` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  `groups` varchar(255) NOT NULL DEFAULT '' COMMENT '分组定义',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- -----------------------------
-- Records of `heilphp_category`
-- -----------------------------
INSERT INTO `heilphp_category` VALUES ('1', 'blog', '博客', '0', '0', '10', '', '', '', '', '', '', '', '2,3','2', '2,1', '0', '0', '1', '0', '0', '1', '', '1379474947', '1382701539', '1', '0','');
INSERT INTO `heilphp_category` VALUES ('2', 'default_blog', '默认分类', '1', '1', '10', '', '', '', '', '', '', '', '2,3','2', '2,1,3', '0', '1', '1', '0', '1', '1', '', '1379475028', '1386839751', '1', '0','');

-- -----------------------------
-- Table structure for `heilphp_document`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_document`;
CREATE TABLE `heilphp_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` varchar(80) NOT NULL DEFAULT '' COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属分类',
  `group_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '所属分组',
  `description` varchar(140) NOT NULL DEFAULT '' COMMENT '描述',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `display` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `deadline` bigint(10) unsigned DEFAULT NULL COMMENT '截止时间',
  `attach` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优先级',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '数据状态',
  `delete_time` bigint(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_category_status` (`category_id`,`status`),
  KEY `idx_status_type_pid` (`status`,`uid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表';

-- -----------------------------
-- Records of `heilphp_document`
-- -----------------------------
INSERT INTO `heilphp_document` VALUES ('1', '1', '', 'HeilPHP0.01开发版发布', '2', '0','升级OneThink核心为ThinkPHP5.1', '0', '0', '2', '2', '0', '0', '0', '1', null, '0', '8', '0', '0', '0', '1406001413', '1406001413', '1',null);

-- -----------------------------
-- Table structure for `heilphp_document_article`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_document_article`;
CREATE TABLE `heilphp_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表';

-- -----------------------------
-- Records of `heilphp_document_article`
-- -----------------------------
INSERT INTO `heilphp_document_article` VALUES ('1', '0', '<h1>\r\n	HeilPHP0.01.1开发版发布&nbsp;\r\n</h1>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>HeilPHP是一个开源的内容管理框架，基于最新的ThinkPHP5.1版本和OneThink1.1版本开发，提供更方便、更安全的WEB应用开发体验，采用了全新的架构设计和命名空间机制，融合了模块化、驱动化和插件化的设计理念于一体，开启了国内WEB应用傻瓜式开发的新潮流。&nbsp;</strong> \r\n</p>\r\n<h2>\r\n	主要特性：\r\n</h2>\r\n<p>\r\n	1. 基于ThinkPHP最新5.1版本。\r\n</p>\r\n<p>\r\n	2. 模块化：全新的架构和模块化的开发机制，便于灵活扩展和二次开发。&nbsp;\r\n</p>\r\n<p>\r\n	3. 文档模型/分类体系：通过和文档模型绑定，以及不同的文档类型，不同分类可以实现差异化的功能，轻松实现诸如资讯、下载、讨论和图片等功能。\r\n</p>\r\n<p>\r\n	4. 开源免费：HeilPHP遵循Apache2开源协议,免费提供使用。&nbsp;\r\n</p>\r\n<p>\r\n	5. 用户行为：支持自定义用户行为，可以对单个用户或者群体用户的行为进行记录及分享，为您的运营决策提供有效参考数据。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>&nbsp;HeilPHP集成了一个完善的后台管理体系和前台模板标签系统，让你轻松管理数据和进行前台网站的标签式开发。&nbsp;</strong> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<h2>\r\n	后台主要功能：\r\n</h2>\r\n<p>\r\n	1. 用户Passport系统\r\n</p>\r\n<p>\r\n	2. 配置管理系统&nbsp;\r\n</p>\r\n<p>\r\n	3. 权限控制系统\r\n</p>\r\n<p>\r\n	4. 后台建模系统&nbsp;\r\n</p>\r\n<p>\r\n	5. 多级分类系统&nbsp;\r\n</p>\r\n<p>\r\n	6. 用户行为系统&nbsp;\r\n</p>\r\n<p>\r\n	7. 钩子和插件系统\r\n</p>\r\n<p>\r\n	8. 系统日志系统&nbsp;\r\n</p>\r\n<p>\r\n	9. 数据备份和还原\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	&nbsp;	<br />\r\n</p>\r\n<p>\r\n	<strong>HeilPHP开发团队 2018</strong> \r\n</p>', '', '0');

-- -----------------------------
-- Table structure for `heilphp_document_download`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_document_download`;
CREATE TABLE `heilphp_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表';

-- -----------------------------
-- Table structure for `heilphp_url`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_url`;
CREATE TABLE `heilphp_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` varchar(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表';


-- -----------------------------
-- Table structure for `heilphp_channel`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_channel`;
CREATE TABLE `heilphp_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` varchar(30) NOT NULL COMMENT '频道标题',
  `url` varchar(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT="频道表";

-- -----------------------------
-- Records of `heilphp_channel`
-- -----------------------------
INSERT INTO `heilphp_channel` VALUES ('1', '0', '首页', 'Index/index', '1', '1379475111', '1379923177', '1', '0');
INSERT INTO `heilphp_channel` VALUES ('2', '0', '博客', 'Article/index?category=blog', '2', '1379475131', '1379483713', '1', '0');
INSERT INTO `heilphp_channel` VALUES ('3', '0', '官网', 'http://www.heilphp.com', '3', '1379475154', '1387163458', '1', '1');

-- -----------------------------
-- Table structure for `heilphp_ucenter_admin`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_ucenter_admin`;
CREATE TABLE `heilphp_ucenter_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';


-- -----------------------------
-- Table structure for `heilphp_ucenter_app`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_ucenter_app`;
CREATE TABLE `heilphp_ucenter_app` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `title` varchar(30) NOT NULL COMMENT '应用名称',
  `url` varchar(100) NOT NULL COMMENT '应用URL',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT '应用IP',
  `auth_key` varchar(100) NOT NULL DEFAULT '' COMMENT '加密KEY',
  `sys_login` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '同步登陆',
  `allow_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '允许访问的IP',
  `create_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '应用状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用表';


-- -----------------------------
-- Table structure for `heilphp_ucenter_member`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_ucenter_member`;
CREATE TABLE `heilphp_ucenter_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) DEFAULT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码md5(字典排序)',
  `salt` char(10) DEFAULT NULL COMMENT '密码salt',
  `email` char(32) DEFAULT NULL COMMENT '用户邮箱',
  `mobile` char(15) DEFAULT NULL COMMENT '用户手机',
  `reg_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` bigint(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `mobile` (`mobile`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- -----------------------------
-- Table structure for `heilphp_ucenter_setting`
-- -----------------------------
DROP TABLE IF EXISTS `heilphp_ucenter_setting`;
CREATE TABLE `heilphp_ucenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';


--
-- 表的结构 `heilphp_picture`
--

DROP TABLE IF EXISTS `heilphp_picture`;
CREATE TABLE IF NOT EXISTS `heilphp_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `type` varchar(50) NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` bigint(10) unsigned NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 COMMENT = '图片表';


--
-- 表的结构 `heilphp_file`
--

DROP TABLE IF EXISTS `heilphp_file`;
CREATE TABLE IF NOT EXISTS `heilphp_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `name` varchar(50) NULL COMMENT '原始文件名',
  `savename` varchar(50) NULL COMMENT '保存文件名',
  `savepath` varchar(255) NULL COMMENT '文件保存路径',
  `ext` char(6) NULL COMMENT '文件后缀',
  `mime` char(40) NULL COMMENT '文件mime类型',
  `size` bigint(10) NULL COMMENT '文件大小',
  `md5` char(32) NULL COMMENT '文件MD5',
  `sha1` char(40) NULL COMMENT '文件sha1编码',
  `location` tinyint(1) unsigned NULL COMMENT '文件保存位置 0-本地,1-FTP',
  `create_time` bigint(10) unsigned NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100000 COMMENT = '文件表';


--
-- 表的结构 `heilphp_seo`
--

DROP TABLE IF EXISTS `heilphp_seo`;
CREATE TABLE IF NOT EXISTS `heilphp_seo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `title` varchar(50) NULL COMMENT '设置说明',
  `module` varchar(50) NULL COMMENT '模块',
  `controller` varchar(50) NULL COMMENT '控制器',
  `action` varchar(50) NULL COMMENT '方法',
  `seo_title` text NULL COMMENT 'SEO标题',
  `seo_keywords` text NULL COMMENT 'SEO关键词',
  `seo_description` text NULL COMMENT 'SEO描述',
  `description` text NULL COMMENT 'SEO变量说明',
  `create_time` bigint(10) unsigned NULL COMMENT '创建时间',
  `update_time` bigint(10) unsigned NULL COMMENT '更新时间',
  `sort` int(10) unsigned NULL COMMENT '排序',
  `status` tinyint(1) unsigned NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT = '搜索引擎优化表';

--
-- 表的结构 `heilphp_ad_position`
--

DROP TABLE IF EXISTS `heilphp_ad_position`;
CREATE TABLE IF NOT EXISTS `heilphp_ad_position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `title` varchar(80) DEFAULT NULL COMMENT '广告位名称',
  `name` varchar(80) DEFAULT NULL COMMENT '广告位标识',
  `type` tinyint(1) unsigned DEFAULT NULL COMMENT '广告位展示方式 0.单图 1.多图 2.文字链接 3.代码',
  `width` char(20) DEFAULT NULL COMMENT '广告位置宽度',
  `height` char(20) DEFAULT NULL COMMENT '广告位置高度',
  `margin` char(20) DEFAULT NULL COMMENT '外部边距',
  `padding` char(20) DEFAULT NULL COMMENT '内部边距',
  `pos` char(20) DEFAULT NULL COMMENT '位置标识',
  `style` tinyint(1) unsigned DEFAULT NUll COMMENT '广告样式',
  `theme` varchar(50) DEFAULT NUll COMMENT '适用主题',
  `create_time` bigint(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULl DEFAULT 1 COMMENT '状态（0：禁用，1：启用）',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `delete_time` bigint(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT = '广告位表';


--
-- 表的结构 `heilphp_ad`
--

DROP TABLE IF EXISTS `heilphp_ad`;
CREATE TABLE IF NOT EXISTS `heilphp_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `title` varchar(80) DEFAULT NULL COMMENT '广告位名称',
  `position` int(10) unsigned NOT NULL COMMENT '广告位id',
  `data` text NOT NULL COMMENT '广告内容',
  `url` varchar(250) DEFAULT NULL COMMENT '链接地址',
  `target` varchar(30) DEFAULT NULL COMMENT '打开位置 "_blank" 等',
  `click_num` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '点击次数',
  `start_time` bigint(10) unsigned DEFAULT NULL COMMENT '开始时间',
  `end_time` bigint(10) unsigned DEFAULT NULL COMMENT '结束时间',
  `create_time` bigint(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` bigint(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULl DEFAULT 1 COMMENT '状态（0：禁用，1：启用）',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `delete_time` bigint(10) unsigned DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT = '广告位表';


