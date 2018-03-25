<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use think\Controller;
use app\user\api\UserApi;
use Request;

/**
 * 后台登录控制器
 */
class Common extends Controller {

    /**
     * 后台用户登录
     */
    public function login($username = null, $password = null, $captcha = null){
        if(Request::isPost()){
            /* 检测验证码 TODO: */
            if(!captcha_check($captcha)){
                $this->error('验证码输入错误！');
            }

            /* 调用UC登录接口登录 */
            $User = model('Member');
            $res = $User->login($username, $password);
            if(true === $res){ //登录成功
                $this->success('登录成功！', Url('Index/index'));
            } else { //登录失败
                switch($res) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
					default: $error = is_numeric($res) ? '未知错误！' : $res; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                /* 读取数据库中的配置 */
                $config	=	cache('DB_CONFIG_DATA');
                if(!$config){
                    $config	=	model('Config')->lists();
                    cache('DB_CONFIG_DATA',$config);
                }
                config($config); //添加配置
                
				return view();
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            model('Member')->logout();
            session('[destroy]');
            $this->success('退出成功！', Url('login'));
        } else {
            $this->redirect('login');
        }
    }

    public function verify(){
        $verify = new \think\Verify();
        $verify->entry(1);
    }

}
