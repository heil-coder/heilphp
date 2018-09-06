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
		'title'		=>  [
			'chsDash'
			,'length'				=> '2,30'
		]
		,'position'		=>  [
			'require'
			,'num'				=> 'integer'
			,'gt'				=> '0'
		]
		,'data'		=> [
			'require'
			,'checkData'
		]
    ];

    protected $message  =   [
		'title.chsDash'						=> '广告说明只能是汉字、字母、数字、_、-'
		,'title.length'						=> '广告说明长度需在2～30之间'

		,'position.require'					=> '广告位数据异常'
		,'position.num'						=> '广告位数据异常'
		,'position.gt'						=> '广告位数据异常'

		,'data.require'						=> '广告内容不能为空'
		,'data.checkData'					=> '广告内容异常'
    ];
	/**
	 * 检查广告数据
	 * @author Jason <1878566968@qq.com>
	 */
	protected function checkData($value,$rule,$data){
		$map = [];
		$map[]	= ['id','=',$data['position']];

		$AdPosition = model('AdPosition');
		
		$position = $AdPosition->where($map)->find();
		if(empty($position)){
			return '广告位不存';
		}

		switch($position['type']){
			case 0:
			case 1:
				if(!is_numeric($value)) return false;
				break;
			case 2:
				if(!is_string($value)) return false;
				break;
			case 3:
				if(!is_string($value)) return false;
				break;
		}
		return true;
	}	
}
