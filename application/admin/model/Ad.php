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
use think\model\concern\SoftDelete;

/**
 * 广告位模型
 * @author Jason <1878566968@qq.com>
 */

class Ad extends Model {
	use SoftDelete;
	protected $autoWriteTimestamp = true;

	protected $insert = ['status'=>1];
	protected function setStartTimeAttr($value){
		if($value == ''){
			return null;
		}	
	}
	protected function setEndTimeAttr($value){
		if($value == ''){
			return null;
		}	
	}

	/**
	 * 编辑
	 */
	public function edit(){
        $data = input('param.');
		if(empty($data)){
			return false;	
		}

		$validate = new \app\admin\validate\Ad;
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
			action_log('update_ad', '广告', $data['id'], UID);
			return $res;
		}
	}
}
