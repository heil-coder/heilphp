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
        $componentConfigKey = 'WECHAT_OPEN_PLATFORM_CONFIG';
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
		cache('test2',date('Y-m-d H:i:s'));
		$openPlatformConfig = config('WECHAT_OPEN_PLATFORM_CONFIG');
		$options = [
			'open_platform' => [
				'app_id'   => $openPlatformConfig['appId'],
				'secret'   => $openPlatformConfig['appSecret'],
				'token'    => $openPlatformConfig['token'],
				'aes_key'  => $openPlatformConfig['encodingAesKey']
			]
		];

		$app = new Application($options);
		$openPlatform = $app->open_platform;
		$openPlatform->server->setMessageHandler(function ($event) {


			// 事件类型常量定义在 \EasyWeChat\OpenPlatform\Guard 类里
			switch ($event->InfoType) {
			case 'authorized':
				// ...
				break;
			case 'unauthorized':
				// ...
				break;
			case 'updateauthorized':
				// ...
				break;
				//
			case 'component_verify_ticket':
				$data = [
					'value'	=>$event['ComponentVerifyTicket']
					,'update_time'	=>$event['CreateTime']
				];
				db('Config')->where('name','=','WECHAT_OPEN_PLATFORM_VERIFY_TICKET')->update($data);
				cache('DB_CONFIG_DATA',null);
				echo 'success';
				break;
			}
		});
	}
	public function getTokenFromServer(){
		$openPlatformAccessToken = config('WECHAT_OPEN_PLATFORM_ACCESS_TOKEN');		
		//当前时间 - token更新时间 小于 (2小时 -20分钟) 终止
		if((app()->getBeginTime() - $openPlatformAccessToken['update_time']) <= (60*60*2 - 60*20)){
			echo '未到更新时间';
			exit();	
		}
		$openPlatformConfig = config('WECHAT_OPEN_PLATFORM_CONFIG');
		$options = [
			'open_platform' => [
				'app_id'   => $openPlatformConfig['appId'],
				'secret'   => $openPlatformConfig['appSecret'],
				'token'    => $openPlatformConfig['token'],
				'aes_key'  => $openPlatformConfig['encodingAesKey']
			]
		];
		$app = new Application($options);
		$openPlatform = $app->open_platform;
		$token = $openPlatform->access_token->getTokenFromServer();
		$token['update_time'] = $data['update_time'] = app()->getBeginTime();
		$string = '';
		foreach($token as $key =>$val){
			$string = $string ? ($string . "\r\n" . $key . ':' .$val) : ($key . ':' .$val);	
		}
		$data['value'] = $string;
		db('Config')->where('name','=','WECHAT_OPEN_PLATFORM_ACCESS_TOKEN')->update($data);
		cache('DB_CONFIG_DATA',null);
	}
}
