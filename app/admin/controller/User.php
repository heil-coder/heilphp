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
use app\user\api\UserApi;

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
		$map = [];
        $map[]  =   ['status','>=',0];
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
    public function add($username = '', $password = '', $repassword = '', $email = ''){
        if(Request::isPost()){
            /* 检测密码 */
            if($password != $repassword){
                $this->error('密码和重复密码不一致！');
            }

            /* 调用注册接口注册用户 */
            $User   =   new UserApi;
            $uid    =   $User->register($username, $password, $email);
            if(0 < $uid){ //注册成功
                $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
                if(!db('Member')->add($user)){
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！',Url('index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            $this->assign('meta_title','新增用户');
			return view();
        }
    }
}
