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
use Env;

/**
 * 插件模型
 * @author Jason <1878566968@qq.com>
 */

class Addons extends Model {
	protected $autoWriteTimestamp = true;
    /**
     * 查找后置操作
     */
    //protected function _after_find(&$result,$options) {

    //}

    //protected function _after_select(&$result,$options){

    //    foreach($result as &$record){
    //        $this->_after_find($record,$options);
    //    }
    //}


    /**
     * 获取插件列表
     * @param string $addon_dir
     */
    public function getList($addon_dir = ''){
        if(!$addon_dir)
            $addon_dir = Env::get('root_path').HEILPHP_ADDON_PATH;
        $dirs = array_map('basename',glob($addon_dir.'*', GLOB_ONLYDIR));
        if($dirs === FALSE || !file_exists($addon_dir)){
            $this->error = '插件目录不可读或者不存在';
            return FALSE;
        }
		$addons			=	array();
		$where[]	=	['name','in',$dirs];
		$list			=	$this->where($where)->field(true)->select()->toArray();
		foreach($list as $addon){
			$addon['uninstall']		=	0;
			$addons[lcfirst($addon['name'])]	=	$addon;
		}
        foreach ($dirs as $value) {
            if(!isset($addons[$value])){
				$class = get_addon_class(ucfirst($value));
				if(!class_exists($class)){ // 实例化插件失败忽略执行
					\think\facade\Log::record('插件'.$value.'的入口文件不存在！');
					continue;
				}
                $obj    =   new $class;
				$addons[$value]	= $obj->info;
				if($addons[$value]){
					$addons[$value]['uninstall'] = 1;
                    unset($addons[$value]['status']);
				}
			}
        }
        int_to_string($addons, array('status'=>array(-1=>'损坏', 0=>'禁用', 1=>'启用', null=>'未安装')));
        $addons = list_sort_by($addons,'uninstall','desc');
        return $addons;
    }

    /**
     * 获取插件的后台列表
     */
    public function getAdminList(){
        $admin = array();
        $db_addons = $this->where("status=1 AND has_adminlist=1")->field('title,name')->select();
        if($db_addons){
            foreach ($db_addons as $value) {
                $admin[] = array('title'=>$value['title'],'url'=>"Addons/adminList?name={$value['name']}");
            }
        }
        return $admin;
    }
}
