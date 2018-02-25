<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\behavior;
use app\common\controller\Addon;

class SiteStatistics extends Addon{
    public $info = array(
        'name'=>'SiteStatistics',
        'title'=>'站点统计信息',
        'description'=>'统计站点的基础信息',
        'status'=>1,
        'author'=>'thinkphp',
        'version'=>'0.1'
    );
    public function install(){
        return true;
    }

    public function uninstall(){
        return true;
    }
    //实现的AdminIndex钩子方法
    public function AdminIndex($param){
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if($config['display']){
            $info['user']		=	db('Member')->count();
            //$info['action']		=	db('ActionLog')->count();
            //$info['document']	=	db('Document')->count();
            //$info['category']	=	db('Category')->count();
            //$info['model']		=	db('Model')->count();
			//$this->display('info');
        }
    }
}
