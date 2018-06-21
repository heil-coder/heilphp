<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

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
        $pid  = input('param.pid/d',0);
        if($pid){
            $data = db('Menu')->where('id',$pid)->field(true)->find();
            $this->assign('data',$data);
        }
        $title      =   trim(input('param.title'));
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
        if(input('param.title')){
            $mMenu = model('Menu');
            $data = Request()->only('title,pid,sort,url,hide,tip,group,is_dev,status');
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
            $this->assign('info',array('pid'=>input('param.pid')));
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
        if(Request()->isPost()){
            $mMenu = model('Menu');
			$res = $mMenu->edit();
			if($res !== false){
				session('ADMIN_MENU_LIST',null);
				$this->success('更新成功', Cookie('__forward__'));
			} else {
				$this->error($mMenu->getError() ?: '更新失败');
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
        $id = array_unique(input('param.id/a',[]));

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
	/**
	 * 菜单导入
	 */
    public function import(){
        if(Request()->isPost()){
            $tree = Input('post.tree');
            $lists = explode(PHP_EOL, $tree);
            $menuModel = Db('Menu');
            if($lists == []){
                $this->error('请按格式填写批量导入的菜单，至少一个菜单');
            }else{
                $pid = Input('post.pid/d',0);
                foreach ($lists as $key => $value) {
                    $record = explode('|', $value);
                    if(count($record) == 2){
						$data[] = [
							'title'=>$record[0],
							'url'=>$record[1],
							'pid'=>$pid,
							'sort'=>0,
							'hide'=>0,
							'tip'=>'',
							'is_dev'=>0,
							'group'=>''
						];
                    }
                }
				if(!empty($data)){
					$menuModel->insertAll($data);
				}
                session('ADMIN_MENU_LIST',null);
                $this->success('导入成功',Url('index',['pid'=>$pid]));
            }
        }else{
            $this->assign('meta_title','批量导入后台菜单');
            $pid = Input('param.pid/d');
            $this->assign('pid', $pid);
            $data = db('Menu')->where('id',$pid ?: 0)->field(true)->find();
            $this->assign('data', $data);
			return view();
        }
    }
    /**
     * 菜单排序
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function sort(){
        if(Request()->isGet()){
            $ids = Input('param.ids');
            $pid = Input('param.pid/d',0);

            //获取排序的数据
			$map = [
				['status','>',-1]
			];
            if(!empty($ids)){
                $map[] = ['id','in',$ids];
            }else{
                if($pid !== ''){
                    $map[] = ['pid','=',$pid];
                }
            }
            $list = Db('Menu')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->assign('meta_title','菜单排序');
			return view();
        }elseif (Request()->isPost()){
            $ids = Input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = Db('Menu')->where('id',$value)->setField('sort', $key+1);
            }
            if($res !== false){
                session('ADMIN_MENU_LIST',null);
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}
