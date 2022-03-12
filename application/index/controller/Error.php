<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use think\Controller;
use think\Request;

/**
 * 空控制器
 * @author Jason <1878566968@qq.com>
 */
class Error extends Controller{
    public function index(Request $request)
    {
        //根据当前控制器名来判断要执行那个城市的操作
		$this->error('内容不存在');
    }
}
