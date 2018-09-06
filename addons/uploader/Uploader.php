<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace addons\uploader;
use app\common\controller\Addon;

/**
 * 上传工具插件
 * @author Jason <1878566968@qq.com>
 */
class Uploader extends Addon{
	public function install(){
		return true;
	}

	public function uninstall(){
		return true;
	}

	/**
	 * 上传工具挂载的钩子
	 * @param array('name'=>'表单name','value'=>'表单对应的值')
	 */
	public function flieUploader($data){
		$this->display('uploader');
	}
}
