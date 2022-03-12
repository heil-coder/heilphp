<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\admin\logic;
use think\Model;

/**
 * 文档模型逻辑层公共模型
 * 所有逻辑层模型都需要继承此模型
 */
class Base extends Model {
	protected $name= '';

    /* 自动验证规则 */
    protected $validate   =   ['rule'=>[],'message'=>[]];

    /* 自动完成规则 */
    protected $auto        =   [];

    /**
     * 构造函数
     * @param string $name 模型名称
     * @param string $tablePrefix 表前缀
     * @param mixed $connection 数据库连接信息
     */
    public function __construct($name = '', $tablePrefix = '', $connection = '') {
        /* 设置默认的表前缀 */
        $this->connection = config('database.');
        $this->connection['prefix'] .= 'document_';
        /* 执行构造方法 */
        parent::__construct($name, $tablePrefix, $connection);
    }

    /**
     * 获取模型详细信息
     * @param  integer $id 文档ID
     * @return array       当前模型详细信息
     */
    public function detail($id) {
        if ($this->getTableFields($this->getTable()) == false) {
            $data = array();
        } else {
            $data = $this->field(true)->find($id);
            if (!$data) {
                $this->error = '获取详细信息出错！';
                return false;
            }
			else{
				$data = $data->toArray();
			}
        }
        return $data;
    }

    /**
     * 新增或添加模型数据
     * @param  number $id 文章ID
     * @return boolean    true-操作成功，false-操作失败
     */
    public function edit($id = 0) {
        /* 获取数据 */
        $data = input('param.'); 
        if ($data === false) {
            return false;
        }

		if(empty($data['id'])) unset($data['id']);

	   	$Validate = new \think\Validate;
		if($Validate->check($data) !== true){
			$this->error = $Validate->getError();	
		};

		$this->data($data);
        if (empty($data['id'])) {//新增数据
            $data['id'] = $id;
            $res = $this->save($data);
            if (!$res) {
                $this->error = '新增数据失败！';
                return false;
            }
        } else { //更新数据
            $status = $this->get($id)->save($data);
            if (false === $status) {
                $this->error = '更新数据失败！';
                return false;
            }
        }
        return true;
    }

    /**
     * 模型数据自动保存
     * @return boolean
     */
    public function autoSave($id = 0) {
        $this->_validate = array();
        return $this->update($id);
    }

    /**
     * 检测属性的自动验证和自动完成属性
     * @return boolean
     */
    public function checkModelAttr($model_id){
        $fields     =   get_model_attribute($model_id,false);
        $validate   = ['rule'=>[],'message'=>[]];  $auto   =   [];
        foreach($fields as $key=>$attr){
            if($attr['is_must']){// 必填字段
                //$validate[]  =  array($attr['name'],'require',$attr['title'].'必须!',self::MUST_VALIDATE , 'regex', self::MODEL_BOTH);
				$validate['rule'][$attr['name']] = 'require';
				$validate['message'][$attr['name'].'.'.'require'] = $attr['title'].'必须!';
            }
            // 自动验证规则
            if(!empty($attr['validate_rule'])) {
                //$validate[]  =  array($attr['name'],$attr['validate_rule'],$attr['error_info']?$attr['error_info']:$attr['title'].'验证错误',0,$attr['validate_type'],$attr['validate_time']);
            }
            // 自动完成规则
            if(!empty($attr['auto_rule'])) {
                //$auto[]  =  array($attr['name'],$attr['auto_rule'],$attr['auto_time'],$attr['auto_type']);
            }elseif('checkbox'==$attr['type']){ // 多选型
                //$auto[] =   array($attr['name'],'arr2str',3,'function');
            }elseif('datetime' == $attr['type'] || 'date' == $attr['type']){ // 日期型
                //$auto[] =   array($attr['name'],'strtotime',3,'function');
            }
        }
        $this->validate['rule']   =   array_merge($validate['rule'],$this->validate['rule']);
        $this->validate['message']   =   array_merge($validate['message'],$this->validate['message']);
        $auto       =   array_merge($auto,$this->auto);
		$this->auto($auto);
    }
}
