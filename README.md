# heilphp
heilphp


## 目录结构
```
project  应用部署目录
├─app					应用目录（可设置）
│  ├─common             公共模块目录（可更改）
│  ├─index              模块目录(可更改)
│  │  ├─config.php      模块配置文件
│  │  ├─common.php      模块函数文件
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  └─ ...            更多类库目录
│  ├─command.php        命令行工具配置文件
│  ├─common.php         应用公共（函数）文件
│  ├─config.php         应用（公共）配置文件
│  ├─database.php       数据库配置文件
│  ├─tags.php           应用行为扩展定义文件
│  └─route.php          路由配置文件
├─extend                扩展类库目录（可定义）
├─public                WEB 部署目录（对外访问目录）
│  ├─uploads			上传文件存储目录
│  ├─static             静态资源存放目录(css,js,image)
│  ├─index.php          应用入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于 apache 的重写
├─runtime               应用的运行时目录（可写，可设置）
├─vendor                第三方类库目录（Composer）
├─thinkphp              框架系统目录
│  ├─lang               语言包目录
│  ├─library            框架核心类库目录
│  │  ├─think           Think 类库包目录
│  │  └─traits          系统 Traits 目录
│  ├─tpl                系统模板目录
│  ├─.htaccess          用于 apache 的重写
│  ├─.travis.yml        CI 定义文件
│  ├─base.php           基础定义文件
│  ├─composer.json      composer 定义文件
│  ├─console.php        控制台入口文件
│  ├─convention.php     惯例配置文件
│  ├─helper.php         助手函数文件（可选）
│  ├─LICENSE.txt        授权说明文件
│  ├─phpunit.xml        单元测试配置文件
│  ├─README.md          README 文件
│  └─start.php          框架引导文件
├─build.php             自动生成定义文件（参考）
├─composer.json         composer 定义文件
├─LICENSE.txt           授权说明文件
├─README.md             README 文件
├─think                 命令行入口文件
```

# 数据字典

> 参考[OneThink数据字典](http://document.onethink.cn/manual_1_0.html#onethink_3_3)进行设计。  

## config 系统配置表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id | int(10) unsigned |--|否|是| 配置ID,自增主键 |
| name | varchar(30) |--|否|--| 配置名称 |
| type | tinyint(3) |0|否|--| 配置类型（0-数字，1-字符，2-文本，3-数组，4-枚举，5-多选） |
| title | varchar(50) |--|否|--| 配置说明 |
| group | tinyint(3) |--|否|--| 配置分组（0-无分组，1-基本设置）|
| extra | varchar(255) |--|否|--| 配置值 如：0:关闭1:开启|
| remark | varchar(100) |--|否|--| 配置说明 |
| create_time | bigint(10) unsigned |--|否|--| 创建时间 |
| update_time | binint(10) unsigned |--|否|--| 更新时间 |
| status | tinyint(1) |--|否|--| 状态 |	
| value | text |--|否|--| 配置值 |	
| sort | smallint(3) unsigned |--|否|--| 排序 |	


## member 会员信息表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| uid | int(10) unsigned |否|无|是| 用户ID,自增主键 |
| nickname | char(30)  |否|无|--| 昵称|
| sex| tinyint(3) unsigned |否|无|--| 性别(0:未知/保密 1:男 2:女)|
| birthday| date|否|无|--|生日|
| qq| char(15)|否|无|--|qq|
| score| mediumint(1)|否|无|--|用户积分|
| login| int(1)|否|无|--|登录次数|
| reg_ip| bigint(20)|否|无|--|注册IP|
| reg_time| bigint(10) unsigned|否|无|--|注册时间|
| last_login_ip| bigint(20)|否|无|--|最后登录IP|
| last_login_time| bigint(10) unsigned|否|无|--|最后登录时间|
| status| tinyint(1) |否|无|--|会员状态|

## auth_group 用户组定义表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id|mediumint(8) unsigned| 否|无|是|用户组id，自增主键|
| module| varchar(30)| 否| 无|--|用户组所属模块|
| type| tinyint(4)| 否| 无|否|组类型|
| title| char(30)| 否|无|--|用户组中文名称|
| description| varchar(80)|--|无|否|描述信息|
| status| tinyint(1)|否|无|否|用户组状态 第2位(0:未删除 1:已删除) 第1位(0:禁用 1:可用)|
| rules| varchar(500)| 否|无|--|用户组拥有的规则id,过个规则使用','隔开|

## auth_group_access 用户用户组关系对应表 
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| uid| int(11) unsigned| 否| 无|否|用户id|
| group_id| mediumint(8) unsigned| 否| 无|否|用户组id|

## auth_rule 权限规则表
|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| id|mediumint(8) unsigned| 否|无|是|用户组id，自增主键|
| module| varchar(30)| 否| 无|--|用户组所属模块|
| type| tinyint(2)| 否| 无|--|1:url ; 2:主菜单|
| name| char(80)| 否|无|--|规则唯一英文标识|
| title|char(30)|否|无|--|规则中文描述|
| status| tinyint(1)| 否|无|否|规则状态 第2位(0:未删除 1:已删除) 第1位(0:禁用 1:可用)|
| condition| varchar(300)|否|无|--|规则附加条件|

## auth_extend 权限扩展表 

> 当节点控制无法满足时,需要对权限控制进行扩展。例如：分类的授权即使用该表。  

|字段|类型|允许为空|默认值|自动递增|注释|
|:--|:--|:--|:--|:--|:--|
| group_id| mediumint(8) unsigned| 否|无|否|用户组id|
| extend_id| mediumint(8) unsigned| 否|无|否|扩展表中数据的id|
| type| tinyint(1) unsigned| 否|无|否|扩展类型标识 1:栏目分类权限|

> 索引定义：UNIQUE KEYgroup_extend_type(group_id,extend_id,type)  
