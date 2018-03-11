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
 * 模型管理控制器
 * @author Jason	<1878566968@qq.com>
 */
class Modelmanage extends Admin {

    /**
     * 模型管理首页
     */
    public function index(){
        $list = $this->getListing('Model');
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->assign('meta_title','模型管理');
		return view();
    }
    /**
     * 新增页面初始化
     */
    public function add(){
        //获取所有的模型
        $models = db('Model')->where('extend',0)->field('id,title')->select();

        $this->assign('models', $models);
        $this->assign('meta_title','新增模型');
		return view();
    }
    /**
     * 更新一条数据
     */
    public function update(){
        $res = model('Modelmanage')->edit();

        if(!$res){
            $this->error(model('Modelmanage')->getError());
        }else{
            $this->success($res['id']?'更新成功':'新增成功', Cookie('__forward__'));
        }
    }
    /**
     * 删除一条数据
     */
    public function del(){
        $ids = Request::param('ids');
        empty($ids) && $this->error('参数不能为空！');
        $ids = explode(',', $ids);
        foreach ($ids as $value){
            $res = model('Modelmanage')->del($value);
            if(!$res){
                break;
            }
        }
        if(!$res){
            $this->error(model('Modelmanage')->getError());
        }else{
            $this->success('删除模型成功！');
        }
    }
}
