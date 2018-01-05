<?php
/**
 * HeilPHP
 * @Copyright (c) 2018 http://www.heilphp.com All rights reserved.
 * @Author Jason <1878566968@qq.com>
 */
namespace app\install\controller;

use think\Controller;

class Install extends Controller{
	/**
	 * 初始化方法 
	 */
    protected function _initialize(){
        //if('安装锁定文件如存')){
        //    $this->error('已经成功安装了OneThink，请不要重复安装!');
        //}
    }

    //安装第一步，检测运行所需的环境设置
    public function step1(){
        session('error', false);

        //环境检测
        $env = check_env();

        //目录文件读写检测
        if(IS_WRITE){
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //函数检测
        $func = check_func();

        session('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);

		return view();
    }

    //安装第二步，创建数据库
    public function step2(){
		return view();
    }

    //安装第三步，安装数据表，创建配置文件
    public function step3(){

    }
}
