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
use app\admin\controller\Admin;

/**
 * 用户管理控制器
 * @author Jason <1878566968@qq.com>
 */
class User extends Admin{
    /**
     * 用户管理首页
     * @author Jason	<1878566968@qq.com>
     */
    public function index(){
        $nickname       =   Request::get('nickname');
		$this->assign('nickname',$nickname);
		$map = array();
        $map[]  =   array('status','>=',0);
        if(is_numeric($nickname)){
            //$map['uid|nickname']=   array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        }else{
            //$map['nickname']    =   array('like', '%'.(string)$nickname.'%');
        }

        $list   = $this->getListing('Member', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->assign('meta_title', $this->meta_title);

		return view();
    }
}
