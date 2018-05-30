<?php
/**
 * ComponentAction 
 * 微信开放平台
 * @uses Action
 * @author Jason<1878566968@qq.com> 
 */
namespace app\api\controller;
use app\api\controller\Base;
use EasyWeChat\Foundation\Application;

class Component extends Base{
    //公众号ID
    protected $weid = 0;
    //公众号
    protected $weixin = null;
    //开放平台配置信息
    protected $componentConfig = null;
    /**
     * _initialize
     * 前置函数 在其他函数执行之前执行
     */
    public function initialize(){
		parent::initialize();
        //获取微信开放平台配置信息
        $componentConfigKey = 'WECHAT_OPEN_COMPONENT_CONFIG';
        //$this->componentConfig = json_decode(base64_decode(getConfig($componentConfigKey)),true);
    }

    /**
     * _empty 
     * 空方法 没有传入方法名或传入的方法不存在的时候执行 
     * @access public
     * @return void
     */
    public function _empty(){
		$this->index();
	}
	public function index(){
		$options = [
			// ...
			'open_platform' => [
				'app_id'   => 'wx48254a0207b9e357',
				'secret'   => 'be0bdb08e2fad6ccc21d32e661f07c72',
				'token'    => 'heilphp',
				'aes_key'  => '4e84db09c229a435159ed4a9d58a80c4435159ed4a9'
			],
			// ...
		];

		$app = new Application($options);
		$openPlatform = $app->open_platform;
		$openPlatform->server->serve();
		$openPlatform->server->listen(function ($event) {
			cache('test',$event);
		});
	}
	public function test(){
		dump(cache('test'));
	}
}
