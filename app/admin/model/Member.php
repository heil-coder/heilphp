<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\admin\Model;
use think\Model;
use App;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class Member extends Model {

    //protected $_validate = array(
    //    array('nickname', '1,16', '昵称长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),
    //    array('nickname', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用
    //);
	protected $auto = ['password'];
	
	protected function setPasswordAttr($value){
		if($value === $this->password){
			return $value;
		}
		else{
			return encrypt_password($value,session('user_auth.salt'));
		}
	}
	
    public function lists($status = 1, $order = 'uid DESC', $field = true){
        $map = [['status','=',$status]];
        return $this->field($field)->where($map)->order($order)->select();
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
		switch ($type) {
			case 1:
				$map[] = ['username','=',$username];
				break;
			case 2:
				$map[] = ['email','=',$username];
				break;
			case 3:
				$map[] = ['mobile','=',$username];
				break;
			case 4:
				$map[] = ['id','=',$username];
				break;
			default:
				return 0; //参数错误
		}

		/* 获取用户数据 */
		$user = $this->where($map)->find()->toArray();
		if(is_array($user) && $user['status']){
			/* 验证用户密码 */
			if(encrypt_password($password, $user['salt']) === $user['password']){
				//记录行为
        		//action_log('user_login', 'member', $uid, $uid);

        		/* 登录用户 */
        		$this->autoLogin($user);
        		return true;
			} else {
				return -2; //密码错误
			}
		} else {
			return -1; //用户不存在或被禁用
		}
	}
    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'id'             => $user['id'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => App::getBeginTime(),
            'last_login_ip'   => get_client_ip(1),
        );
        $this->get($user['id'])->save($data);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['id']
            ,'username'        => $user['nickname']
			,'salt'			  => $user['salt']
            ,'last_login_time' => $user['last_login_time']
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }

    public function getNickName($id){
        return $this->where('id',(int)$id)->value('nickname');
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
		//$data = $this->create($data);
		if($data){
			return $this->get($uid)->save($data);
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
		$password = $this->getFieldById($uid, 'password');
		if(encrypt_password($password_in, session('user_auth.salt')) === $password){
			return true;
		}
		return false;
	}
}
