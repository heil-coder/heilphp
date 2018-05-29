<?php
/**
 * BaseAction
 * 基础
 */
namespace app\api\controller;
use think\Controller;
class Base extends Controller{
    /**
     * _initialize 
     * 初始化函数，可用于部署控制器公共前置内容
     * @access public
     * @return void
     */
    public function initialize(){
        //读取数据库中的配置信息
        $this->getDbConfig();
    }
    /**
     * _empty 
     * 空方法 没有传入方法名或传入的方法不存在的时候执行 
     * @access public
     * @return void
     */
    public function _empty(){
    }
    /**
     * getDbConfig
     * 读取数据库中的配置信息
     */
    protected function getDbConfig(){
        /* 读取数据库中的配置 */
        $config =   cache('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/getListing');
            cache('DB_CONFIG_DATA',$config);
        }
		config($config,'app'); //添加配置
    }
}
