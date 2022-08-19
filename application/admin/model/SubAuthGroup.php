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
use think\Db;
/**
 * 配置模型
 * @author Jason	<1878566968@qq.com>
 */

class SubAuthGroup extends Model {
	const TYPE_ADMIN                = 1;                   // 管理员用户组类型标识
	const AUTH_GROUP_ACCESS         = 'sub_auth_group_access'; // 关系表表名
	const AUTH_EXTEND_CATEGORY_TYPE = 1;              // 分类权限标识
	const AUTH_EXTEND_MODEL_TYPE    = 2; //分类权限标识

	use SoftDelete;
	protected $deleteTime = 'delete_time';

	protected function setRulesAttr($value){
		if (is_null($value)) return null;
		return is_array($value) ? arr2str($value) : $value;
	}
    /**
     * 返回用户组列表
     * 默认返回正常状态的管理员用户组列表
     * @param array $where   查询条件,供where()方法使用
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function getGroups($where=array()){
		$map = [
			['status','=',1]
			,['type','=',self::TYPE_ADMIN]
			,['module','=','admin']
		];
        $map = array_merge($map,$where);
        return $this->where($map)->select();
    }
    /**
     * 把用户添加到用户组,支持批量添加用户到用户组
     * @author 朱亚杰 <zhuyajie@topthink.net>
     *
     * 示例: 把uid=1的用户添加到group_id为1,2的组 `AuthGroupModel->addToGroup(1,'1,2');`
     */
    public function addToGroup($uid,$gid){
        $uid = is_array($uid)?implode(',',$uid):trim($uid,',');
        $gid = is_array($gid)?$gid:explode( ',',trim($gid,',') );

        $Access = Db::name(self::AUTH_GROUP_ACCESS);
		$del = null;
        if( isset($_REQUEST['batch']) ){
            //为单个用户批量添加用户组时,先删除旧数据
            $del = $Access->where('uid','in',$uid)->delete();
        }

        $uid_arr = explode(',',$uid);
		$uid_arr = array_diff($uid_arr,array(config('USER_ADMINISTRATOR')));
		$add = [];
		$repeat = [];
        if( $del!==false ){
            foreach ($uid_arr as $u){
            	//判断用户id是否合法
            	if(Db::name('SubMember')->getFieldByUid($u,'uid') == false){
            		$this->error = "编号为{$u}的用户不存在！";
            		return false;
            	}
                foreach ($gid as $g){
                    if( is_numeric($u) && is_numeric($g) ){
						$Access->setOption('where',[]);
						if(!$Access->where([
							['uid','=',$u]
							,['group_id','=',$g]
						])->find()
						){
							$add[] = array('group_id'=>$g,'uid'=>$u);
						}
						else{
							$repeat[] = $u;
						}
                    }
                }
            }
			if(!empty($repeat)){
				//id重复添加时错误提示
				$this->error = "UID:".arr2str($repeat)."不能重复添加";
				return false;
			}
            $res = empty($add) ? 0 : $Access->insertAll($add);
        }
        if ($res === false) {
            return false;
        }else{
            return true;
        }
    }
    /**
     * 将用户从用户组中移除
     * @param int|string|array $gid   用户组id
     * @param int|string|array $cid   分类id
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    public function removeFromGroup($uid,$gid){
		return Db::name(self::AUTH_GROUP_ACCESS)->where([
			['uid','=',$uid]
			,['group_id','=',$gid]
		])->delete();
    }
    /**
     * 返回用户所属用户组信息
     * @param  int    $uid 用户id
     * @return array  用户所属的用户组 array(
     *                                         array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *                                         ...)
     */
    static public function getUserGroup($uid){
        $user_groups = Db::name("SubAuthGroupAccess")
            ->alias("subAuthGroupAccess")
            ->join("SubAuthGroup subAuthGroup", "subAuthGroupAccess.group_id = subAuthGroup.id")
            ->field([
                "subAuthGroupAccess.uid" => "uid",
                "subAuthGroupAccess.group_id" => "group_id",
                "subAuthGroup.title" => "title",
                "subAuthGroup.description" => "description",
                "subAuthGroup.rules" => "rules",
            ])
            ->where([
                ["subAuthGroupAccess.uid", "=", $uid],
                ["subAuthGroup.status", "=", 1]
            ])
            ->select();
        return !empty($user_groups) ? $user_groups : [];
    }
    /**
     * 返回用户所属用户组信息
     * @param  int    $uid 用户id
     * @return array  用户所属的用户组 array(
     *                                         array('uid'=>'用户id','group_id'=>'用户组id','title'=>'用户组名称','rules'=>'用户组拥有的规则id,多个,号隔开'),
     *                                         ...)
     */
    static public function listUserGroupIds($uid){
        $groupIds = Db::name("SubAuthGroupAccess")
            ->alias("subAuthGroupAccess")
            ->join("SubAuthGroup subAuthGroup", "subAuthGroupAccess.group_id = subAuthGroup.id")
            ->where([
                ["subAuthGroupAccess.uid", "=", $uid],
                ["subAuthGroup.status", "=", 1]
            ])
            ->column("subAuthGroup.id");
        return $groupIds;
    }

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
        $result = Db::name()
            ->table($prefix.self::AUTH_GROUP_ACCESS.' g')
            ->join($prefix.self::AUTH_EXTEND.' c ','g.group_id=c.group_id')
            ->where("g.uid='$uid' and c.type='$type' and !isnull(extend_id)")
            ->column('extend_id');
        if ( $uid == UID && $session ) {
            session($session,$result);
        }
        return $result;
    }
}
