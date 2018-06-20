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

class Model extends Validate
{
    protected $rule = [
		'name'  =>  [
			'require'														//不能为空
			,'alpha'														//只能是字母
			,'length'				=> '3,30'								//长度不合法
			,'unique'				=> 'ucenter_member,name'				//被占用
		]
		,'title'	=>	[
			'require'														//不能为空
			,'chsDash'														//只能是汉字、字母、数字、_、-
			,'length'				=> '3,30'								//长度不合法	
		]
    ];

    protected $message  =   [
		'name.require'						=> '模型标识不能为空'
		,'name.alpha'						=> '模型标识只能为字母'
		,'name.length'						=> '模型标识长度需在3～30个字符之间'
		,'name.unique'						=> '模型标识已存在'

		,'title.require'					=> '模型名称不能为空'
		,'title.chsDash'					=> '模型名称只能是汉字、字母、数字、_、-'
		,'title.length'						=> '模型名称长度需在3～30个字符之间'
    ];
	protected function sceneAdd(){
    	return $this->only(['name','title']);
	}
}
