<?php
// +----------------------------------------------------------------------
// | 三亚名城果蔬服务有限公司
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.hcnmsc.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\Model;
use think\Model;
use think\model\concern\SoftDelete;

/**
 * 子账号模型
 */

class SubMember extends Model {
	protected $pk = 'uid';

    public function lists($status = 1, $order = 'uid DESC', $field = true){
        $map = [['status','=',$status]];
        return $this->field($field)->where($map)->order($order)->select()->toArray();
    }

    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($uid){
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->where('uid',$uid)->find()->toArray();
        if(!$user || 1 != $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }

        //记录行为
        action_log('user_login', '用户', $uid, $uid);

        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session(null);
    }

    /**
     * 自动登录用户
     * @param  integer $user 用户信息数组
     */
    private function autoLogin($user){
        /* 更新登录信息 */
        $data = array(
            'uid'             => $user['uid'],
            'last_login_time' => app()->getBeginTime(),
            'last_login_ip'   => get_client_ip(1),
        );
        $this->where('uid',$user['uid'])->data($data)->inc('login')->update();

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['uid'],
            'username'        => $user['nickname'],
            'last_login_time' => $user['last_login_time'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->value('nickname');
    }

}
