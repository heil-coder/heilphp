<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;
use think\Model;
use think\model\concern\SoftDelete;
/**
 * 配置模型
 * @author Jason	<1878566968@qq.com>
 */

class AuthGroup extends Model {
	const TYPE_ADMIN                = 1;                   // 管理员用户组类型标识
	const MEMBER                    = 'member';
	const UCENTER_MEMBER            = 'ucenter_member';
	const AUTH_GROUP_ACCESS         = 'auth_group_access'; // 关系表表名
	const AUTH_EXTEND               = 'auth_extend';       // 动态权限扩展信息表
	const AUTH_GROUP                = 'auth_group';        // 用户组表名
	const AUTH_EXTEND_CATEGORY_TYPE = 1;              // 分类权限标识
	const AUTH_EXTEND_MODEL_TYPE    = 2; //分类权限标识

	use SoftDelete;
	protected $deleteTime = 'delete_time';

    /**
     * 返回用户拥有管理权限的扩展数据id列表
     *
     * @param int     $uid  用户id
     * @param int     $type 扩展数据标识
     * @param int     $session  结果缓存标识
     * @return array
     *
     *  array(2,4,8,13)
     *
     */
    static public function getAuthExtend($uid,$type,$session){
        if ( !$type ) {
            return false;
        }
        if ( $session ) {
            $result = session($session);
        }
        if ( $uid == UID && !empty($result) ) {
            return $result;
        }
        $prefix = config('database.prefix');
        $result = db()
            ->table($prefix.self::AUTH_GROUP_ACCESS.' g')
            ->join($prefix.self::AUTH_EXTEND.' c ','g.group_id=c.group_id')
            ->where("g.uid='$uid' and c.type='$type' and !isnull(extend_id)")
            ->column('extend_id');
        if ( $uid == UID && $session ) {
            session($session,$result);
        }
        return $result;
    }
    /**
     * 返回用户拥有管理权限的分类id列表
     *
     * @param int     $uid  用户id
     * @return array
     *
     *  array(2,4,8,13)
     *
     */
    static public function getAuthCategories($uid){
        return self::getAuthExtend($uid,self::AUTH_EXTEND_CATEGORY_TYPE,'AUTH_CATEGORY');
    }
}
