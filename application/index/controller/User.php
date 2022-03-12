<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\index\controller;
use app\user\api\UserApi;


/**
 * swagger: 用户相关
 */
class User extends Base{

	/**
	 * post: 首页
	 * path: index
	 * method: index
	 */
	public function index(){
		
	}
	/**
	 * post: 用户注册
	 * path: register
	 * method: register
	 * param: username - {string} 用户名
	 * param: password - {string} 密码
	 * param: repassword - {string} 确认密码
	 * param: email - {string} 邮箱
	 * param: captcha - {string} 验证码
	 */
	public function register($username = '', $password = '', $repassword = '', $email = '', $captcha = null){
        if(!Config('USER_ALLOW_REGISTER')){
            $this->error('注册已关闭');
        }
		if(Request()->isPost()){ //注册用户
			/* 检测验证码 */
			if(!captcha_check($captcha)){
				$this->error('验证码输入错误！');
			}

			/* 调用注册接口注册用户 */
            $User = new UserApi;
			$uid = $User->register($username, $password,$repassword, $email);
			if(0 < $uid){ //注册成功
				//TODO: 发送验证邮件
				$this->success('注册成功！',Url('login'));
			} else { //注册失败，显示错误信息
				$this->error($uid);
			}

		} else { //显示注册表单
			return view();
		}
	}
	/**
	 * post: 发送验证码
	 * path: sendVerify/{phone}/{deviceType}
	 * method: sendVerify
	 * param: phone - {string} 手机号
	 */
	public function sendVerify2($phone, $deviceType) {
		return [
			'code'		=> 200,
			'message'	=> '发送验证码',
			'data'		=> [
				'phone'			=> $phone,
				'deviceType'		=> $deviceType
			]
		];
	}
	
	/**
	 * post: 登陆
	 * path: login
	 * method: login
	 * param: phone - {string} 手机号
	 * param: password - {string} 密码
	 * param: deviceType - {int} = [0|1|2|3|4] 设备类型(0: android手机, 1: ios手机, 2: android平板, 3: ios平板, 4: pc)
	 * param: verifyCode - {string} = 0 验证码
	 */
	public function login($phone, $password, $deviceType, $verifyCode = '0') {
		return [
			'code'		=> 200,
			'message'	=> '登陆成功',
			'data'		=> [
				'phone'			=> $phone,
				'password'		=> $password,
				'deviceType'		=> $deviceType,
				'verifyCode'		=> $verifyCode
			]
		];
	}
	
	/**
	 * get: 获取配置
	 * path: profile
	 * method: profile
	 * param: keys - {string[]} 需要获取配置的Key值数组
	 */
	public function profile($keys) {
		return [
			'code'		=> 200,
			'message'	=> '获取成功',
			'data'		=> $keys
		];
	}
}
