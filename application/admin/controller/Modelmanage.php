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
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use Request;

/**
 * 模型管理控制器
 * @author huajie <banhuajie@163.com>
 * @modify Jason	<1878566968@qq.com>
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
     * 编辑页面初始化
     */
    public function edit(){
        $id = Request::param('id/d','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }

        /*获取一条记录的详细数据*/
        $Model = model('Modelmanage');
        $data = $Model->field(true)->find($id)->toArray();
        if(!$data){
            $this->error($Model->getError());
        }
        $data['attribute_list'] = empty($data['attribute_list']) ? [] : explode(",", $data['attribute_list']);
        $fields = db('Attribute')->where('model_id',$data['id'])->column('id,name,title,is_show');
        $fields = empty($fields) ? array() : $fields;
        // 是否继承了其他模型
        if($data['extend'] != 0){
            $extend_fields  = db('Attribute')->where('model_id',$data['extend'])->column('id,name,title,is_show');
            $fields        += $extend_fields;
        }
        
        // 梳理属性的可见性
        foreach ($fields as $key=>$field){
            if (!empty($data['attribute_list']) && !in_array($field['id'], $data['attribute_list'])) {
                $fields[$key]['is_show'] = 0;
            }
        }
        
        // 获取模型排序字段
        $field_sort = json_decode($data['field_sort'], true);
        if(!empty($field_sort)){
            foreach($field_sort as $group => $ids){
                foreach($ids as $key => $value){
                    $fields[$value]['group']  =  $group;
                    $fields[$value]['sort']   =  $key;
                }
            }
        }
        
        // 模型字段列表排序
        $fields = list_sort_by($fields,"sort");

        $this->assign('fields', $fields);
        $this->assign('info', $data);
        $this->assign('meta_title','编辑模型');
		return view();
    }
    /**
     * 更新一条数据
     */
    public function update(){
        $res = model('Modelmanage')->edit();

        if(!$res){
            $this->error(model('Modelmanage')->error);
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
    /**
     * 生成一个模型
     * @author huajie <banhuajie@163.com>
     */
    public function generate(){
        if(!Request::isPost()){
            //获取所有的数据表
            $tables = model('Modelmanage')->getTables();

            $this->assign('id', '');
            $this->assign('tables', $tables);
            $this->assign('meta_title','生成模型');
			return view();
        }else{
            $table = Input('post.table');
            empty($table) && $this->error('请选择要生成的数据表！');
            $res = model('Modelmanage')->generate($table,Input('post.name'),Input('post.title'));
            if($res){
                $this->success('生成模型成功！', Url('index'));
            }else{
                $this->error(model('Model')->error);
            }
        }
    }
}
