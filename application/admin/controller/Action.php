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
 * 行为控制器
 * @author Jason <1878566968@qq.com>
 */
class Action extends Admin {

    /**
     * 行为日志列表
	 * @author Jason <1878566968@qq.com>
     */
    public function log(){
        //获取列表数据
        $map[]    =   ['status','>=', 0];
        $list   =   $this->getListing('ActionLog', $map);
        !empty($list) && $list = $list->toArray()['data'];
        int_to_string($list);
        foreach ($list as $key=>$value){
            $model_id                  =   get_model_field($value['model'],"name","id");
            $list[$key]['model_id']    =   $model_id ? $model_id : 0;
        }
        $this->assign('_list', $list);
        $this->assign('meta_title','行为日志');
		return view();
    }

    /**
     * 查看行为日志
	 * @author Jason <1878566968@qq.com>
     */
    public function detail($id = 0){
        empty($id) && $this->error('参数错误！');

        $info = db('ActionLog')->field(true)->find($id);

        $this->assign('info', $info);
        $this->assign('meta_title','查看行为日志');
		return view();
    }

    /**
     * 删除日志
     * @param mixed $ids
     * @author huajie <banhuajie@163.com>
     */
    public function remove($ids = 0){
        empty($ids) && $this->error('参数错误！');
        if(is_array($ids)){
            $map[] = ['id','in', $ids];
        }elseif (is_numeric($ids)){
            $map[] = ['id','=',$ids];
        }
        $res = db('ActionLog')->where($map)->delete();
        if($res !== false){
            $this->success('删除成功！');
        }else {
            $this->error('删除失败！');
        }
    }

    /**
     * 清空日志
     */
    public function clear(){
        $res = db('ActionLog')->where('1=1')->delete();
        if($res !== false){
            $this->success('日志清空成功！');
        }else {
            $this->error('日志清空失败！');
        }
    }

}
