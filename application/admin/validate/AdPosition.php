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

class AdPosition extends Validate
{
    protected $rule = [
		'title'  =>  [
			'require'														//不能为空
			,'chsDash'														//只能是汉字、字母、数字、_、-
			,'length'				=> '2,30'								//长度不合法
		]
		,'name'	=>	[
			'alphaDash'
		]
		,'width'	=>	[
			'require'
			,'alphaNum'
		]
		,'height'	=>	[
			'require'
			,'alphaNum'
		]


    ];

    protected $message  =   [
		'title.require'						=> '广告位名称不能为空'
		,'title.chsDash'					=> '广告位名称只能是汉字、字母、数字、_、-'
		,'title.length'						=> '广告位名称长度需在2～30之间'

		,'name.alphaDash'					=> '广告位标识只能是字母、数字、_、-'

		,'width.require'					=> '请填写广告位宽度'
		,'width.alphaNum'					=> '广告位宽度不合法'

		,'height.require'					=> '请填写广告位高度'
		,'height.alphaNum'					=> '广告位高度不合法'
    ];

}
