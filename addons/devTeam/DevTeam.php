<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace addons\devTeam;
use app\common\controller\Addon;

/**
 * 开发团队信息插件
 * @author thinkphp
 */

    class DevTeam extends Addon{

        public $info = array(
            'name'=>'DevTeam',
            'title'=>'开发团队信息',
            'description'=>'开发团队成员信息',
            'status'=>1,
            'author'=>'HeilPHP',
            'version'=>'0.1'
        );

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的AdminIndex钩子方法
        public function adminIndex($param){
            $config = $this->getConfig();
            $this->assign('addons_config', $config);
            if($config['display'])
                $this->display('widget');
        }
    }
