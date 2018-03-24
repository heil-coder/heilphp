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

class Category extends Validate
{
    protected $rule = [
		'name'			=>	[
			'require'														//标识不能为空
			,'unique'				=> 'category,name'						//标识已存在
		]
		,'title'		=>	[
			'require'														//名称不能为空
		]
		,'meta_title'	=>	[
			'length'				=> '1,50'								//网页标题不能超过50个字符
		]
		,'keywords'		=>	[
			'length'				=> '1,255'								//网页关键词不能超过255个字符
		]
		,'description'		=>	[
			'length'				=> '1,255'								//网页描述不能超过255个字符
		]
    ];

    protected $message  =   [
        'name.require'						=> '标识不能为空'
        ,'name.unique'						=> '标识已存在'

        ,'title.require'					=> '名称不能为空'
        ,'meta_title.length'				=> '网页标题不能超过50个字符'
        ,'keywords.length'					=> '网页关键词不能超过50个字符'
        ,'description.length'				=> '网页描述不能超过50个字符'
    ];
}
