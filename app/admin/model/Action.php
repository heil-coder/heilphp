<?php

namespace app\admin\model;
use think\Model;

/**
 * 行为模型
 */

class Action extends Model {
	protected $autoWriteTimestamp = true;
    /* 自动完成规则 */
	protected $auto = ['status'=>1];

    /**
     * 新增或更新一个行为
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     */
    public function edit(){
        /* 获取数据对象 */
        $data = input('post.');
        if(empty($data)){
            return false;
        }

        /* 添加或新增行为 */
        if(empty($data['id'])){ //新增数据
            $id = $this->save($data); //添加行为
            if(!$id){
                $this->error = '新增行为出错！';
                return false;
            }
        } else { //更新数据
            $status = $this->get($data['id'])->save($data); //更新基础内容
            if(false === $status){
                $this->error = '更新行为出错！';
                return false;
            }
        }
        //删除缓存
        cache('action_list', null);

        //内容添加或更新完成
        return $data;

    }

}
