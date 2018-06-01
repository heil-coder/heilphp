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
				break;
			}
		});
		$openPlatform->server->serve();
	}
	/**
	 * getTokenFromServer
	 * 获取平台token
	 * @author jason <1878566968@qq.com>
	 */
	public function getTokenFromServer(){
		$openPlatformVerifyTicket = config('WECHAT_OPEN_PLATFORM_VERIFY_TICKET');		
		if(empty($openPlatformVerifyTicket)){
			echo '请在component_verify_ticket更新后重试';
			exit();	
		}
		$openPlatformAccessToken = config('WECHAT_OPEN_PLATFORM_ACCESS_TOKEN');		
		//已存在token && 当前时间 - token更新时间 小于 (有效时长-20分钟)
		if(!empty($openPlatformAccessToken) && (app()->getBeginTime() - $openPlatformAccessToken['update_time']) <= ($openPlatformAccessToken['expires_in'] - 60*20)){
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
		echo '更新成功';
	}
	/**
	 * authorize
	 * 账号授权
	 * @author Jason <1878566968@qq.com>
	 */
	public function authorize(){
		if(!request()->has('auth_code','get')){
			$this->authorize_step1();
		}
		else{
			$this->authorize_step2();	
		}
	}
	/**
	 * authorize_step1
	 * 授权第一步 获取授权码
	 * @author Jason <1878566968@qq.com>
	 */
	protected function authorize_step1(){
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
		$url = $openPlatform->pre_auth
			->redirect('http://heilphp.web.easychn.com'.url('api/component/authorize'))
			->getTargetUrl();
		header('Location:'.$url);
	}
	/**
	 * authorize_step2
	 * 授权第二步 获取授权账号信息
	 * @author Jason <1878566968@qq.com>
	 */
	protected function authorize_step2(){
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
		$info = $openPlatform->getAuthorizationInfo($authorizationCode = null)->toArray();
		$data = [
			'is_connect'	=>	1
			,'is_bind'		=>1
			,'appid'		=>	$info['authorization_info']['authorizer_appid']
			,'access_token'	=>	$info['authorization_info']['authorizer_access_token']	
			,'authorizer_refresh_token'	=>	$info['authorization_info']['authorizer_refresh_token']	
			,'update_time'	=>	app()->getBeginTime()
		];
		$Wechat = db('ApiWechat');
		$map = [];
		$map[] =['appid','=',$info['authorization_info']['authorizer_appid']];
		$detail = $Wechat->where($map)->find();
		if(empty($detail)){
			$res = $Wechat->insert($data);	
		}
		else{
			$res = $Wechat->where($map)->update($data);	
		}
		$this->success('授权成功',url('api/component/test'));
	}
	/**
	 * refreshToken
	 * 刷新access_token
	 * @author Jason <1878566968@qq.com>
	 */
	public function refreshToken(){
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

		$Wechat = Db('ApiWechat');		
		$map = [];
		$map[] = ['update_time','<',app()->getBeginTime() - 60*30];
		$map[] = ['is_bind','=',1];
		$list = $Wechat->where($map)->limit(30)->select();
		foreach($list as $val){
			$accessToken = $openPlatform->getAuthorizerToken($val['appid'],$val['authorizer_refresh_token'])->toArray();
			$app->access_token = $openPlatform->authorizer_access_token;
			$openPlatform->createAuthorizerApplication($val['appid'],$accessToken['authorizer_refresh_token']);
			$js = $app->js;
			$jsapi_ticket= $js->ticket();
			$data = [
				'access_token'	=> $accessToken['authorizer_access_token']
				,'jsapi_ticket'	=> $jsapi_ticket 
				,'authorizer_refresh_token'	=> $accessToken['authorizer_refresh_token']
				,'update_time'	=> app()->getBeginTime()
			];
			$Wechat->where('id',$val['id'])->update($data);
		}
		echo '更新成功';
	}
	public function test(){
		echo '<a href="'.url('api/component/authorize').'">授权</a>';	
	}
}
