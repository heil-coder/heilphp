# heilphp
heilphp


##  问题记录
> 安装时判断sae(不可写入)，并做相应处理 暂不考虑
> win,linux 目录斜杠不同带来的兼容性影响问题

# 数据返回
> 使用ThinkPHP 5.1 控制器的success()和error()方法返回数据,可以对get、post或ajax请求方式都可以做适合的响应。

## 通用返回格式
| 参数| 数型 | 默认值 | 注释 |
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



ThinkPHP 5.1RC1
===============

ThinkPHP5.1对底层架构做了进一步的改进，减少依赖，其主要特性包括：

 + 采用容器统一管理对象
 + 支持Facade
 + 配置和路由目录独立
 + 取消系统常量
 + 助手函数增强
 + 类库别名机制
 + 增加条件查询
 + 改进查询机制
 + 配置采用二级
 + 依赖注入完善


> ThinkPHP5的运行环境要求PHP5.6以上。


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

> router.php用于php自带webserver支持，可用于快速测试
> 切换到public目录后，启动命令：php -S localhost:8888  router.php
> 上面的目录结构和名称是可以改变的，这取决于你的入口文件和配置参数。

## 升级指导

原有下面系统类库的命名空间需要调整：

* think\App      => think\facade\App （或者 App ）
* think\Cache    => think\facade\Cache （或者 Cache ）
* think\Config   => think\facade\Config （或者 Config ）
* think\Cookie   => think\facade\Cookie （或者 Cookie ）
* think\Debug    => think\facade\Debug （或者 Debug ）
* think\Hook     => think\facade\Hook （或者 Hook ）
* think\Lang     => think\facade\Lang （或者 Lang ）
* think\Log      => think\facade\Log （或者 Log ）
* think\Request  => think\facade\Request （或者 Request ）
* think\Response => think\facade\Reponse （或者 Reponse ）
* think\Route    => think\facade\Route （或者 Route ）
* think\Session  => think\facade\Session （或者 Session ）
* think\Url      => think\facade\Url （或者 Url ）

原有的配置文件config.php 拆分为app.php cache.php 等独立配置文件 放入config目录。
原有的路由定义文件route.php 移动到route目录

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

版权所有Copyright © 2006-2017 by ThinkPHP (http://thinkphp.cn)

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
| username| char(16) |否|无|--| 用户名|
| password| char(32) |否|无|--| 密码 md5(字典排序)|
| salt| char(10) |否|无|--| 密码salt|
| email| varchar(50)| 是|无|--|邮箱|
| mobile| char(15)| 是|无|--|手机|
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
| status| tinyint(1) |否|0|--|会员状态|
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


## hoods 钩子表 
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
| id | int(10) unsigned|否|无|是|插件id，自增主键| 
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
| id | int(10) unsigned|否|无|是|插件id，自增主键| 
| action_id|int(10) unsigned |否｜0|--|行为id|
| user_id|int(10) unsigned |否｜0|--|执行用户id|
| action_ip|bigint(20)|否｜无｜--｜执行行为者id|
| model|varchar(50)|否｜''｜--｜触发行为的表|
| record_id|int(10) unsigned|否｜0｜--｜触发行为的数据id|
| remark| varchar(255)|否|''|--|日志备注｜
| status| tinyint(1) unsigned|否|1|--|状态｜
| create_time| bigint(10) unsigned|否|0|--|执行行为的时间｜
