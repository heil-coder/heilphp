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
 * 后台配置控制器
 * @author Jason	<1878566968@qq.com>
 */
class Config extends Admin{

    /**
     * 配置管理
     * @author Jason	<1878566968@qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();
        $map[]  = ['status','=',1];
        if(isset($_GET['group'])){
            $map[]   =  ['group','=',Request::get('group/d',0)];
        }
        if(isset($_GET['name'])){
            $map[]    =   ['name','like', '%'.Request::get('name/s','').'%'];
        }

        $list = $this->getListing('Config', $map,'sort,id');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('group',config('CONFIG_GROUP_LIST'));
        $this->assign('group_id',Request::get('group/d',0));
        $this->assign('list', $list);
        $this->assign('meta_title', '配置管理');
		return view();
    }
    /**
     * 新增配置
     */
    //public function add(){
    //    if(IS_POST){
    //        $Config = D('Config');
    //        $data = $Config->create();
    //        if($data){
    //            if($Config->add()){
    //                S('DB_CONFIG_DATA',null);
    //                $this->success('新增成功', U('index'));
    //            } else {
    //                $this->error('新增失败');
    //            }
    //        } else {
    //            $this->error($Config->getError());
    //        }
    //    } else {
    //        $this->meta_title = '新增配置';
    //        $this->assign('info',null);
    //        $this->display('edit');
    //    }
    //}
    /**
     * 编辑配置
     */
    public function edit($id = 0){
        if(Request::isPost()){
            $dbConfig = db('Config');
            $data = Request::param();
            if($data){
				if(empty($data['id'])){
					$result = $dbConfig->insert($data);
				}
				else{
					$result = $dbConfig->update($data);
				}
                if($result === false){
                    $this->error('更新失败');
                } else {
                    cache('DB_CONFIG_DATA',null);
                    //记录行为
                    //action_log('update_config','config',$data['id'],UID);
                    $this->success('更新成功', Cookie('__forward__'));
                }
            } else {
                $this->error($dbConfig->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
			if(!empty($id)){
				$info = db('Config')->field(true)->find($id);
				if(empty($info)){
					$this->error('获取配置信息错误');
				}
				$this->assign('info', $info);
			}

			$this->assign('typeList',get_config_type());
			$this->assign('groupList',get_config_group());
			$this->assign('meta_title', '编辑配置');
			return view();
        }
    }
    // 获取某个标签的配置参数
    public function group() {
        $id     =   Request::get('id/d',1);
        $type   =   config('CONFIG_GROUP_LIST');
		$list   =   db("Config")->where([
			['status','=',1]
			,['group','=',$id]
		])
		->field('id,name,title,extra,value,remark,type')->order('sort')->select();
        if($list) {
            $this->assign('list',$list);
        }
        $this->assign('id',$id);
        $this->assign('meta_title', $type[$id].'设置');
		return view();
    }
    /**
     * 批量保存配置
     */
    public function save($config){
        if($config && is_array($config)){
            foreach ($config as $name => $value) {
				db('Config')->where('name','=',$name)->setField('value', $value);
            }
        }
        cache('DB_CONFIG_DATA',null);
        $this->success('保存成功！');
    }

}
