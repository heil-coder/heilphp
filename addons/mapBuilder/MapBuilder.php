<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace addons\mapBuilder;
use app\common\controller\Addon;

/**
 * 地图构建插件
 * @author Jason <1878566968@qq.com>
 */
class MapBuilder extends Addon{
	public function install(){
		return true;
	}

	public function uninstall(){
		return true;
	}

	/**
	 * 上传工具挂载的钩子
	 * @param $param array('name'=>'表单name','value'=>'表单对应的值','type'=>'地图SDK类型 可选 'baiduMap,qqMap')
	 */
	public function getCoordinate($param){
		//静态变量记录插件调用次数
		static $times = null;
		if(!$times){
			$times = 1;
		}
		else{
			$times++;
		}
		$this->assign('times',$times);
		$this->assign('param', $param);
		$this->display('getCoordinate');
	}
}
