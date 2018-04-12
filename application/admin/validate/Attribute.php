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

class Attribute extends Validate
{
    protected $rule = [
		'name'			=>	[
			'require'														//不能为空
			//,'regex'					=> '/^[a-zA-Z][\w_]{1,29}$/'		//字段名格式
			,'checkName'													//检查字段是否已存在
		]
		,'title'		=>	[
			'require'													//不能为空
			,'length'					=> '1,100'							//标题长度不能超过100个字符
		]
		,'field'		=>	[
			'require'
			,'length'					=> '1,100'							//字段长度不能超过100个字符
		]


		,'remark'	=>	[
			'length'					=> '1,100'							//备注不能超过100个字符
		]

		,'model_id'	=>	[
			'require'														//不能为空
		]
    ];

    protected $message  =   [
        'name.require'						=> '字段名不能为空'
        ,'name.regex'						=> '字段名不合法'
        ,'name.checkName'					=> '字段名已存在'

        ,'title.require'					=> '字段标题不能为空'

        ,'field.require'					=> '字段定义必填'
        ,'field.length'						=> '字段定义不能超过100个字符'

        ,'title.length'						=> '字段标题不能超过100个字符'
        ,'remark.length'					=> '备注不能超过100个字符'
        ,'model_id.require'					=> '未选择操作的模型'
    ];
    /**
     * 检查同一张表是否有相同的字段
     * @author huajie <banhuajie@163.com>
     */
    protected function checkName($name,$rule,$data){
        $model_id = $data['model_id'];
        $id = $data['id'];
		$map = [
			['name','=',$name]
			,['model_id','=',$model_id]
		];
        if(!empty($id)){
            $map[] = ['id','<>',$id];
        }
        $res = db('Attribute')->where($map)->find();
        return empty($res);
    }
}
