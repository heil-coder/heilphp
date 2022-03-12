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

class Document extends Validate
{
    protected $rule = [
		'name'  =>  [
			'alphaDash'														//只能是字母、数字、_、-
			,'length'				=> '1,39'								//长度不合法
			,'unique'				=> 'document,name'
		]
		,'title'	=>	[
			'require'														//不能为空
			,'length'				=> '1,80'								//不能为空
		]
		,'level'	=> [
			'integer'
		]
		,'description'	=>	[
			'length'				=> '1,140'
		]
		,'category_id'	=>	[
			'require'
			,'checkCategory'
			,'checkCategoryModel'
		]
    ];

    protected $message  =   [
		'name.alphaDash'				=> '文档标识只能是字母、数字、_、-'
		,'name.length'					=> '文档标识长度需在1~39个字符之间'
		,'name.unique'					=> '文档标识已存在'

		,'title.require'				=> '标题不能为空'
		,'title.length'					=> '标题长度不能超过80个字符'

		,'level.integer'				=> '优先级只能填整数'

		,'description.length'			=> '简介长度不能超过140个字符'

		,'category_id.require'			=> '分类不能为空'
    ];

	protected function checkCategory($id,$rule,$data){
		//检查是否允许发布内容
		$publish = get_category($id, 'allow_publish');
		if($publish === false) return '该分类不允许发布内容';
		//检查类型是否允许发布
		$data['type']	=	!empty($data['type']) ? $data['type'] : 2;
		$type = get_category($id, 'type');
		$type = explode(",", $type);
		return in_array($data['type'], $type) ? true : '该分类不允许发布该类型内容';
	}
	protected function checkCategoryModel($id,$rule,$data){
		$cate   =   get_category($id);
		$array  =   explode(',', $data['pid'] ? $cate['model_sub'] : $cate['model']);
		return in_array($data['model_id'], $array) ? true : '该分类没有绑定当前模型';
	}
}
