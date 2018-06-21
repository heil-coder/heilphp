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
//use think\model\concern\SoftDelete;
/**
 * 菜单模型 
 */

class Menu extends Model {
    /* 自动完成规则 */
	protected $auto = ['status'=>1];
	protected function setTitleAttr($value){
		return htmlspecialchars($value);
	}
//	use SoftDelete;
//	protected $deleteTime = 'delete_time';

	/**
	 * 编辑配置
	 */
	public function edit(){
        $data = Request()->only('title,pid,sort,url,hide,tip,group,is_dev,status');
		if(empty($data)){
			return false;	
		}

		$validate = new \app\admin\validate\Menu;
		if(!$validate->check($data)){
			$this->error = $validate->getError();
			return false;
		}

		if(empty($data['id'])){
			$res = $this->save($data);
			$data['id'] = $this->id;
		}
		else{
			$res = $this->get($data['id'])->save($data);
		}
		if($res === false){
			return false;	
		}
		else{
			return $res;
		}
        action_log('update_menu', 'Menu', $data['id'], UID);
	}
}
