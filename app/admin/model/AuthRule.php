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
 * 权限规则模型
 */
class AuthRule extends Model{
    
    const RULE_URL = 1;
    const RULE_MAIN = 2;
	use SoftDelete;
	protected $deleteTime = 'delete_time';
}
