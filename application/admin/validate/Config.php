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

class Config extends Validate
{
    protected $rule = [
		'name'  =>  [
			'require'														//不能为空
			,'alphaDash'														//只能是字母
			,'length'				=> '3,30'								//长度不合法
			,'unique'				=> 'config,name'				//被占用
		]
		,'title'	=>	[
			'require'														//不能为空
			,'chsDash'														//只能是汉字、字母、数字、_、-
			,'length'				=> '2,30'								//长度不合法	
		]
		,'group'	=>	[
			'require'
			,'num'					=> 'integer'
			,'gt'					=> '0'
		]
    ];

    protected $message  =   [
		'name.require'						=> '配置名称不能为空'
		,'name.alphaDash'					=> '配置名称只能为字母、数字、-、_'
		,'name.length'						=> '配置名称长度需在3～30个字符之间'
		,'name.unique'						=> '配置名称已存在'

		,'title.require'					=> '配置说明不能为空'
		,'title.chsDash'					=> '配置说明只能是汉字、字母、数字、_、-'
		,'title.length'						=> '配置说明长度需在2～30之间'

		,'group.require'					=> '配置分组不能未空'
		,'group.num'						=> '请正确选择配置分组'
		,'group.gt'							=> '请正确选择配置分组'
    ];

}
