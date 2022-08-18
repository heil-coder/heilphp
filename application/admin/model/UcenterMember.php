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
use think\model\concern\SoftDelete;
use think\Db;
use App;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 * @modify Jason <1878566968@qq.com>
 */

class UcenterMember extends Model {
	protected $pk = 'id';


    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($id){
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->where('id',$id)->find()->toArray();
        if(!$user || 1 != $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }

        //记录行为
        action_log('user_login', '用户', $id, $id);

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
            'id'             => $user['id'],
            'last_login_time' => app()->getBeginTime(),
            'last_login_ip'   => get_client_ip(1),
        );
        $this->where('id',$user['id'])->data($data)->update();

        $userDetail = $this->getUserDetail($user["id"]);

        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['id'],
            'username'        => $userDetail['nickname'] ?: $user["username"],
            'last_login_time' => $user['last_login_time'],
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));
    }
    /**
     * @param int $uid 用户uid
     * @return array 用户信息
     * @author Jason <1878566968@qq.com>
     */
    private function getUserDetail($uid){
        $map = [
            ["uid", "=", $uid]
        ];

        $detail = Db::name("member")->where($map)->find();
        if ($detail){
            return $detail;
        }

        $detail = Db::name("SubMember")->where($map)->find();
        if ($detail){
            return $detail;
        }

        $detail = Db::name("WechatMember")->where($map)->find();

        return $detail;
    }
}
