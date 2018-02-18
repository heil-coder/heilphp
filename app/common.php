<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 时间戳格式化
 * @param int $time			时间字符串或时间戳
 * @param string $format	时间格式
 * @return string 完整的时间显示
 * @author Jason	<1878566968@qq.com>
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    $time = $time === NULL ? App::getBeginTime() : $time;
	$time = is_numeric($time) ? $time : strtotime($time);
    return date($format, $time);
}

