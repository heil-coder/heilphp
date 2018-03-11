<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2013 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace app\common\behavior;
use Config;
use think\Loader;
use Hook;
//defined('THINK_PATH') or exit();

// 初始化钩子信息
class InitHookBehavior{

    // 行为扩展的执行入口必须是run
    public static function run(/*&$content*/){
        if(defined('BIND_MODULE') && BIND_MODULE === 'install') return;
        $path = dirname($_SERVER['SCRIPT_FILENAME']);

        if (PHP_SAPI == 'cli') {
            $rootPath = realpath($path) . '/';
        } else {
            $rootPath = realpath($path . '/../') . '/';
        }

		cache('hooks',null);
        $data = cache('hooks');
        if(!$data){
            $hooks = Db('Hooks')->column('name,addons');
            foreach ($hooks as $key => $value) {
                if($value){
					$map = [];
                    $map[]  =   ['status','=',1];
                    $names          =   explode(',',$value);
                    $map[]    =   ['name','IN',$names];
                    $data = Db('Addons')->where($map)->column('id,name');
                    if($data){
                        $addons = array_intersect($names, $data);
                        Hook::add($key,array_map('get_addon_class',$addons));
                    }
                }
            }
            cache('hooks',Hook::get());
        }else{
            Hook::import($data,false);
        }
    }
}
