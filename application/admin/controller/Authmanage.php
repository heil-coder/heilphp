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
			if ( empty($this->auth_group) ) {
				$this->assign('auth_group',array('title'=>null,'id'=>null,'description'=>null,'rules'=>null,));//排除notice信息
			}
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
        $this->updateRules();
		$auth_group = db('AuthGroup')->where([
			['status','>=',0]
			,['module','=','admin']
			,['type','=',AuthGroup::TYPE_ADMIN] 			
		])
		->column('id,id,title,rules');
        $node_list   = $this->returnNodes();
		$map         = [];
		$map[]		 = ['module','=','admin'];
		$map['type']		 = ['type','=',AuthRule::RULE_MAIN];
		$map[]		 = ['status','=',1];
        $main_rules  = db('AuthRule')->where($map)->column('name,id');
		$map['type'] = ['type','=',AuthRule::RULE_URL];
        $child_rules = db('AuthRule')->where($map)->column('name,id');

        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list',  $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[Request::param('group_id/d')]);
        $this->assign('meta_title','访问授权');
        return view('managegroup');
    }
    /**
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function updateRules(){
        //需要新增的节点必然位于$nodes
        $nodes    = $this->returnNodes(false);

        $AuthRule = model('AuthRule');
        $map      = [['module','=','admin'],['type','in','1,2']];//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules
        $rules    = $AuthRule->where($map)->order('name')->select();

        //构建insert数据
        $data     = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value){
            $temp['name']   = $value['url'];
            $temp['title']  = $value['title'];
            $temp['module'] = 'admin';
            if($value['pid'] >0){
                $temp['type'] = AuthRule::RULE_URL;
            }else{
                $temp['type'] = AuthRule::RULE_MAIN;
            }
            $temp['status']   = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;//去除重复项
        }

        $update = array();//保存需要更新的节点
        $ids    = array();//保存需要删除的节点的id
        foreach ($rules as $index=>$rule){
            $key = strtolower($rule['name'].$rule['module'].$rule['type']);
            if ( isset($data[$key]) ) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']]=$rule;
            }elseif($rule['status']==1){
                $ids[] = $rule['id'];
            }
        }
        if ( count($update) ) {
            foreach ($update as $k=>$row){
                if ( $row!=$diff[$row['id']] ) {
                    $AuthRule->where(array('id'=>$row['id']))->find()->save($row);
                }
            }
        }
        if ( count($ids) ) {
            $AuthRule->where( array( 'id'=>array('IN',implode(',',$ids)) ) )->select()->save(array('status'=>-1));
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if( count($data) ){
            $AuthRule->saveAll(array_values($data));
        }
        if ( $AuthRule->getConnection()->getError() ) {
            trace('['.__METHOD__.']:'.$AuthRule->getConnection()->getError());
            return false;
        }else{
            return true;
        }
    }
    /**
     * 将分类添加到用户组的编辑页面
     */
    public function category(){
		$auth_group     =   db('AuthGroup')->where([
													['status','>=','0']
													,['module','=','admin']
													,['type','=',AuthGroup::TYPE_ADMIN]
												])
            ->column('id,id,title,rules');
        $group_list     =   model('Category')->getTree();
        $authed_group   =   db('AuthExtend')->where('group_id',Request::param('group_id'))->column('extend_id');
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

		$auth_group = db('AuthGroup')->where([
										['status','>=','0']
										,['module','=','admin']
										,['type','=',AuthGroup::TYPE_ADMIN]
									])
            ->column('id,id,title,rules');
        $prefix   = config('database.prefix');
        $l_table  = $prefix.(AuthGroup::MEMBER);
        $r_table  = $prefix.(AuthGroup::AUTH_GROUP_ACCESS);
        $model    = db(AuthGroup::MEMBER)->alias('m')->join ( $r_table.' a','m.uid=a.uid' );
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
            if( !db('Member')->where('uid',$uid)->find() ){
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
