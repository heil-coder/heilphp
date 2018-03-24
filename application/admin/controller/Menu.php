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

/**
 * 后台菜单控制器
 * @author Jason	<1878566968@qq.com>
 */
class Menu extends Admin{

    /**
     * 配置管理
     * @author Jason	<1878566968@qq.com>
     */
    public function index(){
        $pid  = Request::param('pid/d',0);
        if($pid){
            $data = db('Menu')->where('id',$pid)->field(true)->find();
            $this->assign('data',$data);
        }
        $title      =   trim(Request::param('title'));
        $type       =   Config('CONFIG_GROUP_LIST');
        $all_menu   =   db('Menu')->column('id,title');
        $map[] =   ['pid','=',$pid];
        if($title)
            $map[] = ['title','like',"%{$title}%"];
        $list       =   db("Menu")->where($map)->field(true)->order('sort asc,id asc')->select();
        int_to_string($list,array('hide'=>array(1=>'是',0=>'否'),'is_dev'=>array(1=>'是',0=>'否')));
        if($list) {
            foreach($list as &$key){
                if($key['pid']){
                    $key['up_title'] = $all_menu[$key['pid']];
                }
            }
            $this->assign('list',$list);
        }
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('meta_title','菜单列表');
		return view();
    }
    /**
     * 新增菜单
     */
    public function add(){
        if(Request::param('title')){
            $mMenu = model('Menu');
            $data = Request::only('title,pid,sort,url,hide,tip,group,is_dev,status');
            if($data){
                $id = $mMenu->save($data);
                if($id){
                    session('ADMIN_MENU_LIST',null);
                    //记录行为
                    //action_log('update_menu', 'Menu', $id, UID);
                    $this->success('新增成功', Cookie('__forward__'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($mMenu->getError());
            }
        } else {
            $this->assign('info',array('pid'=>Request::param('pid')));
            $menus = db('Menu')->field(true)->select();
            $menus = model('Tree')->toFormatTree($menus);
            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->assign('Menus', $menus);
            $this->assign('meta_title','新增菜单');
			return view('edit');
        }
    }
    /**
     * 编辑配置
     */
    public function edit($id = 0){
        if(Request::isPost()){
            $mMenu = model('Menu');
            $data = Request::only('title,pid,sort,url,hide,tip,group,is_dev,status');
            if($data){
                if($mMenu->where('id',Request::param('id/d'))->find()->save($data)!== false){
                    session('ADMIN_MENU_LIST',null);
                    //记录行为
                    //action_log('update_menu', 'Menu', $data['id'], UID);
                    $this->success('更新成功', Cookie('__forward__'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($mMenu->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = db('Menu')->field(true)->find($id);
            $menus = db('Menu')->field(true)->select();
            $menus = model('Tree')->toFormatTree($menus);

            $menus = array_merge(array(0=>array('id'=>0,'title_show'=>'顶级菜单')), $menus);
            $this->assign('Menus', $menus);
            if(false === $info){
                $this->error('获取后台菜单信息错误');
            }
            $this->assign('info', $info);
            $this->assign('meta_title','编辑后台菜单');
			return view();
        }
    }
    /**
     * 删除后台菜单
     */
    public function del(){
        $id = array_unique(Request::param('id/a',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = [['id','in',$id]];
        if(db('Menu')->where($map)->delete()){
            session('ADMIN_MENU_LIST',null);
            //记录行为
            //action_log('update_menu', 'Menu', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}
