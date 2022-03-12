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

class Channel extends Validate
{
    protected $rule = [
		'title'  =>  [
			'require'														//不能为空
			,'chsDash'														//只能是汉字、字母、数字、_、-
			,'length'				=> '2,30'								//长度不合法
		]
		,'url'	=>	[
			'require'														//不能为空
		]
    ];

    protected $message  =   [
		'title.require'					=> '标题不能为空'
		,'title.chsDash'					=> '标题只能是汉字、字母、数字、_、-'
		,'title.length'						=> '标题长度需在2～30之间'

		,'url.require'						=> '链接不能未空'
    ];

}
