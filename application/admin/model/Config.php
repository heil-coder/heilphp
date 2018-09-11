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
/**
 * 配置模型
 * @author Jason	<1878566968@qq.com>
 */

class Config extends Model {
	protected $autoWriteTimestamp = true;
    /* 自动完成规则 */
	protected $auto = ['status'=>1];
	protected function setNameAttr($value){
		return strtoupper($value);	
	}
    /**
     * 获取配置列表
     * @return array 配置数组
     */
    public function lists(){
        $data   = $this->where('status','=',1)->field('type,name,value')->select();
        
        $config = array();
        if($data && is_array($data)){
            foreach ($data as $value) {
                $config[$value['name']] = $this->parse($value['type'], $value['value']);
            }
        }
        return $config;
    }
	/**
	 * 编辑配置
	 */
	public function edit(){
        $data = Request()->only(['id','name','type','title','group','extra','remark','value','sort']);
		if(empty($data)){
			return false;	
		}

		$validate = new \app\admin\validate\Config;
		if(!$validate->check($data)){
			$this->error = $validate->getError();
			return false;
		}

		if(empty($data['id'])){
			$res = $this->allowField(true)->save($data);
			$data['id'] = $this->id;
		}
		else{
			$res = $this->allowField(true)->save($data,['id'=>$data['id']]);
		}
		if($res === false){
			return false;	
		}
		else{
			action_log('update_config','配置',$data['id'],UID);
			return $res;
		}
	}
    /**
     * 根据配置类型解析配置
     * @param  integer $type  配置类型
     * @param  string  $value 配置值
     */
    private function parse($type, $value){
        switch ($type) {
            case 3: //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if(strpos($value,':')){
                    $value  = array();
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k]   = $v;
                    }
                }else{
                    $value =    $array;
                }
                break;
        }
        return $value;
    }
}
