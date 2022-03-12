<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use Request;

/**
 * 后台配置控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 * @modify Jason	<1878566968@qq.com>
 */
class Config extends Admin{

    /**
     * 配置管理
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     * @modify Jason	<1878566968@qq.com>
     */
    public function index(){
        /* 查询条件初始化 */
        $map = array();
        $map[]  = ['status','=',1];
        if(!empty(input('param.group'))){
            $map[]   =  ['group','=',input('param.group/d',0)];
        }
        if(!empty(input('param.name/s',''))){
            $map[]    =   ['name','like', '%' . input('param.name/s','') . '%'];
        }

        $list = $this->getListing('Config', $map,'sort,id');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('group',config('CONFIG_GROUP_LIST'));
        $this->assign('group_id',Request::param('group/d',0));
        $this->assign('list', $list);
        $this->assign('meta_title', '配置管理');
		return view();
    }
    /**
     * 编辑配置
     */
    public function edit($id = 0){
        if(Request::isPost()){
            $mConfig = model('Config');
			$res = $mConfig->edit();
            if($res === false){
				$this->error($mConfig->getError() ?: '更新失败');
			}else{
				$this->success('更新成功', Cookie('__forward__'));
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
        $id     =   Request::param('id/d',1);
        $type   =   config('CONFIG_GROUP_LIST');
		$list   =   db('Config')->where([
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

    /**
     * 删除配置
     */
    public function del(){
        $id = array_unique(Request::param('id/a',[]));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        if(model('Config')->where('id','in',$id)->delete()){
            cache('DB_CONFIG_DATA',null);
            //记录行为
            //action_log('update_config','config',$id,UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
    /**
     * 配置排序
     */
    public function sort(){
        if(Request::isGet()){
            $ids = Request::param('ids');

            //获取排序的数据
			$map[] = ['status','>',-1];

            if(!empty($ids)){
                $map['id'] = ['id','in',$ids];
            }elseif(Request::param('group')){
                $map['group']	=	['group','=',Request::param('group')];
            }
            $list = model('Config')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
			$this->assign('meta_title','配置排序');
			return view();
        }elseif (Request::isPost()){
            $ids = Request::post('ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = model('Config')->where('id',$value)->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！',Cookie('__forward__'));
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}
