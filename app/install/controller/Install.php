<?php
/**
 * HeilPHP
 * @Copyright (c) 2018 http://www.heilphp.com All rights reserved.
 * @Author Jason <1878566968@qq.com>
 */
namespace app\install\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Url;
use think\Db;

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
    public function step2($db = null, $admin = null){
		if (Request::instance()->isPost()){
            //检测管理员信息
            if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
                $this->error('请填写完整管理员信息');
            } else if($admin[1] != $admin[2]){
                $this->error('确认密码和密码不一致');
            } else {
                $info = array();
                list($info['username'], $info['password'], $info['repassword'], $info['email'])
                = $admin;
                //缓存管理员信息
				Session::set('admin_info', $info);
            }

            //检测数据库配置
            if(!is_array($db) || empty($db[0]) ||  empty($db[1]) || empty($db[2]) || empty($db[3])){
                $this->error('请填写完整的数据库配置');
            } else {
                $DB = array();
                list($DB['DB_TYPE'], $DB['DB_HOST'], $DB['DB_NAME'], $DB['DB_USER'], $DB['DB_PWD'],
                     $DB['DB_PORT'], $DB['DB_PREFIX']) = $db;
                //缓存数据库配置
				Session::set('db_config', $DB);

                //创建数据库
                $dbname = $DB['DB_NAME'];
                unset($DB['DB_NAME']);
                $db  = Db::connect($DB);
                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
                $db->execute($sql) || $this->error($db->getError());
            }

            //跳转到数据库安装页面
            $this->redirect('step3');
        } else {
			if(Session::get('update')){
                Session::set('step', 2);
				return view('update');
            }else{
				Session::get('error') && $this->error('环境检测没有通过，请调整环境后重试！');

                $step = Session::get('step');
                if($step != 1 && $step != 2){
                    $this->redirect('step1');
                }

				Session::set('step', 2);
				$this->assign('url',Url::build('install/install/step2'));
				return view();
            }
        }


		return view();
    }

    //安装第三步，安装数据表，创建配置文件
    public function step3(){

    }
}
