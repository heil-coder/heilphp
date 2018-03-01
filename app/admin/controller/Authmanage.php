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
        int_to_string($list);
		$this->assign('_list',$list);

        $this->assign('meta_title', '权限管理');
		return view();
    }
    /**
     * 编辑管理员用户组
     */
	public function editGroup(){
		if(Request::isPost()){
			$this->writeGroup();
		}
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
				['module','admin']	
				,['type',AuthGroup::TYPE_ADMIN]
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
    protected function writeGroup(){
        if(isset($_POST['rules'])){
            sort($_POST['rules']);
            $_POST['rules']  = implode( ',' , array_unique($_POST['rules']));
        }
        $mAuthGroup       =  model('AuthGroup');
        $data = Request::only(['id','module','type','title','description','status','rules']);
		$data['module'] = 'admin';
		$data['type'] =  AuthGroup::TYPE_ADMIN;
        if ( $data ) {
			if(empty($data['id'])){
				$result = $mAuthGroup->save($data);
			}
			else{
				$result = $mAuthGroup->where('id',$data['id'])->find()->save($data);
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
}
