<?php
// +----------------------------------------------------------------------
// | 三亚名城果蔬服务有限公司
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.hcnmsc.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\user\api\UserApi;
use think\Db;

/**
 * 子用户管理控制器
 * @author Jason <1878566968@qq.com>
 */
class SubUser extends Admin{
    /**
     * 用户管理首页
     */
    public function index(){
        if (is_login() != get_user_pid(is_login())){
            $this->error('权限不足');
        }
        $nickname       =   input('param.nickname');
		$this->assign('nickname',$nickname);
		$map = [];
		if(!is_administrator()){
			$map[]  =   ['pid','=',UID];
		}
        $map[]  =   ['status','>=',0];
        if(is_numeric($nickname)){
            //$map['uid|nickname']=   array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
        }else{
            //$map['nickname']    =   array('like', '%'.(string)$nickname.'%');
        }

        $list   = $this->getListing('SubMember', $map);
        int_to_string($list);
        $this->assign('_list', $list);
        $this->meta_title = '子账号信息';
        $this->assign('meta_title', $this->meta_title);

		return view();
    }
    public function add($username = '', $password = '', $repassword = '', $email = ''){
        if (is_login() != get_user_pid(is_login())){
            $this->error('权限不足');
        }
        if(Request()->isPost()){
            /* 调用注册接口注册用户 */
            $User   =   new UserApi;
            $uid    =   $User->register($username, $password,$repassword, $email);
            if(0 < $uid){ //注册成功
                $user = array('uid' => $uid, 'pid'=>UID,'nickname' => $username, 'status' => 1);
                $user["create_time"] = $user["update_time"] = app()->getBeginTime();
                if(!Db::name('SubMember')->insert($user)){
                    $this->error('用户添加失败！');
                } else {
                    $this->success('用户添加成功！',Url('index'));
                }
            } else { //注册失败，显示错误信息
                $this->error($uid);
            }
        } else {
            $this->assign('meta_title','新增子用户');
			return view();
        }
    }

    /**
     * 会员状态修改
     */
    public function changeStatus($method=null){
        $id = array_unique(input('param.id/a',[]));
        if( in_array(config('heilphp.USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
		$map = [];
		if(!is_administrator()){
			$map[]  =   ['pid','=',UID];
		}
        $map[] =   ['uid','in',$id];
        switch ( strtolower($method) ){
            case 'forbid':
                $this->forbid('SubMember', $map );
                break;
            case 'resume':
                $this->resume('SubMember', $map );
                break;
            case 'delete':
                $this->delete('SubMember', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }

    /**
     * 修改昵称初始化
     */
	public function editNickname(){
		if(Request()->isPost()){
			//获取参数
			$nickname = input('param.nickname');
			$password = input('param.password');
			empty($nickname) && $this->error('请输入昵称');
			empty($password) && $this->error('请输入密码');

			//密码验证
			$SubUser   =   model('SubMember');;
			$uid    =   $SubUser->login(UID, $password, 4);
			($uid === -2) && $this->error('密码不正确');

			$SubMember =   model('SubMember');
			$data   =   ['nickname'=>$nickname];
			if(!$data){
				$this->error($SubMember->getError());
			}

			$res = $SubMember->save($data,['uid'=>UID]);

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
			$nickname = db('SubMember')->getFieldByUid(UID, 'nickname');
			$this->assign('nickname', $nickname);
			$this->assign('meta_title','修改昵称');
			return view();
		}
	}

    /**
     * 修改密码初始化
	 */
	public function editPassword(){
		if(Request()->isPost()){
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
