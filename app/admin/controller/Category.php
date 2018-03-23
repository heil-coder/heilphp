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
 * 后台分类管理控制器
 */
class Category extends Admin {

    /**
     * 分类管理列表
     */
    public function index(){
        $tree = model('Category')->getTree(0,'id,name,title,sort,pid,allow_publish,status');
        $this->assign('tree', $tree);
        Config('_SYS_GET_CATEGORY_TREE_', true); //标记系统获取分类树模板
        $this->assign('meta_title','分类管理');
		return view();
    }

    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     */
    public function tree($tree = null){
        config('_SYS_GET_CATEGORY_TREE_') || $this->_empty();
        $this->assign('tree', $tree);
		return $this->fetch('tree');
    }

    /* 编辑分类 */
    public function edit($id = null, $pid = 0){
        $Category = model('Category');

        if(Request::isPost()){ //提交表单
            if(false !== $Category->edit()){
                $this->success('编辑成功！', Url('index'));
            } else {
                $error = $Category->getError();
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = '';
            if($pid){
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }

            /* 获取分类信息 */
            $info = $id ? $Category->info($id) : '';

            $this->assign('info',       $info);
            $this->assign('category',   $cate);
            $this->assign('meta_title','编辑分类');
			return view();
        }
    }

    /* 新增分类 */
    public function add($pid = 0){
        $Category = model('Category');

        if(Request::isPost()){ //提交表单
            if(false !== $Category->edit()){
                $this->success('新增成功！', Url('index'));
            } else {
                $error = $Category->error;
                $this->error(empty($error) ? '未知错误！' : $error);
            }
        } else {
            $cate = array();
            if($pid){
                /* 获取上级分类信息 */
                $cate = $Category->info($pid, 'id,name,title,status');
                if(!($cate && 1 == $cate['status'])){
                    $this->error('指定的上级分类不存在或被禁用！');
                }
            }

            /* 获取分类信息 */
            $this->assign('info',       null);
            $this->assign('category', $cate);
            $this->assign('meta_title','新增分类');
			return view('edit');
        }
    }

    /**
     * 删除一个分类
     */
    public function remove(){
        $cate_id = Request::param('id');
        if(empty($cate_id)){
            $this->error('参数错误!');
        }

        //判断该分类下有没有子分类，有则不允许删除
        $child = db('Category')->where('pid',$cate_id)->field('id')->select();
        if(!empty($child)){
            $this->error('请先删除该分类下的子分类');
        }

        //判断该分类下有没有内容
        $document_list = db('Document')->where('category_id',$cate_id)->field('id')->select();
        if(!empty($document_list)){
            $this->error('请先删除该分类下的文章（包含回收站）');
        }

        //删除该分类信息
        $res = db('Category')->delete($cate_id);
        if($res !== false){
            //记录行为
            //action_log('update_category', 'category', $cate_id, UID);
            $this->success('删除分类成功！');
        }else{
            $this->error('删除分类失败！');
        }
    }

    /**
     * 操作分类初始化
     * @param string $type
     */
    public function operate($type = 'move'){
        //检查操作参数
        if(strcmp($type, 'move') == 0){
            $operate = '移动';
        }elseif(strcmp($type, 'merge') == 0){
            $operate = '合并';
        }else{
            $this->error('参数错误！');
        }
        $from = Request::param('from/d');;
        empty($from) && $this->error('参数错误！');

        //获取分类
		$map = [
			['status','=',1]
			,['id','<>',$from]
		];
        $list = db('Category')->where($map)->field('id,pid,title')->select();


        //移动分类时增加移至根分类
        if(strcmp($type, 'move') == 0){
        	//不允许移动至其子孙分类
        	$list = tree_to_list(list_to_tree($list));

        	$pid = db('Category')->getFieldById($from, 'pid');
        	$pid && array_unshift($list, array('id'=>0,'title'=>'根分类'));
        }

        $this->assign('type', $type);
        $this->assign('operate', $operate);
        $this->assign('from', $from);
        $this->assign('list', $list);
        $this->assign('meta_title',$operate.'分类');
		return view();
    }

    /**
     * 移动分类
     */
    public function move(){
        $to = Request::param('to');;
        $from = Request::param('from');;
        $res = db('Category')->where(array('id'=>$from))->setField('pid', $to);
        if($res !== false){
            $this->success('分类移动成功！', Url('index'));
        }else{
            $this->error('分类移动失败！');
        }
    }

    /**
     * 合并分类
     */
    public function merge(){
        $to = Request::param('to');;
        $from = Request::param('frn');
        $Model = db('Category');

        //检查分类绑定的模型
        $from_models = explode(',', $Model->getFieldById($from, 'model'));
        $to_models = explode(',', $Model->getFieldById($to, 'model'));
        foreach ($from_models as $value){
            if(!in_array($value, $to_models)){
                $this->error('请给目标分类绑定' . get_document_model($value, 'title') . '模型');
            }
        }

        //检查分类选择的文档类型
        $from_types = explode(',', $Model->getFieldById($from, 'type'));
        $to_types = explode(',', $Model->getFieldById($to, 'type'));
        foreach ($from_types as $value){
            if(!in_array($value, $to_types)){
                $types = config('DOCUMENT_MODEL_TYPE');
                $this->error('请给目标分类绑定文档类型：' . $types[$value]);
            }
        }

        //合并文档
        $res = db('Document')->where('category_id',$from)->setField('category_id', $to);

        if($res !== false){
            //删除被合并的分类
            $Model->delete($from);
            $this->success('合并分类成功！', Url('index'));
        }else{
            $this->error('合并分类失败！');
        }

    }
}
