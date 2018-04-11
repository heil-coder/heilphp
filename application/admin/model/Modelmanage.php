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
 * 文档基础模型
 * @modify Jason<1878566968@qq.com>
 */
class Modelmanage extends Model{
	protected $name= 'model';
	protected $autoWriteTimestamp = true;
    /* 自动验证规则 */

    /* 自动完成规则 */
	protected $auto = ['name','status'=>1,'field_sort','attribute_list'];
	protected function setNameAttr($value){
		return strtolower($value);
	}
	protected function setFieldSortAttr($value){
        return empty($value) ? '' : json_encode($value);
	}
	protected function setAttributeListAttr($value){
        return empty($value) ? '' : implode(',', $value);
	}

    /**
     * 检查列表定义
     * @param type $data
     */
    protected function checkListGrid($data) {
        return I("post.extend") != 0 || !empty($data);
    }

    /**
     * 新增或更新一个文档
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     */
    public function edit(){
        /* 获取数据对象 */
        $data = Request::only('id,name,title,extend,engine_type,need_pk');
        if(empty($data)){
            return false;
        }

        /* 添加或新增基础内容 */
        if(empty($data['id'])){ //新增数据
            $id = $this->save($data); //添加基础内容
            if(!$id){
                $this->error = '新增模型出错！';
                return false;
            }
			$data['id'] = $this->id;
        } else { //更新数据
            $status = $this->get($data['id'])->save($data); //更新基础内容
            if(false === $status){
                $this->error = '更新模型出错！';
                return false;
            }
        }
        // 清除模型缓存数据
        cache('DOCUMENT_MODEL_LIST', null);

        //记录行为
        action_log('update_model','model',$data['id'] ? $data['id'] : $id,UID);

        //内容添加或更新完成
        return $data;
    }

    /**
     * 获取指定数据库的所有表名
     */
    public function getTables(){
        return $this->getConnection()->getTables();
    }

    /**
     * 根据数据表生成模型及其属性数据
     */
    public function generate($table,$name='',$title=''){
        //新增模型数据
        if(empty($name)){
            $name = $title = substr($table, strlen(config('database.prefix')));
        }
        $data = array('name'=>$name, 'title'=>$title);
        //$data = $this->create($data);
        if($data){
            $res = $this->save($data);
            if(!$res){
                return false;
            }
        }else{
            $this->error = $this->getError();
            return false;
        }

        //新增属性
        $fields = db()->query('SHOW FULL COLUMNS FROM '.$table);
        foreach ($fields as $key=>$value){
            $value  =   array_change_key_case($value);
            //不新增id字段
            if(strcmp($value['field'], 'id') == 0){
                continue;
            }

            //生成属性数据
            $data = array();
            $data['name'] = $value['field'];
            $data['title'] = $value['comment'];
            $data['type'] = 'string';	//TODO:根据字段定义生成合适的数据类型
            //获取字段定义
            $is_null = strcmp($value['null'], 'NO') == 0 ? ' NOT NULL ' : ' NULL ';
            $data['field'] = $value['type'].$is_null;
            $data['value'] = $value['default'] == null ? '' : $value['default'];
            $data['model_id'] = $this->id;
            //$_POST = $data;		//便于自动验证
            model('Attribute')->edit($data, false);
        }
        return $res;
    }

    /**
     * 删除一个模型
     * @param integer $id 模型id
     */
    public function del($id){
        //获取表名
        $model = $this->field('name,extend')->find($id);
        if($model['extend'] == 0){
            $table_name = config('database.prefix').strtolower($model['name']);
        }elseif($model['extend'] == 1){
            $table_name = config('database.prefix').'document_'.strtolower($model['name']);
        }else{
            $this->error = '只支持删除文档模型和独立模型';
            return false;
        }

        //删除属性数据
        db('Attribute')->where('model_id',$id)->delete();
        //删除模型数据
        $this->get($id)->delete();
        //删除该表
        $sql = <<<sql
                DROP TABLE {$table_name};
sql;
        $res = db()->execute($sql);
        return $res !== false;
    }
}
