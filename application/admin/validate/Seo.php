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

class Seo extends Validate
{
    protected $rule = [
		'title'  =>  [
			'require'														//不能为空
			,'chsDash'														//只能是汉字、字母、数字、_、-
			,'length'				=> '2,30'								//长度不合法
		]
		,'controller'	=>	[
			'alphaDash'
		]
		,'action'	=>	[
			'alphaDash'
		]
		,'seo_title'	=>	[
			'require'														//不能为空
		]
    ];

    protected $message  =   [
		'title.require'						=> '设置说明不能为空'
		,'title.chsDash'					=> '设置说明能是汉字、字母、数字、_、-'
		,'title.length'						=> '设置说明长度需在2～30之间'

		,'controller.alphaDash'				=> '控制器只能为字母、数字、_、-'

		,'action.alphaDash'					=> '方法只能为字母、数字、_、-'

		,'seo_title.require'				=> 'SEO标题不能为空'
    ];

}
