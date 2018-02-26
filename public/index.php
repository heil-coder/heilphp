<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

//检测php版本
if(version_compare(PHP_VERSION,'5.6.0','<'))  die('require PHP > 5.6.0 !');

/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */

// 定义应用目录
define('APP_PATH',realpath(__DIR__ . '/../app/').'/');

if(!is_file(APP_PATH . 'install/data/install.lock')){
	header('Location: ./install.php');
	exit;
}
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
