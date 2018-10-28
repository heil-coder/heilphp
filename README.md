# heilphp
heilphp


##  问题记录
> 安装时判断sae(不可写入)，并做相应处理 暂不考虑
> win,linux 目录斜杠不同带来的兼容性影响问题
> 内容编辑时 时间戳为0时date控件识别为1970-01-01 08:00,与永久有效的说明相悖
> 模型save新增后获取自增主键方式待修改
> config  分组为0的在后台系统配置处不显示，无法修改
> 分类编辑修改子文档绑定模型时action_log('update_category')报错
> 分类编辑 数组参数写入失败
> 内容批量粘贴异常
> 菜单编辑写入失败

> step3 update时未升级语法结构

# 数据返回
> 使用ThinkPHP 5.1 控制器的success()和error()方法返回数据,可以对get、post或ajax请求方式都可以做适合的响应。

# 待处理
> 数据字典category allow_publish 的注释内容确认
> 数据字典category reply_model的注释确认
> 名词解释 频道 的说明
> status 统一配置调整
> 用户行为日志清空后，行为规则周期执行判断会重新计时问题
> get_attribute_type 函数修改可能引起的问题检查
> 模型属性新增、修改后是否正确对模型字段做相应操作检查
> 模型数据新增自动完成
> application/common.php中重写助手函数view(),来实现更自主的模板文件选择,
> 多图、多文件上传

#已处理
> 安装 install.lock保存路径
> url模型编辑函数异常，暂未解决
> 多字段验证唯一,如果其中一个值为null则无法生成正确的验证查询sql语句 (fieldName = null)
```
delete_time为null时需要在模型中加入软删除设置,联合唯一的字段会自动在验证时增加对delete_time的联合验证,非软删除的null字段暂时没有处理办法
```

## 名词解释
| 标题|说明|
| --- |  --- |
| 频道 |暂时理解为首页的推荐的栏目内容| 
| 列表 |栏目列表页| 
| 详情 |内容详情页| 

## 系统SESSION
| 参数| 数据类型 | 默认值 | 注释 |
| --- | ---| --- | --- |
| ADMIN_MENU_LIST | 数组 | -- | 以controller为下标记录当前控制器的后台菜单 ,用户登出时需清除 |

## 系统缓存
| 参数| 数据类型 | 默认值 | 注释 |
| --- | ---| --- | --- |

## 通用返回格式
| 参数| 数据类型 | 默认值 | 注释 |
| --- | ---| --- | --- |
| code  | tinyint | -- | 0：错误 1:成功|
| msg | string | -- | 返回提示信息 |
| url | string | -- | 可选 用于返回url信息 |
| data| |--  | 可选 要返回的数据，可以是字符串、数字、数组等开发场景需要的数据格式 |

请按此格式返回数据，以便于统一的开发。
* * * * *

## 自动时间戳
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| create_time| bigint(10) unsigned|否|无|--|创建时间| 
| update_time| bigint(10) unsigned|否|无|--|更新时间| 

```
被系统统一使用ThinkPHP5的自动时间戳记录创建时间和更新时间
```

## 数据软删除
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| delete_time| bigint(10) unsigned|是|null|--|软删除标记 null:未删除 时间戳:删除时间| 

```
软删除标识字段统一为delete_time,数据表中的唯一字段由于删除引起的唯一冲突问题通过设置联合唯一索引解决，如：UNIQUE KEY un_id_card (id_card,delete_time)
```

![](http://www.thinkphp.cn/Uploads/editor/2016-06-23/576b4732a6e04.png) 

ThinkPHP 5.1 —— 12载初心，你值得信赖的PHP框架
===============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/top-think/framework/badges/quality-score.png?b=5.1)](https://scrutinizer-ci.com/g/top-think/framework/?branch=5.1)
[![Build Status](https://travis-ci.org/top-think/framework.svg?branch=master)](https://travis-ci.org/top-think/framework)
[![Total Downloads](https://poser.pugx.org/topthink/framework/downloads)](https://packagist.org/packages/topthink/framework)
[![Latest Stable Version](https://poser.pugx.org/topthink/framework/v/stable)](https://packagist.org/packages/topthink/framework)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D5.6-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/topthink/framework/license)](https://packagist.org/packages/topthink/framework)

ThinkPHP5.1对底层架构做了进一步的改进，减少依赖，其主要特性包括：

 + 采用容器统一管理对象
 + 支持Facade
 + 注解路由支持
 + 路由跨域请求支持
 + 配置和路由目录独立
 + 取消系统常量
 + 助手函数增强
 + 类库别名机制
 + 增加条件查询
 + 改进查询机制
 + 配置采用二级
 + 依赖注入完善
 + 支持`PSR-3`日志规范
 + 中间件支持（V5.1.6+）
 + Swoole/Workerman支持（V5.1.18+）


> ThinkPHP5的运行环境要求PHP5.6以上。

## 安装

使用composer安装

~~~
composer create-project topthink/think tp
~~~

启动服务

~~~
cd tp
php think run
~~~

然后就可以在浏览器中访问

~~~
http://localhost:8000
~~~

更新框架
~~~
composer update topthink/framework
~~~


## 在线手册

+ [完全开发手册](https://www.kancloud.cn/manual/thinkphp5_1/content)
+ [升级指导](https://www.kancloud.cn/manual/thinkphp5_1/354155) 

## 目录结构

初始的目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           应用目录
│  ├─common             公共模块目录（可以更改）
│  ├─module_name        模块目录
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  │
│  ├─command.php        命令行定义文件
│  ├─common.php         公共函数文件
│  └─tags.php           应用行为扩展定义文件
│
├─config                应用配置目录
│  ├─module_name        模块配置目录
│  │  ├─database.php    数据库配置
│  │  ├─cache           缓存配置
│  │  └─ ...            
│  │
│  ├─app.php            应用配置
│  ├─cache.php          缓存配置
│  ├─cookie.php         Cookie配置
│  ├─database.php       数据库配置
│  ├─log.php            日志配置
│  ├─session.php        Session配置
│  ├─template.php       模板引擎配置
│  └─trace.php          Trace配置
│
├─route                 路由定义目录
│  ├─route.php          路由定义
│  └─...                更多
│
├─public                WEB目录（对外访问目录）
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─thinkphp              框架系统目录
│  ├─lang               语言文件目录
│  ├─library            框架类库目录
│  │  ├─think           Think类库包目录
│  │  └─traits          系统Trait目录
│  │
│  ├─tpl                系统模板目录
│  ├─base.php           基础定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     框架惯例配置文件
│  ├─helper.php         助手函数文件
│  ├─phpunit.xml        phpunit配置文件
│  └─start.php          框架入口文件
│
├─extend                扩展类库目录
├─runtime               应用的运行时目录（可写，可定制）
├─vendor                第三方类库目录（Composer依赖库）
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
~~~

> 可以使用php自带webserver快速测试
> 切换到根目录后，启动命令：php think run

## 命名规范

`ThinkPHP5`遵循PSR-2命名规范和PSR-4自动加载规范，并且注意如下规范：

### 目录和文件

*   目录不强制规范，驼峰和小写+下划线模式均支持；
*   类库、函数文件统一以`.php`为后缀；
*   类的文件名均以命名空间定义，并且命名空间的路径和类库文件所在路径一致；
*   类名和类文件名保持一致，统一采用驼峰法命名（首字母大写）；

### 函数和类、属性命名

*   类的命名采用驼峰法，并且首字母大写，例如 `User`、`UserType`，默认不需要添加后缀，例如`UserController`应该直接命名为`User`；
*   函数的命名使用小写字母和下划线（小写字母开头）的方式，例如 `get_client_ip`；
*   方法的命名使用驼峰法，并且首字母小写，例如 `getUserName`；
*   属性的命名使用驼峰法，并且首字母小写，例如 `tableName`、`instance`；
*   以双下划线“__”打头的函数或方法作为魔法方法，例如 `__call` 和 `__autoload`；

### 常量和配置

*   常量以大写字母和下划线命名，例如 `APP_PATH`和 `THINK_PATH`；
*   配置参数以小写字母和下划线命名，例如 `url_route_on` 和`url_convert`；

### 数据表和字段

*   数据表和字段采用小写加下划线方式命名，并注意字段名不要以下划线开头，例如 `think_user` 表和 `user_name`字段，不建议使用驼峰和中文作为数据表字段命名。

## 参与开发

请参阅 [ThinkPHP5 核心框架包](https://github.com/top-think/framework)。

## 版权信息

ThinkPHP遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2006-2018 by ThinkPHP (http://thinkphp.cn)

All rights reserved。

ThinkPHP® 商标和著作权所有者为上海顶想信息科技有限公司。

更多细节参阅 [LICENSE.txt](LICENSE.txt)

# 数据字典

> 参考[OneThink数据字典](http://document.onethink.cn/manual_1_0.html#onethink_3_3)进行设计。  

## config 系统配置表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| 配置ID,自增主键 |
| name | varchar(30) |否|无|--| 配置名称 |
| type | tinyint(1) |否|0|--| 配置类型（0-数字，1-字符，2-文本，3-数组，4-枚举，5-多选） |
| title | varchar(50) |否|无|--| 配置说明 |
| group | tinyint(1) |否|0|--| 配置分组（0-无分组，1-基本设置）|
| extra | varchar(255) |否|无|--| 配置值|
| remark | varchar(100) |否|无|--| 配置说明 |
| create_time | bigint(10) unsigned |否|0|--| 创建时间 |
| update_time | binint(10) unsigned |否|0|--| 更新时间 |
| status | tinyint(1) |否|0|--| 状态 |	
| value | text |否|无|--| 配置值 |	
| sort | smallint(3) unsigned |否|0|--| 排序 |	


## member 会员信息表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| uid | int(10) unsigned |否|无|是| 用户ID,自增主键 |
| nickname | char(30)  |否|无|--| 昵称|
| sex| tinyint(1) unsigned |否|0|--| 性别(0:未知/保密 1:男 2:女)|
| birthday| date|否|无|--|生日|
| qq| char(15)|否|无|--|qq|
| score| mediumint(8)|否|0|--|用户积分|
| login| int(10)|否|0|--|登录次数|
| reg_ip| bigint(20)|否|0|--|注册IP|
| reg_time| bigint(10) unsigned|否|0|--|注册时间|
| last_login_ip| bigint(20)|否|0|--|最后登录IP|
| last_login_time| bigint(10) unsigned|否|0|--|最后登录时间|
| status| tinyint(1) unsigned |否|0|--|会员状态|
| delete_time| bigint(10) unsigned|是|null|-- 删除时间 如未删除则为null||

## menu 菜单表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| 菜单ID,自增主键 |
| title | varchar(50) |否|无|--| 标题 |
| pid | int(10) unsigned |否| 0 |--| 上级分类ID |
| sort | int(10) unsigned |否| 0 |--| 排序(同级有效) |
| url | varchar(255) |否|无|--| 链接地址 |
| hide | tinyint(1) |否|0|--| 是否隐藏 0:否 1:是|
| tip | varchar(255) |否|无|--| 提示|
| group | varchar(50) |是|无|--| 分组 |
| is_dev | tinyint(1) unsigned |否|0|--| 分组 |
| status | tinyint(1) unsigned |否|0|--| 状态 |

## auth_group 用户组定义表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id|mediumint(8) unsigned| 否|无|是|用户组id，自增主键|
| module| varchar(30)| 否| 无|--|用户组所属模块|
| type| tinyint(1)| 否| 0 |否|组类型|
| title| char(30)| 否|无|--|用户组中文名称|
| description| varchar(80)|--|无|否|描述信息|
| rules| varchar(500)| 否|无|--|用户组拥有的规则id,过个规则使用','隔开|
| status| tinyint(1)|否| 1 |--|用户组状态 0:禁用 1:可用|
| delete_time| bigint(10) unsigned|是|null|-- 删除时间 如未删除则为null||

## auth_group_access 用户用户组关系对应表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| uid| int(10) unsigned| 否| 无|否|用户id|
| group_id| mediumint(8) unsigned| 否| 无|否|用户组id|

## auth_rule 权限规则表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id|mediumint(8) unsigned| 否|无|是|用户组id，自增主键|
| module| varchar(30)| 否| 无|--|用户组所属模块|
| type| tinyint(2)| 否| 1 |--|1:url ; 2:主菜单|
| name| char(80)| 否|无|--|规则唯一英文标识|
| title|char(30)|否|无|--|规则中文描述|
| status| tinyint(1)| 否|0|--|规则状态 第2位(0:未删除 1:已删除) 第1位(0:禁用 1:可用)|
| condition| varchar(300)|否|无|--|规则附加条件|

## auth_extend 权限扩展表 

> 当节点控制无法满足时,需要对权限控制进行扩展。例如：分类的授权即使用该表。  

|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| group_id| mediumint(8) unsigned| 否|无|否|用户组id|
| extend_id| mediumint(8) unsigned| 否|无|否|扩展表中数据的id|
| type| tinyint(1) unsigned| 否|无|否|扩展类型标识 1:栏目分类权限|

> 索引定义：UNIQUE KEYgroup_extend_type(group_id,extend_id,type)  


## hooks 钩子表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|插件id，自增主键| 
| name| varchar(40)|否|''|--|钩子名称| 
| description| text|是|null|--|描述| 
| type| tinyint(1) unsigned|否|1|--|类型 ?| 
| update_time| bigint(10) unsigned|否|0|--|更新时间| 
| addons|varchar(255)|否|''|钩子挂载的插件 ''，''分割|
| status|tinyint(1) unsigned|否|1|状态|

## addons 插件表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|插件id，自增主键| 
| name| varchar(40)|否|无|--|插件名或标识| 
| title| varchar(20)|否|无|--|名称| 
| description| text|否|无|--|插件描述| 
| status| tinyint(1)|否|无|--|状态| 
| config| text|否|无|--|配置| 
| author| varchar(40)|否|''|--|作者| 
| version| varchar(40)|否|''|--|版本号| 
| create_time| bigint(10)|否|0|--|安装时间| 
| has_adminlist| tinyint(1)|否|0|--|是否有后台列表| 

## action 行为表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|行为id，自增主键| 
| name | varchar(30) |否|''|--|行为唯一标识| 
| title| varchar(80) |否|''|--|行为说明| 
| remark| varchar(140) |否|''|--|行为描述| 
| rule | text |是|null|--|行为规则| 
| log| text |是|null|--|日志规则| 
| type| tinyint(1) unsigned|否|1|--|类型 1:系统　2:用户| 
| status| tinyint(1) unsigned|否|0|--|状态| 
| update_time| bigint(10) unsigned|否|0|--|修改时间| 
| delete_time| bigint(10) unsigned|是|null|--|软删除标记 null:未删除 时间戳:删除时间| 

## action_log 行为日志表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|行为日志id，自增主键| 
| action_id|int(10) unsigned |否|0|--|行为id|
| user_id|int(10) unsigned |否|0|--|执行用户id|
| action_ip|bigint(20)|否|无|--|执行行为者id|
| model|varchar(50)|否|''|--|触发行为的表|
| record_id|int(10) unsigned|否|0|--|触发行为的数据id|
| remark| varchar(255)|否|''|--|日志备注|
| status| tinyint(1) unsigned|否|1|--|状态|
| create_time| bigint(10) unsigned|否|0|--|执行行为的时间|

## model 模型表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|模型id，自增主键| 
| name| varchar(30) |否|''|--|模型标识| 
| title| varchar(30) |否|''|--|模型名称| 
| extend| int(10) |否|0|--|继承的模型| 
| relation| varchar(30) |否|''|--|继承与被继承模型的关联字段| 
| need_pk| tinyint(1) unsigned|否|1|--|新建表时是否需要主键字段| 
| field_sort| text|是|null|--|表单字段排序| 
| field_group| varchar(255)|否|1:基础|--|字段分组| 
| attribute_list|text |是|null|--|属性列表（表的字段）| 
| attribute_alias|varchar(255)|否|''|--|属性别名定义| 
| template_list|varchar(100)|否|''|--|列表模板| 
| template_add|varchar(100)|否|''|--|新增模板| 
| template_edit|varchar(100)|否|''|--|编辑模板| 
| list_grid|text|是|null|--|列表定义| 
| list_row|smallint(2) unsigned|否|10|--|列表数据长度| 
| search_key|varchar(50) |否|''|--|默认搜索字段| 
| search_list|varchar(255|否|''|--|高级搜索的字段|
| create_time|bigint(10) unsigned|否|0|--|创建时间|
| update_time|bigint(10) unsigned|否|0|--|更新时间|
| status |tinyint(1) unsigned|否|0|--|状态|
| engine_type|varchar(25)|否|'MyISAM'|--|数据库引擎|

## attribute 模型属性表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|属性id，自增主键| 
| name| varchar(30) |否|''|--|字段名| 
| title| varchar(100) |否|''|--|字段注释| 
| field| varchar(100) |否|''|--|字段定义| 
| type| varchar(20) |否|''|--|数据类型| 
| value| varchar(100) |否|''|--|字段默认值| 
| remark| varchar(100) |否|''|--|备注| 
| is_show| tinyint(1) unsigned|否|1|--|是否显示 0:不显示　1:显示| 
| extra| varchar(255) |否|''|--|参数| 
| model_id| int(10) unsigned|否|0|--|模型id| 
| is_must| tinyint(1) unsigned|否|0|--|是否必填 0:选填　1:必填| 
| status| tinyint(1) unsigned|否|0|--|状态| 
| create_time| bigint(10) unsigned|否|0|--|创建时间| 
| update_time| bigint(10) unsigned|否|0|--|更新时间| 
| validate_rule| varchar(255)|否|''|--|验证规则| 
| validate_time| tinyint(1) unsigned|否|0|--|验证时间 1:新增 2:编辑 3:始终| 
| error_info| varchar(100)|否|''|--|错误提示| 
| validate_type| varchar(25)|否|''|--|验证方式| 
| auto_rule| varchar(100)|否|''|--|自动完成规则| 
| auto_time| tinyint(1) unsigned|否|0|--|自动完成时间 1:新增 2:编辑 3:始终| 
| auto_type| varchar(25)|否|''|--|自动完成方式| 


## category 模型属性表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|分类id，自增主键| 
| name| varchar(30) |否|无|--|标志| 
| title| varchar(50) |否|无|--|标题| 
| pid| int(10) unsigned |否|0|--|上级分类id| 
| sort | int(10) unsigned |否|0|--|排序(同级有效)| 
| list_row| tinyint(3) unsigned |否|10|--|列表每页行数| 
| meta_title| varchar(50) |否|''|--|SEO的网页标题| 
| keywords| varchar(255) |否|''|--|关键字| 
| description| varchar(255) |否|''|--|描述| 
| template_index| varchar(100) |否|''|--|频道页模板| 
| template_lists| varchar(100) |否|''|--|列表页模板| 
| template_detail| varchar(100) |否|''|--|详情页模板| 
| template_edit| varchar(100) |否|''|--|编辑页模板| 
| model| varchar(100) |否|''|--|列表绑定模型| 
| model_sub| varchar(100) |否|''|--|子文档绑定模型| 
| type| varchar(100) |否|''|--|允许发布的内容类型| 
| link_id| int(10) unsigned |否|0|--|外链| 
| allow_publish| tinyint(1) unsigned |否|0|--|是否允许发布内容 非后台管理员的权限设置| 
| display| tinyint(1) unsigned |否|0|--|可见性| 
| reply| tinyint(1) unsigned |否|0|--|是否允许回复| 
| check| tinyint(1) unsigned |否|0|--|发布的文章是否需要审核| 
| reply_model| varchar(100) |否|''|--|回复使用的模型| 
| extend| text |是|null|--|扩展设置| 
| create_time| bigint(10) unsigned |否|0|--|创建时间| 
| update_time| bigint(10) unsigned |否|0|--|更新时间| 
| status| tinyint(1) unsigned |否|0|--|数据状态| 
| icon| int(10) unsigned |否|0|--|分类图标| 
| groups| varchar(255) |否|''|--|分组定义| 

## document 文档模型基础表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|无|是|文档id，自增主键| 
| uid | int(10) unsigned|否|0|--|用户ID| 
| name| varchar(40) |否|''|--|标识| 
| title| varchar(80) |否|''|--|标题| 
| category_id| int(10) unsigned |否|无|--|所属分类| 
| group_id| mediumint(8) unsigned|否|无|--|所属分组| 
| description| varchar(140) |否|''|--|描述| 
| root| int(10) unsigned |否|0|--|根节点| 
| pid| int(10) unsigned |否|0|--|所属ID| 
| model_id| int(10) unsigned |否|0|--|内容模型ID| 
| type| tinyint(3) unsigned |否|2|--|内容类型| 
| position| smallint(5) unsigned |否|0|--|推荐位| 
| link_id| int(10) unsigned |否|0|--|外链| 
| cover_id| int(10) unsigned |否|0|--|封面| 
| display| tinyint(1) unsigned |否|1|--|可见性| 
| deadline| bigint(10) unsigned |否|0|--|截止时间| 
| attach| tinyint(1) unsigned |否|0|--|附件数量| 
| view| int(10) unsigned |否|0|--|浏览量| 
| comment| int(10) unsigned |否|0|--|评论数| 
| extend| int(10) unsigned |否|0|--|扩展统计字段| 
| level| int(10) unsigned |否|0|--|优先级| 
| create_time| bigint(10) unsigned |否|0|--|创建时间| 
| update_time| bigint(10) unsigned |否|0|--|更新时间| 
| status| tinyint(1) unsigned |否|0|--|数据状态| 
| delete_time| bigint(10) unsigned|是|null|-- 删除时间 如未删除则为null||

## document_article 文档模型文章表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|0|--|文档id，主键| 
| parse| tinyint(1) unsigned|否|0|--|内容解析类型| 
| content| text|否|无|--|文章内容| 
| template| varchar(100)|否|''|--|详情页显示模板| 
| bookmark| int(10) unsigned|否|0|--|收藏数| 

## document_download 文档模型下载表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned|否|0|--|文档id，主键| 
| parse| tinyint(3) unsigned|否|0|--|内容解析类型| 
| content| text |否|无|--|下载详细描述| 
| template| varchar(100)|否|''|--|详情页显示模板| 
| file_id| int(10) unsigned|否|0|--|文件ID| 
| download| int(10) unsigned|否|0|--|下载次数| 
| size| bigint(10) unsigned|否|0|--|文件大小| 

## url 链接表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(11) unsigned|否|0|是|链接唯一标识，主键| 
| url | varchar(255) |否|''|--|链接地址规| 
| short| varchar(100) |否|''|--|短网址| 
| status| tinyint(1) unsigned|否|2|--|状态| 
| create_time| bigint(10) unsigned |否|0|--|创建时间| 

## channel 频道表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(11) unsigned|否|0|是|频道唯一标识，主键| 
| pid | int(10) unsigned|否|0|--|上级频道ID| 
| title | varchar(30) |否|无|--|频道标题| 
| url| varchar(30) |否|无|--|频道链接| 
| sort| int(10) unsigned|否|0|--|导航排序| 
| create_time| bigint(10) unsigned |否|0|--|创建时间| 
| update_time| bigint(10) unsigned |否|0|--|更新时间| 
| status| tinyint(1) unsigned|否|0|--|状态| 
| target| tinyint(1) unsigned|否|0|--|新窗口打开| 

## ucenter_member 用户表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| 用户ID,自增主键 |
| username| char(16) |是|null|--| 用户名|
| password| char(32) |是|null|--| 密码 md5(字典排序)|
| salt| char(10) |是|null|--| 密码salt|
| email| varchar(50)| 是|无|--|邮箱|
| mobile| char(15)| 是|无|--|手机|

## ucenter_setting 用户设置表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| 设置ID,自增主键 |
| type | tinyint(1) unsigned |否|0|--| 配置类型（1-用户配置） |
| value| text |否|无|--| 配置数据 |

## picture 图片表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| ID,自增主键 |
| type |varchar(50)|否|无|--| 类型 |
| path |varchar(255)|否|''|--| 路径 |
| url |varchar(255)|否|''|--| 图片链接 |
| md5 |char(32)|否|''|--| 文件md5 |
| sha1 |char(40)|否|''|--| 文件sha1编码 |
| status |tinyint(1)|否|0|--| 状态 |
| create_time |bigint(10) unsigned|是|无|--| 创建时间 |

## file 文件表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| ID,自增主键 |
| name| varchar(50) |是|无|--| 原始文件名 |
| savename| varchar(50) |是|无|--| 保存文件名 |
| savepath| varchar(255) |是|无|--| 文件保存路径|
| ext| char(6) |是|无|--| 文件后缀|
| mime| char(40) |是|无|--| 文件mime类型|
| size| bigint(10) |是|无|--| 文件大小|
| md5| char(32) |是|无|--| 文件MD5|
| sha1| char(40) |是|无|--| 文件sha1编码|
| location| tinyint(1) unsigned |是|无|--|文件保存位置 0-本地,1-FTP|
| create_time| bigint(10) unsigned |是|无|--|上传时间|

## seo 搜索引擎优化表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| ID,自增主键 |
| title| varchar(200) |是|无|--| 设置说明|
| module| varchar(50) |是|无|--|模块|
| controller| varchar(50) |是|无|--|控制器|
| action| varchar(50) |是|无|--|方法|
| seo_title|text|是|无|--|SEO标题|
| seo_keywords|text|是|无|--|SEO关键词|
| seo_description|text|是|无|--|SEO描述|
| description|text|是|无|--|SEO变量说明|
| create_time| bigint(10) unsigned|是|无|--|创建时间|
| update_time| bigint(10) unsigned|是|无|--|更新时间|
| sort|int(10) unsigned|是|无|--|排序|
| status|tinyint(1) unsigned|是|无|--|状态|

## ad_position 广告位表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| ID,自增主键 |
| title |varchar(80) |是|null|--|广告位置名称|
| name | varchar(50) |是|null|--| 广告位标识 |
| type |tinyint(1) unsigned |是|null|--|广告位置展示方式 0.单图 1.多图 2.文字链接 3.代码|
| width |char(20) |是|null|--|广告位置宽度|
| height |char(20) |是|null|--|广告位置高度|
| margin|char(20) |是|null|--|外部边距|
| padding|char(20) |是|null|--|内部边距|
| pos |varchar(50) |是|null|--|位置标识|
| style|tinyint(1) |是|null|--|广告样式|
| theme|varchar(50) |是|null|--|适用主题|
| create_time|bigint(10) unsigned | 是|null|--|创建时间|
| update_time|bigint(10) unsigned | 是|null|--|更新时间|
| status |tinyint(1) unsigned |否|1|--|状态（0：禁用，1：启用）|
| delete_time|bigint(10) unsigned | 是|null|--|删除时间|


## ad 广告表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |否|无|是| ID,自增主键 |
| title |varchar(80) |否|无|--|广告名称|
| position |int(10) unsigned |否|无|--|广告位id|
| data| text |否|无|--|广告内容|
| url|varchar(250)|是|null|--|链接地址|
| target| varchar(30)|是|null|--|打开位置 "_blank" 等|
| click_num| int(10) unsigned|否|0|--|点击次数|
| start_time|bigint(10) unsigned | 是|null|--|开始时间|
| end_time|bigint(10) unsigned | 否|无|--|结束时间|
| create_time|bigint(10) unsigned |是|null|--|创建时间|
| update_time|bigint(10) unsigned |是|null|--|更新时间|
| sort|int(10) unsigned|否|无|--|排序|
| status|tinyint(1) unsigned|否|无|--|状态（0：禁用，1：正常）|
| delete_time|bigint(10) unsigned | 是|null|--|删除时间|
