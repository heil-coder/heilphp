<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// HeilPHP常量定义
const HEILPHP_VERSION    = '0.01';
const HEILPHP_ADDON_PATH = './addons/';
return [
    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落')

    /* 用户相关设置 */
    ,'USER_MAX_CACHE'     => 1000					//最大缓存用户数
    ,'USER_ADMINISTRATOR' => 1						//管理员用户ID
];
