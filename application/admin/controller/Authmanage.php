<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\controller\Admin;
use Request;
use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;
use think\Db;

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
		$this->assign('_list',$list);

        $this->assign('meta_title', '权限管理');
		return view();
    }
    /**
     * 编辑管理员用户组
     */
	public function editGroup(){
		$id = Request::param('id/d',null);
		//如果没有传入id
		if(empty($id)){
            $this->assign('auth_group',array('title'=>null,'id'=>null,'description'=>null,'rules'=>null,));//排除notice信息
			$actionName = '新增';
		}
		//如果传入id
		else{
			$auth_group = model('AuthGroup')->where([
				['module','=','admin']	
				,['type','=',AuthGroup::TYPE_ADMIN]
			])
			->find($id);
			$this->assign('auth_group',$auth_group);
			$actionName = '编辑';
		}
		$this->assign('meta_title',$actionName.'用户组');
		return view();
    }
    /**
     * 管理员用户组数据写入/更新
     */
    public function writeGroup(){
        if(isset($_POST['rules'])){
            sort($_POST['rules']);
            $_POST['rules']  = implode( ',' , array_unique($_POST['rules']));
        }
        $mAuthGroup       =  model('AuthGroup');
        $data = Request::only(['id','module','type','title','description','status','rules']);
		$data['module'] = 'admin';
		$data['type'] =  $mAuthGroup::TYPE_ADMIN;
        if ( $data ) {
			if(empty($data['id'])){
				$result = $mAuthGroup->allowField(true)->save($data);
			}
			else{
				$result = $mAuthGroup->allowField(true)->save($data,['id'=>$data['id']]);
			}
            if($result === false){
                $this->error('操作失败'.$mAuthGroup->getError());
            } else{
                $this->success('操作成功!',Url('index'));
            }
        }else{
            $this->error('操作失败'.$mAuthGroup->getError());
        }
		exit();
    }
    /**
     * 状态修改
     */
    public function changeStatus($method=null){
        if ( empty(Request::has('id')) ) {
            $this->error('请选择要操作的数据!');
        }
        switch ( strtolower($method) ){
            case 'forbidgroup':
                $this->forbid('AuthGroup');
                break;
            case 'resumegroup':
                $this->resume('AuthGroup');
                break;
            case 'deletegroup':
                $this->delete('AuthGroup');
                break;
            default:
                $this->error($method.'参数非法');
        }
    }
    /**
     * 访问授权页面
     */
    public function access(){
        model("AuthRuleService", "service")->updateRules();
		$auth_group = Db::name('AuthGroup')->where([
			['status','>=',0]
			,['module','=','admin']
			,['type','=',AuthGroup::TYPE_ADMIN] 			
		])
		->column('id,id,title,rules');
        $node_list   = $this->returnNodes();
		$map         = [];
		$map[]		 = ['module','=','admin'];
		$map[]		 = ['status','=',1];
        $main_rules  = Db::name('AuthRule')->where($map)
                                           ->where([
                                               ['type','=',AuthRule::RULE_MAIN]
                                           ])
                                           ->column('name,id');
        $child_rules = Db::name('AuthRule')->where($map)
                                           ->where([
                                               ['type','=',AuthRule::RULE_URL]
                                           ])
                                           ->column('name,id');

        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list',  $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[Request::param('group_id/d')]);
        $this->assign('meta_title','访问授权');
        return view('managegroup');
    }
    /**
     * 将分类添加到用户组的编辑页面
     */
    public function category(){
		$auth_group     =   Db::name('AuthGroup')->where([
													['status','>=','0']
													,['module','=','admin']
													,['type','=',AuthGroup::TYPE_ADMIN]
												])
            ->column('id,id,title,rules');
        $group_list     =   model('Category')->getTree();
        $authed_group   =   Db::name('AuthExtend')->where('group_id',Request::param('group_id'))->column('extend_id');
        $this->assign('authed_group',   implode(',',(array)$authed_group));
        $this->assign('group_list',     $group_list);
        $this->assign('auth_group',     $auth_group);
		$this->assign('this_group',     $auth_group[Request::param('group_id')]);
        $this->assign('meta_title','分类授权');
		return view();
    }
    public function tree($tree = null){
        $this->assign('tree', $tree);
		return $this->fetch('tree');
    }
    /**
     * 将分类添加到用户组  入参:cid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToCategory(){
        $cid = input('cid/a');
        $gid = input('group_id/d');
        if( empty($gid) ){
            $this->error('参数有误');
        }
        $AuthGroup = model('AuthGroup');
        if( !$AuthGroup->find($gid)){
            $this->error('用户组不存在');
        }
        if( $cid && !$AuthGroup->checkCategoryId($cid)){
            $this->error($AuthGroup->error);
        }
        if ( $AuthGroup->addToCategory($gid,$cid) ){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
    /**
     * 用户组授权用户列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function user($group_id){
        if(empty($group_id)){
            $this->error('参数错误');
        }

		$auth_group = Db::name('AuthGroup')->where([
										['status','>=','0']
										,['module','=','admin']
										,['type','=',AuthGroup::TYPE_ADMIN]
									])
            ->column('id,id,title,rules');
        $prefix   = config('database.prefix');
        $l_table  = $prefix.(AuthGroup::MEMBER);
        $r_table  = $prefix.(AuthGroup::AUTH_GROUP_ACCESS);
        $model    = Db::name(AuthGroup::MEMBER)->alias('m')->join ( $r_table.' a','m.uid=a.uid' );
		$_REQUEST = array();
		$list = $this->getListing($model
			,[
				['a.group_id','=',$group_id]
				,['m.status','>=',0]
			]
			,'m.uid asc'
			,'m.uid,m.nickname,m.last_login_time,m.last_login_ip,m.status');
        int_to_string($list);
        $this->assign( '_list',     $list );
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[Request::param('group_id/d')]);
        $this->assign('meta_title','成员授权');
		return view();
    }
    /**
     * 将用户添加到用户组的编辑页面
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function group(){
        $uid            =   input('uid/d');
        $auth_groups    =   model('AuthGroup')->getGroups();
        $user_groups    =   AuthGroup::getUserGroup($uid);
        $ids = array();
        foreach ($user_groups as $value){
            $ids[]      =   $value['group_id'];
        }
        $nickname       =   model('Member')->getNickName($uid);
        $this->assign('nickname',   $nickname);
        $this->assign('auth_groups',$auth_groups);
        $this->assign('user_groups',implode(',',$ids));
        $this->assign('meta_title','用户组授权');
		return view();
    }
    /**
     * 将用户添加到用户组,入参uid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function addToGroup(){
        $uid = input('param.uid/d');
        $gid = input('group_id/a');
        if( empty($uid) ){
            $this->error('参数有误');
        }
        $AuthGroup = model('AuthGroup');
        if(is_numeric($uid)){
            if ( is_administrator($uid) ) {
                $this->error('该用户为超级管理员');
            }
            if( !Db::name('Member')->where('uid',$uid)->find() ){
                $this->error('用户不存在');
            }
        }

        if( $gid && !$AuthGroup->checkGroupId($gid)){
            $this->error($AuthGroup->error);
        }
        if ( $AuthGroup->addToGroup($uid,$gid) ){
            $this->success('操作成功');
        }else{
            $this->error($AuthGroup->error);
        }
    }
    /**
     * 将用户从用户组中移除  入参:uid,group_id
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function removeFromGroup(){
        $uid = input('uid/d');
        $gid = input('group_id/d');
        if( $uid == UID ){
            $this->error('不允许解除自身授权');
        }
        if( empty($uid) || empty($gid) ){
            $this->error('参数有误');
        }
        $AuthGroup = model('AuthGroup');
        if( !$AuthGroup->find($gid)){
            $this->error('用户组不存在');
        }
        if ( $AuthGroup->removeFromGroup($uid,$gid) ){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
}
