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
            if($uid !== false){ //注册成功
				$this->success('用户添加成功！',Url('index'));
            } else { //注册失败，显示错误信息
                $this->error($this->showRegError($uid));
            }
        } else {
            $this->assign('meta_title','新增用户');
			return view();
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique(Request::param('id/a',0));
        if( in_array(config('heilphp.USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
		$map = [];
        $map[] =   ['uid','in',$id];
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', $map );
                break;
            case 'resumeuser':
                $this->resume('Member', $map );
                break;
            case 'deleteuser':
                $this->delete('Member', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }
    /**
     * 用户行为列表
     */
    public function action(){
        //获取列表数据
		$map = [];
		$map[] = ['status','>=',0];
        $list   =   $this->getListing('Action',$map);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->assign('meta_title','用户行为');
		return view();
    }
    /**
     * 新增行为
     */
    public function addAction(){
        $this->assign('meta_title','新增行为');
        $this->assign('data',null);
        return view('editaction');
    }

    /**
     * 编辑行为
     */
    public function editAction(){
        $id = Request::param('id/d');
        empty($id) && $this->error('参数不能为空！');
        $data = model('Action')->field(true)->find($id);

        $this->assign('data',$data);
        $this->assign('meta_title','编辑行为');
        return view('editaction');
    }
}
