<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\user\model;
use think\Model;
/**
 * 会员模型
 */
class UcenterMember extends Model{
	protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'reg_time';
	/**
	 * 数据表前缀
	 * @var string
	 */
	//protected $tablePrefix = UC_TABLE_PREFIX;

	/**
	 * 数据库连接
	 * @var string
	 */
	//protected $connection = UC_DB_DSN;

	/* 用户模型自动验证 */
	//protected $_validate = array(
	//	/* 验证用户名 */
	//	array('username', '1,30', -1, self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
	//	array('username', 'checkDenyMember', -2, self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
	//	array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用

	//	/* 验证密码 */
	//	array('password', '6,30', -4, self::EXISTS_VALIDATE, 'length'), //密码长度不合法

	//	/* 验证邮箱 */
	//	array('email', 'email', -5, self::EXISTS_VALIDATE), //邮箱格式不正确
	//	array('email', '1,32', -6, self::EXISTS_VALIDATE, 'length'), //邮箱长度不合法
	//	array('email', 'checkDenyEmail', -7, self::EXISTS_VALIDATE, 'callback'), //邮箱禁止注册
	//	array('email', '', -8, self::EXISTS_VALIDATE, 'unique'), //邮箱被占用

	//	/* 验证手机号码 */
	//	array('mobile', '//', -9, self::EXISTS_VALIDATE), //手机格式不正确 TODO:
	//	array('mobile', 'checkDenyMobile', -10, self::EXISTS_VALIDATE, 'callback'), //手机禁止注册
	//	array('mobile', '', -11, self::EXISTS_VALIDATE, 'unique'), //手机号被占用
	//);

	/* 用户模型自动完成 */
	//protected $_auto = array(
	//	array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
	//	array('reg_time', NOW_TIME, self::MODEL_INSERT),
	//	array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
	//	array('update_time', NOW_TIME),
	//	array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
	//);


	protected function setPasswordAttr($value,$data){
		if(!empty($this->password) && $value === $this->password){
			return $value;
		}
		elseif(!empty($this->salt)){
			return encrypt_password($value,$this->salt);
		}
		elseif(!empty($data['salt'])){
			return encrypt_password($value,$data['salt']);
		}
		else{
			return encrypt_password($value);
		}
	}
	

	/**
	 * 检测用户名是不是被禁止注册
	 * @param  string $username 用户名
	 * @return boolean          ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMember($username){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 检测邮箱是不是被禁止注册
	 * @param  string $email 邮箱
	 * @return boolean       ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyEmail($email){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 检测手机是不是被禁止注册
	 * @param  string $mobile 手机
	 * @return boolean        ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMobile($mobile){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 根据配置指定用户状态
	 * @return integer 用户状态
	 */
	protected function getStatus(){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 注册一个新用户
	 * @param  string $username 用户名
	 * @param  string $password 用户密码
	 * @param  string $repassword 确认用户密码
	 * @param  string $email    用户邮箱
	 * @param  string $mobile   用户手机号码
	 * @return integer          注册成功-用户信息，注册失败-错误编号
	 */
	public function register($username, $password,$repassword, $email, $mobile){
		$data = array(
			'username' => $username
			,'password' => $password
			,'repassword' => $repassword
			,'email'    => $email
			,'mobile'   => $mobile
			,'nickname'	=> $username
			,'status'	=>1
			,'salt'		=> build_salt()
			,'reg_ip'	=> get_client_ip(1)
		);

		//验证手机
		if(empty($data['mobile'])) unset($data['mobile']);
		if(empty($data['email'])) unset($data['email']);

		$validate = new \app\admin\validate\UcenterMember;
		
		if($validate->check($data)){
			/* 添加用户 */
			$res = $this->save($data);
			return $res ? $this->id : false; //0-未知错误，大于0-注册成功
		}
		else{
			return $validate->getError();
		}
	}

	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function login($username, $password, $type = 1){
		$map = [];
		$data = [];
		$data['password'] = $password;
		$scene = '';
		$validate = new \app\admin\validate\UcenterMember;
		switch ($type) {
			case 1:
				$map[] = ['username','=',$username];
				$data['username'] = $username;
				$scene = 'loginName';
				break;
			case 2:
				$map[] = ['email','=',$username];
				$data['email'] = $username;
				$scene = 'loginEmail';
				break;
			case 3:
				$map[] = ['mobile','=',$username];
				$data['mobile'] = $username;
				$scene = 'loginMobile';
				break;
			case 4:
				$map[] = ['id','=',$username];
				break;
			default:
				return 0; //参数错误
		}
		if(true !== $validate->scene($scene)->check($data)){
			return $validate->getError();
		}

		/* 获取用户数据 */
		$user = $this->where($map)->find();
		if(!empty($user) && $user['status']){
			/* 验证用户密码 */
			if(encrypt_password($password, $user['salt']) === $user['password']){
				$this->updateLogin($user['id']); //更新用户登录信息
				return $user['id']; //登录成功，返回用户ID
			} else {
				return -2; //密码错误
			}
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	/**
	 * 获取用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function info($uid, $is_username = false){
		$map = array();
		if($is_username){ //通过用户名获取
			$map[] = ['username','=',$uid];
		} else {
			$map[] = ['id','=',$uid];
		}

		$user = $this->where($map)->field('id,username,email,mobile,status')->find()->toArray();
		if(is_array($user) && $user['status'] = 1){
			return array($user['id'], $user['username'], $user['email'], $user['mobile']);
		} else {
			return -1; //用户不存在或被禁用
		}
	}

	/**
	 * 检测用户信息
	 * @param  string  $field  用户名
	 * @param  integer $type   用户名类型 1-用户名，2-用户邮箱，3-用户电话
	 * @return integer         错误编号
	 */
	public function checkField($field, $type = 1){
		$data = array();
		switch ($type) {
			case 1:
				$data['username'] = $field;
				break;
			case 2:
				$data['email'] = $field;
				break;
			case 3:
				$data['mobile'] = $field;
				break;
			default:
				return 0; //参数错误
		}

		return $this->create($data) ? 1 : $this->getError();
	}

	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	protected function updateLogin($uid){
		$data = array(
			'id'              => $uid,
			'last_login_time' => app()->getBeginTime(),
			'last_login_ip'   => get_client_ip(1),
		);
		$this->isUpdate(true)->save($data);
	}

	/**
	 * 更新用户信息
	 * @param int $uid 用户id
	 * @param string $password 密码，用来验证
	 * @param array $data 修改的字段数组
	 * @return true 修改成功，false 修改失败
	 * @author huajie <banhuajie@163.com>
	 */
	public function updateUserFields($uid, $password, $data){
		if(empty($uid) || empty($password) || empty($data)){
			$this->error = '参数错误！';
			return false;
		}

		//更新前检查用户密码
		if(!$this->verifyUser($uid, $password)){
			$this->error = '验证出错：密码不正确！';
			return false;
		}

		//更新用户信息
		$validate = new \app\admin\validate\UcenterMember;
		
		if($validate->scene('updateUserFields')->check($data)){
			return $this->get($uid)->save($data);
		}
		else{
			$this->error = $validate->getError();
		}
		return false;
	}

	/**
	 * 验证用户密码
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author huajie <banhuajie@163.com>
	 */
	protected function verifyUser($uid, $password_in){
		$user = $this->getById($uid);
		if(encrypt_password($password_in, $user['salt']) === $user['password']){
			return true;
		}
		return false;
	}

}
