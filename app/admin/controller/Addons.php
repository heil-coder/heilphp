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
 * 扩展插件后台管理页面
 * @author Jason <1878566968@qq.com>
 */
class Addons extends Admin {

    public function _initialize(){
        $this->assign('_extra_menu',array(
            '已装插件后台'=> model('Addons')->getAdminList(),
        ));
        parent::_initialize();
    }

    /**
     * 钩子列表
     */
    public function hooks(){
        $this->assign('meta_title','钩子列表');
        $map    =   $fields =   array();
        $list   =   $this->getListing(model("Hooks")->field($fields),$map);
        int_to_string($list, array('type'=>config('HOOKS_TYPE')));
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('list', $list );
		return view();
    }
    //钩子新增、挂载插件页面
	public function addhook(){
        $this->assign('data', null);
        $this->assign('meta_title','新增钩子');
        return view('edithook');
    }
    //钩子编辑、挂载插件页面
    public function edithook($id){
        $hook = model('Hooks')->field(true)->find($id);
        $this->assign('data',$hook);
        $this->assign('meta_title','编辑钩子');
        return view();
    }
	//钩子信息更新
    public function updateHook(){
        $hookModel  =   model('Hooks');
        $data       =   Request::only('id,name,description,type,addons,status');
        if($data){
            if($data['id']){
                $flag = $hookModel->get($data['id'])->save($data);
                if($flag !== false){
                    cache('hooks', null);
                    $this->success('更新成功', Cookie('__forward__'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $flag = $hookModel->save($data);
                if($flag){
                    cache('hooks', null);
                    $this->success('新增成功', Cookie('__forward__'));
                }else{
                    $this->error('新增失败');
                }
            }
        }else{
            $this->error($hookModel->getError());
        }
    }
    //超级管理员删除钩子
    public function delhook($id){
        if(db('Hooks')->where('id',$id)->delete() !== false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}
