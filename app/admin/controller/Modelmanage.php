<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use Request;

/**
 * 模型管理控制器
 * @author Jason	<1878566968@qq.com>
 */
class Modelmanage extends Admin {

    /**
     * 模型管理首页
     */
    public function index(){
        $list = $this->getListing('Model');
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->assign('meta_title','模型管理');
		return view();
    }
}
