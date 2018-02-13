<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use Db;
use Config;
use Session;
use Request;
use Env;
use App;
use app\admin\controller\Admin;

/**
 * 权限管理控制器
 * @author Jason <1878566968@qq.com>
 */
class Authmanage extends Admin{
    /**
     * 权限管理首页
     * @author Jason	<1878566968@qq.com>
     */
    public function index(){
        $list = $this->getListing('auth_group',array('module'=>'admin'),'id asc');

        $this->assign('meta_title', '权限管理');
		return view();
    }
}
