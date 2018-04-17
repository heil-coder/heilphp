<?php
/**
 * HeilPHP
 * @Copyright (c) 2018 http://www.heilphp.com All rights reserved.
 * @Author Jason <1878566968@qq.com>
 */
namespace app\install\controller;

use think\Controller;
use Session;
use Env;
use Url;

class Index extends Controller
{
	//安装首页
	public function index(){
        if(is_file(Env::get('module_path') . 'data/install.lock')){
            $this->error('已经成功安装了HeilPHP，请不要重复安装!');
        }
		return view();
	}
	
    //安装完成
    public function complete(){
        $step = Session::get('step');

        if(!$step){
            $this->redirect('index');
        } elseif($step != 3) {
            $this->redirect("Install/step{$step}");
        }

        // 写入安装锁定文件
        file_put_contents(Env::get('module_path') . 'data/install.lock','lock');


		Session::delete('step');
		Session::delete('error');
		Session::delete('update');
		url::root('index.php');
		return view();
    }

}
