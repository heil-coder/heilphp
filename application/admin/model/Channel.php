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
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;
use think\Model;

/**
 * 导航模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 * @modify Jason <1878566968@qq.com>
 */

class Channel extends Model {
	protected $autoWriteTimestamp = true;

	protected $insert = ['status'=>1];

	/**
	 * 编辑配置
	 */
	public function edit(){
        $data = Request()->only('id,pid,title,url,sort,create_time,update_time,status,target');
		if(empty($data)){
			return false;	
		}

		$validate = new \app\admin\validate\Channel;
		if(!$validate->check($data)){
			$this->error = $validate->getError();
			return false;
		}

		if(empty($data['id'])){
			$res = $this->save($data);
			$data['id'] = $this->id;
		}
		else{
			$res = $this->allowField(true)->save($data,['id'=>$data['id']]);
		}
		if($res === false){
			return false;	
		}
		else{
			action_log('update_channel', '频道', $data['id'], UID);
			return $res;
		}
	}
}
