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

class Ad extends Validate
{
    protected $rule = [
		'title'  =>  [
			'chsDash'														//只能是汉字、字母、数字、_、-
			,'length'				=> '2,30'								//长度不合法
		]



    ];

    protected $message  =   [
		'title.chsDash'						=> '广告位名称只能是汉字、字母、数字、_、-'
		,'title.length'						=> '广告位名称长度需在2～30之间'
    ];

}
