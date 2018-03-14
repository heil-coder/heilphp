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

	protected $auto = ['rules'];
	protected function setRulesAttr($value){
		if (is_null($value)) return null;
		return is_array($value) ? arr2str($value) : $value;
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

    /**
     * 检查id是否全部存在
     * @param array|string $gid  用户组id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkId($modelname,$mid,$msg = '以下id不存在:'){
        if(is_array($mid)){
            $count = count($mid);
            $ids   = implode(',',$mid);
        }else{
            $mid   = explode(',',$mid);
            $count = count($mid);
            $ids   = $mid;
        }

        $s = db($modelname)->where('id','IN',$ids)->column('id');
        if(count($s)===$count){
            return true;
        }else{
            $diff = implode(',',array_diff($mid,$s));
            $this->error = $msg.$diff;
            return false;
        }
    }

    /**
     * 检查用户组是否全部存在
     * @param array|string $gid  用户组id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkGroupId($gid){
        return $this->checkId('AuthGroup',$gid, '以下用户组id不存在:');
    }

    /**
     * 检查分类是否全部存在
     * @param array|string $cid  栏目分类id列表
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function checkCategoryId($cid){
        return $this->checkId('Category',$cid, '以下分类id不存在:');
    }
    /**
     * 批量设置用户组可管理的分类
     *
     * @param int|string|array $gid   用户组id
     * @param int|string|array $cid   分类id
     *
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    static public function addToCategory($gid,$cid){
        return self::addToExtend($gid,$cid,self::AUTH_EXTEND_CATEGORY_TYPE);
    }
   /**
     * 批量设置用户组可管理的扩展权限数据
     *
     * @param int|string|array $gid   用户组id
     * @param int|string|array $cid   分类id
     *
     * @author 朱亚杰 <xcoolcc@gmail.com>
     */
    static public function addToExtend($gid,$cid,$type){
        $gid = is_array($gid)?implode(',',$gid):trim($gid,',');
        $cid = is_array($cid)?$cid:explode( ',',trim($cid,',') );

        $Access = db(self::AUTH_EXTEND);
		$del = $Access->where([
							['group_id','in',$gid]
							,['type','=',$type]
							])->delete();

        $gid = explode(',',$gid);
        $add = array();
        if( $del!==false ){
            foreach ($gid as $g){
                foreach ($cid as $c){
                    if( is_numeric($g) && is_numeric($c) ){
                        $add[] = array('group_id'=>$g,'extend_id'=>$c,'type'=>$type);
                    }
                }
            }
            $res = $Access->insertAll($add);
        }
        if ($res === false) {
            return false;
        }else{
            return true;
        }
    }
}
