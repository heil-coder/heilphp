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
        //int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->assign('meta_title', $this->meta_title);

		return view();
    }
    public function add($username = '', $password = '', $repassword = '', $email = ''){
        if(Request::isPost()){
            /* 调用注册接口注册用户 */
            $User   =   new UserApi;
            $uid    =   $User->register($username, $password,$repassword, $email);
            if(0 < $uid){ //注册成功
                $user = array('uid' => $uid, 'nickname' => $username, 'status' => 1);
                if(!db('Member')->insert($user)){
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！',Url('index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($uid);
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
        $id = array_unique(Request::param('id/a',[]));
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
        !empty($list) && $list = $list->toArray()['data'];
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
    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction(){
        $res = model('Action')->edit();
        if(!$res){
            $this->error(model('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }
    /**
     * 修改昵称初始化
     */
	public function editNickname(){
		if(Request::isPost()){
			//获取参数
			$nickname = Request::post('nickname');
			$password = Request::post('password');
			empty($nickname) && $this->error('请输入昵称');
			empty($password) && $this->error('请输入密码');

			//密码验证
			$User   =   model('Member');;
			$uid    =   $User->login(UID, $password, 4);
			($uid === -2) && $this->error('密码不正确');

			$Member =   model('Member');
			$data   =   ['nickname'=>$nickname];
			if(!$data){
				$this->error($Member->getError());
			}

			$res = $Member->get(UID)->save($data);

			if($res !== false){
				$user               =   session('user_auth');
				$user['username']   =   $data['nickname'];
				session('user_auth', $user);
				session('user_auth_sign', data_auth_sign($user));
				$this->success('修改昵称成功！');
			}else{
				$this->error('修改昵称失败！');
			}
		}
		else{
			$nickname = db('Member')->getFieldByUid(UID, 'nickname');
			$this->assign('nickname', $nickname);
			$this->assign('meta_title','修改昵称');
			return view();
		}
	}

    /**
     * 修改密码初始化
	 */
	public function editPassword(){
		if(Request::isPost()){
			//获取参数
			$password   =   Input('post.old');
			empty($password) && $this->error('请输入原密码');
			$data['password'] = Input('post.password');
			empty($data['password']) && $this->error('请输入新密码');
			$repassword = Input('post.repassword');
			empty($repassword) && $this->error('请输入确认密码');

			if($data['password'] !== $repassword){
				$this->error('您输入的新密码与确认密码不一致');
			}

			$Api    =   new UserApi();
			$res    =   $Api->updateInfo(UID, $password, $data);
			if($res['status']){
				$this->success('修改密码成功！');
			}else{
				$this->error($res['info']);
			}
		}
		else{
			$this->assign('meta_title','修改密码');
			return view();
		}
	}
}
