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
 * SEO模型
 * @author Jason <1878566968@qq.com>
 */

class Seo extends Model {
	protected $autoWriteTimestamp = true;

	protected $insert = ['status'=>1];

	/**
	 * 编辑
	 */
	public function edit(){
        $data = input('param.');
		if(empty($data)){
			return false;	
		}

		$validate = new \app\admin\validate\Seo;
		if(!$validate->check($data)){
			$this->error = $validate->getError();
			return false;
		}

		if(empty($data['id'])){
			$res = $this->allowField(true)->save($data);
			$data['id'] = $this->id;
		}
		else{
			$res = $this->get($data['id'])->allowField(true)->save($data);
		}
		if($res === false){
			return false;	
		}
		else{
			return $res;
		}
        action_log('update_seo', 'seo', $data['id'], UID);
	}
}
