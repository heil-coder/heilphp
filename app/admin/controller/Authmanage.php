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
		//如果没有传入id
		if(!empty($id)){
			$auth_group = model('AuthGroup')->where([
				['module','=','admin']	
				,['type','=',AuthGroup::TYPE_ADMIN]
			])
			->find( (int)$_GET['id'] );
			$this->assign('auth_group',$auth_group);
			$actionName = '新增';
		}
		//如果传入id
		else{
			if ( empty($this->auth_group) ) {
				$this->assign('auth_group',array('title'=>null,'id'=>null,'description'=>null,'rules'=>null,));//排除notice信息
			}
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
        $_POST['module'] =  'admin';
        $_POST['type']   =  AuthGroupModel::TYPE_ADMIN;
        $AuthGroup       =  model('AuthGroup');
        $data = $AuthGroup->create();
        if ( $data ) {
            if ( empty($data['id']) ) {
                $r = $AuthGroup->add();
            }else{
                $r = $AuthGroup->save();
            }
            if($r===false){
                $this->error('操作失败'.$AuthGroup->getError());
            } else{
                $this->success('操作成功!',U('index'));
            }
        }else{
            $this->error('操作失败'.$AuthGroup->getError());
        }
		exit();
    }
}
