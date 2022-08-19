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
use app\admin\model\SubAuthGroup;
use app\admin\model\AuthRule;
use think\Db;

/**
 * 权限管理控制器
 * @author Jason <1878566968@qq.com>
 */
class SubAuthManage extends Admin{
    /**
     * 权限管理首页
     * @author Jason	<1878566968@qq.com>
     */
    public function index(){
        $list = $this->getListing('sub_auth_group', array('module'=>'admin') ,'id asc');
		$this->assign('_list',$list);

        $this->assign('meta_title', '子账号权限管理');
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
			$auth_group = model('SubAuthGroup')->where([
				['module','=','admin'],
				['type','=',AuthGroup::TYPE_ADMIN],
                ["uid", "=", UID],
                ["id", "=", $id]
			])
			->find($id);
			$this->assign('auth_group',$auth_group);
			$actionName = '编辑';
		}
		$this->assign('meta_title', $actionName.'子账号用户组');
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
        $mAuthGroup       =  model('SubAuthGroup');
        $data = Request::only(['id', 'title','description','status','rules']);
        $data["uid"] = UID;
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
     * 访问授权页面
     */
    public function access(){
        model("AuthRuleService", "service")->updateRules();
		$auth_group = Db::name('SubAuthGroup')->where([
			['status' , '>=', 0],
			['module','=','admin'],
			['uid', '=', UID],
			['type','=',AuthGroup::TYPE_ADMIN] 			
		])
		->column('id,id,title,rules');
		$map         = [];
		$map[]		 = ['module','=','admin'];
		$map[]		 = ['status','=',1];
        $userRules = model("AuthGroupService", "service")->getUserAuthRulesByUid(UID);
        $map[] = ["id", "in", $userRules];
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

        $node_list   = model("MenuService", "service")->returnNodes($userRules);
        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list',  $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[Request::param('group_id/d')]);
        $this->assign('meta_title','访问授权');
        return view('sub_auth_group');
    }
    /**
     * 用户组授权用户列表
     */
    public function user($group_id){
        if(empty($group_id)){
            $this->error('参数错误');
        }

		$auth_group = Db::name('SubAuthGroup')->where([
										['status','>=','0']
										,['module','=','admin']
										,['type','=',AuthGroup::TYPE_ADMIN]
									])
            ->column('id,id,title,rules');

        $model = Db::name("SubMember")->alias("subMember")
            ->join("SubAuthGroupAccess subAuthGroupAccess", "subMember.uid = subAuthGroupAccess.uid");

		$_REQUEST = [];
		$list = $this->getListing($model,
			[
				["subAuthGroupAccess.group_id", "=", $group_id],
				["subMember.status", ">=", 0]
			]
			,"subMember.uid asc"
			,"subMember.uid, subMember.nickname, subMember.last_login_time, subMember.last_login_ip, subMember.status");
        int_to_string($list);
        $this->assign( '_list',     $list );
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[Request::param('group_id/d')]);
        $this->assign('meta_title','子账号成员授权');
		return view();
    }
    /**
     * 将用户添加到用户组的编辑页面
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function group(){
        $uid = input('uid/d');
        $auth_groups = model('SubAuthGroup')->getGroups();
        $groupIds =   SubAuthGroup::listUserGroupIds($uid);
        $nickname       =   model('Member')->getNickName($uid);
        $this->assign('nickname',   $nickname);
        $this->assign('auth_groups',$auth_groups);
        $this->assign("user_groups", implode(",", $groupIds));
        $this->assign('meta_title','用户组授权');
		return view();
    }
    /**
     * 将子用户添加到子用户组
     * @param $uid 用户ID
     * @param $groupId 子账号用户组ID
     */
    public function addToGroup(){
        $subAuthGroupService = model("SubAuthGroupService", "service");
        $res = $subAuthGroupService->addToGroup();
        if ($res){
            $this->success("提交成功");
        }
        else{
            $this->error($subAuthGroupService->getError());
        }
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
                $this->forbid('SubAuthGroup');
                break;
            case 'resumegroup':
                $this->resume('SubAuthGroup');
                break;
            case 'deletegroup':
                $this->delete('SubAuthGroup');
                break;
            default:
                $this->error($method.'参数非法');
        }
    }
}
