<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;
use think\Model;
use think\model\concern\SoftDelete;
/**
 * 菜单模型 
 */

class Menu extends Model {
    /* 自动完成规则 */
	protected $auto = ['status'=>1];
	protected function setTitleAttr($value){
		return htmlspecialchars($value);
	}
	use SoftDelete;
	protected $deleteTime = 'delete_time';
}
