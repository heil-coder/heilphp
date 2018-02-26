<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\Controller;

use Hook;
use app\admin\controller\Admin;

/**
 * 后台首页控制器
 * @author Jason	<1878566968@qq.com>
 */
class Index extends Admin{

    /**
     * 后台首页
     * @author Jason	<1878566968@qq.com>
     */
    public function index(){
        $this->assign('meta_title','管理首页');

		dump(Hook::listen('adminIndex'));
		//return view();
    }

}
