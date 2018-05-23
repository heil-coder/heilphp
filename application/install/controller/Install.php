<?php
/**
 * HeilPHP
 * @Copyright (c) 2018 http://www.heilphp.com All rights reserved.
 * @Author Jason <1878566968@qq.com>
 */
namespace app\install\controller;

use think\Controller;
use Request;
use Session;
use Cache;
use Url;
use Db;
use Env;

class Install extends Controller{
	/**
	 * 初始化方法 
	 */
    protected function initialize(){
        if(is_file(env('root_path') . 'data/install.lock')){
            $this->error('已经成功安装了HeilPHP，请不要重复安装!');
        }
    }

    //安装第一步，检测运行所需的环境设置
    public function step1(){
		session(null);
		Cache::clear();
		Session::set('error', false);

        //环境检测
        $env = check_env();

        //目录文件读写检测
        if(IS_WRITE){
            $dirfile = check_dirfile();
            $this->assign('dirfile', $dirfile);
        }

        //函数检测
        $func = check_func();

		Session::set('step', 1);

        $this->assign('env', $env);
        $this->assign('func', $func);

		return view();
    }

    //安装第二步，创建数据库
    public function step2($db = null, $admin = null){
		if (Request::isPost()){
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
                list($DB['type'], $DB['hostname'], $DB['database'], $DB['username'], $DB['password'],
                     $DB['hostport'], $DB['prefix']) = $db;
                //缓存数据库配置
				Session::set('db_config', $DB);

                //创建数据库
                $dbname = $DB['database'];
                $Db = Db::connect($DB);
                $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
				$Db->execute($sql) || $this->error($Db::getError());
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
        if(Session::get('step') != 2){
            $this->redirect('step2');
        }

		echo $this->fetch();
		
        if(Session::get('update')){
            $db = Db::connect();
            //更新数据表
            update_tables($db, C('DB_PREFIX'));
        }else{
            //连接数据库
            $dbconfig = Session::get('db_config');
            $db = Db::connect($dbconfig);
            //创建数据表
            create_tables($db, $dbconfig['prefix']);
            //注册创始人帐号
            $salt= build_salt();
            $admin = Session::get('admin_info');
            register_administrator($db, $dbconfig['prefix'], $admin, $salt);

            //创建配置文件
            $conf   =   write_config($dbconfig);
			Session::set('config_file',$conf);
        }

        if(Session::get('error')){
			show_msg('安装错误');
        } else {
			Session::set('step', 3);
			show_msg('安装成功，即将跳转');
			complete_redirect();
        }
    }
}
