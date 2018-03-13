<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;
use think\Model;
use Request;

/**
 * 分类模型
 */
class Category extends Model{
	protected $autoWriteTimestamp = true;

    /* 自动完成规则 */
	protected $auto = ['model','model_sub','type','reply_model','extend','status'=>1];
	//修改器
	protected function setModelAttr($value){
		if (is_null($value)) return null;
		return is_array($value) ? arr2str($value) : $value;
	}
	protected function setModel_subAttr($value){
		if (is_null($value)) return null;
		return is_array($value) ? arr2str($value) : $value;
	}
	protected function setTypeAttr($value){
		if (is_null($value)) return null;
		return is_array($value) ? arr2str($value) : $value;
	}
	protected function setReply_modelAttr($value){
		if (is_null($value)) return null;
		return is_array($value) ? arr2str($value) : $value;
	}
	protected function setExtendAttr($value){
		if (is_null($value)) return null;
		return is_array($value) ? arr2str($value) : $value;
	}
	//获取器
	protected function getModelAttr($value){
        return empty($value) ? $value : explode(',', $value);
	}
	protected function getModel_subAttr($value){
        return empty($value) ? $value : explode(',', $value);
	}
	protected function getTypeAttr($value){
        return empty($value) ? $value : explode(',', $value);
	}
	protected function getReplyAttr($value){
        return empty($value) ? $value : explode(',', $value);
	}
	protected function getExtendAttr($value){
        return empty($value) ? $value : explode(',', $value);
	}


    /**
     * 获取分类详细信息
     * @param  milit   $id 分类ID或标识
     * @param  boolean $field 查询字段
     * @return array     分类信息
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function info($id, $field = true){
        /* 获取分类信息 */
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['name'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }

    /**
     * 获取分类树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array          分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function getTree($id = 0, $field = true){
        /* 获取当前分类信息 */
        if($id){
            $info = $this->info($id);
            $id   = $info['id'];
        }

        /* 获取所有分类 */
        $map  = [['status','>', -1]];
        $list = $this->field($field)->where($map)->order('sort')->select()->toArray();
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);

        /* 获取返回数据 */
        if(isset($info)){ //指定分类则返回当前分类极其子分类
            $info['_'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }

        return $info;
    }

    /**
     * 获取指定分类子分类ID
     * @param  string $cate 分类ID
     * @return string       id列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function getChildrenId($cate) {
        $field    = 'id,name,pid,title,link_id';
        $category = $this->getTree($cate, $field);
        $ids[]    = $cate;
        foreach ($category['_'] as $key => $value) {
            $ids[] = $value['id'];
        }
        return implode(',', $ids);
    }

    /**
     * 获取指定分类的同级分类
     * @param  integer $id    分类ID
     * @param  boolean $field 查询字段
     * @return array
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function getSameLevel($id, $field = true){
        $info = $this->info($id, 'pid');
        $map = [['pid','=',$info['pid']], ['status',',',1]];
        return $this->field($field)->where($map)->order('sort')->select();
    }

    /**
     * 更新分类信息
     * @return boolean 更新状态
     */
    public function edit(){
        $data = Request::param('');//$this->create();
        if(!$data){ //数据对象创建错误
            return false;
        }

        /* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add($data);
        }else{
            $res = $this->get($data['id'])->save($data);
        }

        //更新分类缓存
        cache('sys_category_list', null);

        //记录行为
        //action_log('update_category', 'category', $data['id'] ? $data['id'] : $res, UID);

        return $res;
    }
}
