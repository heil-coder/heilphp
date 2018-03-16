<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class Member extends Validate
{
    protected $rule = [
		'username'  =>  [
			'chsDash'														//用户名只能是汉字、字母、数字和下划线_及破折号-
			,'length'				=> '1,30'								//用户名长度不合法
			,'checkDenyMember'												//用户名禁止注册
			,'unique'				=> 'member,username'					//用户名被占用
		]
		,'password'	=>	[
			'length'				=> '6,30'								//密码长度不合法	
		]
		//,'repassword' =>[
		//	'confirm' =>			'password'								//确认密码和密码不一致
		//]
		,'email'	=>	[
			'email'															//邮箱格式不正确
			,'length'				=> '3,32'								//邮箱长度不合法
			,'checkDenyEmail'												//邮箱禁止注册
			,'unique'				=> 'member,email'			//邮箱被占用
		]
		,'mobile'	=> [
			'mobile'														//手机格式不正确
			,'checkDenyMobile'												//手机禁止注册
			,'unique'				=> 'member,mobile'			//手机号被占用
		]
    ];

    protected $message  =   [
        'username.chsDash'					=> '用户名只能是汉字、字母、数字和下划线_及破折号-'
        ,'username.length'					=> '用户名长度不合法'
        ,'username.checkDenyMember'			=> '用户名禁止注册'
        ,'username.unique'					=> '用户名被占用'

        ,'password.length'					=> '密码长度不合法'
		//,'repassword.confirm'				=> '确认密码和密码不一致'

		,'email.email'						=> '邮箱格式不正确'
		,'email.length'						=> '邮箱长度不合法'
		,'email.checkDenyEmail'				=> '邮箱禁止注册'
		,'email.unique'						=> '邮箱被占用'

		,'mobile.mobile'					=> '手机格式不正确'
		,'mobile.checkDenyMobile'			=> '手机禁止注册'
		,'mobile.unique'					=> '手机号被占用'
    ];

	/**
	 * 检测用户名是不是被禁止注册
	 * @param  string $username 用户名
	 * @return boolean          ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMember($username){
		return true; //TODO: 暂不限制，下一个版本完善,读取后台设置的禁用名过滤
	}

	/**
	 * 检测邮箱是不是被禁止注册
	 * @param  string $email 邮箱
	 * @return boolean       ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyEmail($email){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 检测手机是不是被禁止注册
	 * @param  string $mobile 手机
	 * @return boolean        ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMobile($mobile){
		return true; //TODO: 暂不限制，下一个版本完善
	}
}
