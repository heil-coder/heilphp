<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace addons\siteStat;
use app\common\controller\Addon;

/**
 * 系统环境信息插件
 * @author thinkphp
 */
class SiteStat extends Addon{

    public $info = array(
        'name'=>'SiteStat',
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
            $info['user']		=	Db('Member')->count();
            //$info['action']		=	Db('ActionLog')->count();
            //$info['document']	=	Db('Document')->count();
            //$info['category']	=	Db('Category')->count();
            //$info['model']		=	Db('Model')->count();
            $this->assign('info',$info);
            $this->display('info');
        }
    }
}
