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

        $Access = db(self::AUTH_GROUP_ACCESS);
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
            	if(db('Member')->getFieldByid($u,'id') == false){
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
		return db(self::AUTH_GROUP_ACCESS)->where([
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
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        $prefix = config('database.prefix');
        $user_groups = db()
            ->field('uid,group_id,title,description,rules')
            ->table($prefix.self::AUTH_GROUP_ACCESS.' a')
            ->join ($prefix.self::AUTH_GROUP." g "," a.group_id=g.id")
            ->where("a.uid='$uid' and g.status='1'")
            ->select();
        $groups[$uid]=$user_groups?$user_groups:array();
        return $groups[$uid];
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
