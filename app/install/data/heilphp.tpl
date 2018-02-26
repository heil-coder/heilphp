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
    'AUTOLOAD_NAMESPACE' => array('addons' => HEILPHP_ADDON_PATH), //扩展模块列表
];
