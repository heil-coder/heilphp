<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
	if(function_exists("mb_substr"))
		$slice = mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		$slice = iconv_substr($str,$start,$length,$charset);
		if(false === $slice) {
			$slice = '';
		}
	}else{
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
	}
	return $suffix ? $slice.'...' : $slice;
}


/**
 * 插件显示内容里生成访问插件的url
 * @param string $url url
 * @param array $param 参数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function addons_url($url, $param = array()){
	$url        = parse_url($url);
	$case       = Config('URL_CASE_INSENSITIVE');
	$addons     = $case ? parse_name($url['scheme']) : $url['scheme'];
	$controller = $case ? parse_name($url['host']) : $url['host'];
	$action     = trim($case ? strtolower($url['path']) : $url['path'], '/');

	/* 解析URL带的参数 */
	if(isset($url['query'])){
		parse_str($url['query'], $query);
		$param = array_merge($query, $param);
	}

	/* 基础参数 */
	$params = array(
		'_addons'     => $addons,
		'_controller' => $controller,
		'_action'     => $action,
	);
	$params = array_merge($params, $param); //添加额外参数

	return Url('index/addons/execute', $params);
}
/**
 * 时间戳格式化
 * @param int $time			时间字符串或时间戳
 * @param string $format	时间格式
 * @return string 完整的时间显示
 * @author huajie <banhuajie@163.com>
 * @modify Jason <1878566968@qq.com>
 */
function time_format($time = NULL,$format='Y-m-d H:i'){
    if(is_null($time))
        return NULL;
	$time = is_numeric($time) ? $time : strtotime($time);
	return date($format, $time);
}
/**
 * 获取插件类的类名
 * @param strng $name 插件名
 */
function get_addon_class($name){
	$class = "addons\\".lcfirst($name)."\\".ucfirst($name);
	return $class;
}

/**
 * 获取插件类的配置文件数组
 * @param string $name 插件名
 */
function get_addon_config($name){
	$class = get_addon_class($name);
	if(class_exists($class)) {
		$addon = new $class();
		return $addon->getConfig();
	}else {
		return array();
	}
}
/**
 * 调用系统的API接口方法（静态方法）
 * api('User/getName','id=5'); 调用公共模块的User接口的getName方法
 * api('Admin/User/getName','id=5');  调用Admin模块的User接口
 * @param  string  $name 格式 [模块名]/接口名/方法名
 * @param  array|string  $vars 参数
 */
function api($name,$vars=array()){
	$array     = explode('/',$name);
	$method    = array_pop($array);
	$classname = array_pop($array);
	$module    = $array? array_pop($array) : 'common';
	$callback  = 'app\\'.$module.'\\api\\'.$classname.'::'.$method;
	if(is_string($vars)) {
		parse_str($vars,$vars);
	}
	return call_user_func_array($callback,$vars);
}
/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
	// 创建Tree
	$tree = array();
	if(is_array($list)) {
		// 创建基于主键的数组引用
		$refer = array();
		foreach ($list as $key => $data) {
			$refer[$data[$pk]] =& $list[$key];
		}
		foreach ($list as $key => $data) {
			// 判断是否存在parent
			$parentId =  $data[$pid];
			if ($root == $parentId) {
				$tree[] =& $list[$key];
			}else{
				if (isset($refer[$parentId])) {
					$parent =& $refer[$parentId];
					$parent[$child][] =& $list[$key];
				}
			}
		}
	}
	return $tree;
}
/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array        返回排过序的列表数组
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
	if(is_array($tree)) {
		foreach ($tree as $key => $value) {
			$reffer = $value;
			if(isset($reffer[$child])){
				unset($reffer[$child]);
				tree_to_list($value[$child], $child, $order, $list);
			}
			$list[] = $reffer;
		}
		$list = list_sort_by($list, $order, $sortby='asc');
	}
	return $list;
}

/**
 * 根据用户ID获取用户昵称
 * @param  integer $uid 用户ID
 * @return string       用户昵称
 */
function get_nickname($uid = 0){
	static $list;
	if(!($uid && is_numeric($uid))){ //获取当前登录用户名
		return session('user_auth.username');
	}

	/* 获取缓存数据 */
	if(empty($list)){
		$list = cache('sys_user_nickname_list');
	}

	/* 查找用户信息 */
	$key = "u{$uid}";
	if(isset($list[$key])){ //已缓存，直接使用
		$name = $list[$key];
	} else { //调用接口获取用户信息
		$info = db('Member')->field('nickname')->find($uid);
		if($info !== false && $info['nickname'] ){
			$nickname = $info['nickname'];
			$name = $list[$key] = $nickname;
			/* 缓存用户 */
			$count = count($list);
			$max   = config('USER_MAX_CACHE');
			while ($count-- > $max) {
				array_shift($list);
			}
			cache('sys_user_nickname_list', $list);
		} else {
			$name = '';
		}
	}
	return $name;
}
/**
 * 数据签名认证
 * @param  array  $data 被认证的数据
 * @return string       签名
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function data_auth_sign($data) {
	//数据类型检测
	if(!is_array($data)){
		$data = (array)$data;
	}
	ksort($data); //排序
	$code = http_build_query($data); //url编码并生成query字符串
	$sign = sha1($code); //生成签名
	return $sign;
}
/**
 * 检测用户是否登录
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login(){
	$user = session('user_auth');
	if (empty($user)) {
		return 0;
	} else {
		return session('user_auth_sign') == data_auth_sign($user) ? $user['uid'] : 0;
	}
}
/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 */
function is_administrator($uid = null){
	$uid = is_null($uid) ? is_login() : $uid;
	return $uid && (intval($uid) === config('heilphp.USER_ADMINISTRATOR'));
}


/**
 * 对查询结果集进行排序
 * @access public
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list,$field, $sortby='asc') {
	if(is_array($list)){
		$refer = $resultSet = array();
		foreach ($list as $i => $data)
			$refer[$i] = &$data[$field];
		switch ($sortby) {
		case 'asc': // 正向排序
			asort($refer);
			break;
		case 'desc':// 逆向排序
			arsort($refer);
			break;
		case 'nat': // 自然排序
			natcasesort($refer);
			break;
		}
		foreach ( $refer as $key=> $val)
			$resultSet[] = &$list[$key];
		return $resultSet;
	}
	return false;
}
/**
 * 字符串转换为数组，主要用于把分隔符调整到第二个参数
 * @param  string $str  要分割的字符串
 * @param  string $glue 分割符
 * @return array
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function str2arr($str, $glue = ','){
	return explode($glue, $str);
}

/**
 * 数组转换为字符串，主要用于把分隔符调整到第二个参数
 * @param  array  $arr  要连接的数组
 * @param  string $glue 分割符
 * @return string
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function arr2str($arr, $glue = ','){
	return implode($glue, $arr);
}
/**
 * 获取文档模型信息
 * @param  integer $id    模型ID
 * @param  string  $field 模型字段
 * @return array
 */
function get_document_model($id = null, $field = null){
	static $list;

	/* 非法分类ID */
	if(!(is_numeric($id) || is_null($id))){
		return '';
	}

	/* 读取缓存数据 */
	if(empty($list)){
		$list = cache('DOCUMENT_MODEL_LIST');
	}

	/* 获取模型名称 */
	if(empty($list)){
		$map   = [['status','=',1],['extend','=',1]];
		$model = db('Model')->where($map)->field(true)->select();
		foreach ($model as $value) {
			$list[$value['id']] = $value;
		}
		cache('DOCUMENT_MODEL_LIST', $list); //更新缓存
	}

	/* 根据条件返回数据 */
	if(is_null($id)){
		return $list;
	} elseif(is_null($field)){
		return $list[$id];
	} else {
		return $list[$id][$field];
	}
}

/**
 * 检查$pos(推荐位的值)是否包含指定推荐位$contain
 * @param number $pos 推荐位的值
 * @param number $contain 指定推荐位
 * @return boolean true 包含 ， false 不包含
 * @author huajie <banhuajie@163.com>
 */
function check_document_position($pos = 0, $contain = 0){
	if(empty($pos) || empty($contain)){
		return false;
	}

	//将两个参数进行按位与运算，不为0则表示$contain属于$pos
	$res = $pos & $contain;
	if($res !== 0){
		return true;
	}else{
		return false;
	}
}
/**
 * 验证分类是否允许发布内容
 * @param  integer $id 分类ID
 * @return boolean     true-允许发布内容，false-不允许发布内容
 */
function check_category($id){
	if (is_array($id)) {
		$id['type']	=	!empty($id['type'])?$id['type']:2;
		$type = get_category($id['category_id'], 'type');
		$type = explode(",", $type);
		return in_array($id['type'], $type);
	} else {
		$publish = get_category($id, 'allow_publish');
		return $publish ? true : false;
	}
}
/**
 * 检测分类是否绑定了指定模型
 * @param  array $info 模型ID和分类ID数组
 * @return boolean     true-绑定了模型，false-未绑定模型
 */
function check_category_model($info){
	$cate   =   get_category($info['category_id']);
	$array  =   explode(',', $info['pid'] ? $cate['model_sub'] : $cate['model']);
	return in_array($info['model_id'], $array);
}
/* 根据ID获取分类标识 */
function get_category_name($id){
	return get_category($id, 'name');
}
/* 根据ID获取分类名称 */
function get_category_title($id){
	return get_category($id, 'title');
}
/**
 * 获取分类信息并缓存分类
 * @param  integer $id    分类ID
 * @param  string  $field 要获取的字段名
 * @return string         分类信息
 */
function get_category($id, $field = null){
	static $list;

	/* 非法分类ID */
	if(empty($id) || !is_numeric($id)){
		return '';
	}

	/* 读取缓存数据 */
	if(empty($list)){
		$list = cache('sys_category_list');
	}

	/* 获取分类名称 */
	if(!isset($list[$id])){
		$cate = db('Category')->find($id);
		if(!$cate || 1 != $cate['status']){ //不存在分类，或分类被禁用
			return '';
		}
		$list[$id] = $cate;
		cache('sys_category_list', $list); //更新缓存
	}
	return is_null($field) ? $list[$id] : $list[$id][$field];
}

/**
 * 获取表名（不含表前缀）
 * @param string $model_id
 * @return string 表名
 * @author huajie <banhuajie@163.com>
 * @modify Jason <1878566968@qq.com>
 */
function get_table_name($model_id = null){
	if(empty($model_id)){
		return false;
	}
	$Model = db('Model');
	$name = '';
	$info = $Model->getById($model_id);
	if($info['extend'] != 0){
		$name = $Model->getFieldById($info['extend'], 'name').'_';
	}
	$name .= $info['name'];
	return $name;
}
/**
 * 根据条件字段获取指定表的数据
 * @param mixed $value 条件，可用常量或者数组
 * @param string $condition 条件字段
 * @param string $field 需要返回的字段，不传则返回整个数据
 * @param string $table 需要查询的表
 * @author huajie <banhuajie@163.com>
 */
function get_table_field($value = null, $condition = 'id', $field = null, $table = null){
	if(empty($value) || empty($table)){
		return false;
	}

	//拼接参数
	$map[$condition] = $value;
	$info = db(ucfirst($table))->where($map);
	if(empty($field)){
		$info = $info->field(true)->find();
	}else{
		$info = $info->value($field);
	}
	return $info;
}
/**
 * 获取属性信息并缓存
 * @param  integer $id    属性ID
 * @param  string  $field 要获取的字段名
 * @return string         属性信息
 */
function get_model_attribute($model_id, $group = true,$fields=true){
	static $list;

	/* 非法ID */
	if(empty($model_id) || !is_numeric($model_id)){
		return '';
	}

	/* 获取属性 */
	if(!isset($list[$model_id])){
		$extend = db('Model')->getFieldById($model_id,'extend');
		if($extend){
			$map[] = ['model_id','in', [$model_id,$extend]];
		}
		else{
			$map[] = ['model_id','=',$model_id];
		}
		$info = db('Attribute')->where($map)->field($fields)->select();
		$list[$model_id] = $info;
	}

	$attr = array();
	if($group){
		foreach ($list[$model_id] as $value) {
			$attr[$value['id']] = $value;
		}
		$model     = db("Model")->field("field_sort,attribute_list,attribute_alias")->find($model_id);
		$attribute = explode(",", $model['attribute_list']);
		if (empty($model['field_sort'])) { //未排序
			$group = array(1 => array_merge($attr));
		} else {
			$group = json_decode($model['field_sort'], true);

			$keys = array_keys($group);
			foreach ($group as &$value) {
				foreach ($value as $key => $val) {
					$value[$key] = $attr[$val];
					unset($attr[$val]);
				}
			}

			if (!empty($attr)) {
				foreach ($attr as $key => $val) {
					if (!in_array($val['id'], $attribute)) {
						unset($attr[$key]);
					}
				}
				$group[$keys[0]] = array_merge($group[$keys[0]], $attr);
			}
		}
		if (!empty($model['attribute_alias'])) {
			$alias  = preg_split('/[;\r\n]+/s', $model['attribute_alias']);
			$fields = array();
			foreach ($alias as &$value) {
				$val             = explode(':', $value);
				$fields[$val[0]] = $val[1];
			}
			foreach ($group as &$value) {
				foreach ($value as $key => $val) {
					if (!empty($fields[$val['name']])) {
						$value[$key]['title'] = $fields[$val['name']];
					}
				}
			}
		}
		$attr = $group;
	}else{
		foreach ($list[$model_id] as $value) {
			$attr[$value['name']] = $value;
		}
	}
	return $attr;
}
/**
 * 获取数据的所有子孙数据的id值
 * @author 朱亚杰 <xcoolcc@gmail.com>
 * @modify Jason <1878566968@qq.com>
 */

function get_stemma($pids, &$model, $field='id'){
	$collection = array();

	//非空判断
	if(empty($pids)){
		return $collection;
	}

	if( is_array($pids) ){
		$pids = trim(implode(',',$pids),',');
	}
	$result     = $model->field($field)->where('pid','IN',(string)$pids)->select()->toArray();
	$child_ids  = array_column ((array)$result,'id');

	while( !empty($child_ids) ){
		$collection = array_merge($collection,$result);
		$result     = $model->field($field)->where('pid','IN',$child_ids)->select()->toArray();
		$child_ids  = array_column((array)$result,'id');
	}
	return $collection;
}

/**
 * 系统非常规MD5加密方法
 * @param  string $str 要加密的字符串
 * @return string
 */
function encrypt_password($str, $salt = ''){
	return '' === $str ? '' : md5(sha1($str) . $salt);
}
/**
 * 生成用户salt
 * @author Jason	<1878566968@qq.com>
 */
function build_salt(){
	$chars  = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$chars .= '`~!@#$%^&*()_+-=[]{};:"|,.<>/?';
	$chars  = str_shuffle($chars);
	return substr($chars, 0, 10);
}
/*
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
	$type       =  $type ? 1 : 0;
	static $ip  =   NULL;
	if ($ip !== NULL) return $ip[$type];
	if($adv){
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos    =   array_search('unknown',$arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip     =   trim($arr[0]);
		}elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip     =   $_SERVER['HTTP_CLIENT_IP'];
		}elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip     =   $_SERVER['REMOTE_ADDR'];
		}
	}elseif (isset($_SERVER['REMOTE_ADDR'])) {
		$ip     =   $_SERVER['REMOTE_ADDR'];
	}
	// IP地址合法验证
	$long = sprintf("%u",ip2long($ip));
	$ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	return $ip[$type];
}

/**
 * 根据用户ID获取用户名
 * @param  integer $uid 用户ID
 * @return string       用户名
 */
function get_username($uid = 0){
	static $list;
	if(!($uid && is_numeric($uid))){ //获取当前登录用户名
		return session('user_auth.username');
	}

	/* 获取缓存数据 */
	if(empty($list)){
		$list = cache('sys_active_user_list');
	}

	/* 查找用户信息 */
	$key = "u{$uid}";
	if(isset($list[$key])){ //已缓存，直接使用
		$name = $list[$key];
	} else { //调用接口获取用户信息
		$User = controller('user/UserApi','api');
		$info = $User->info($uid);
		if($info && isset($info[1])){
			$name = $list[$key] = $info[1];
			/* 缓存用户 */
			$count = count($list);
			$max   = config('USER_MAX_CACHE');
			while ($count-- > $max) {
				array_shift($list);
			}
			cache('sys_active_user_list', $list);
		} else {
			$name = '';
		}
	}
	return $name;
}

/**
 * 记录行为日志，并执行该行为的规则
 * @param string $action 行为标识
 * @param string $model 触发行为的模型名
 * @param int $record_id 触发行为的记录id
 * @param int $user_id 执行行为的用户id
 * @return boolean
 * @author huajie <banhuajie@163.com>
 * @modify Jason<1878566968@qq.com>
 */
function action_log($action = null, $model = null, $record_id = null, $user_id = null){

	//参数检查
	if(empty($action) || empty($model) || empty($record_id)){
		return '参数不能为空';
	}
	if(empty($user_id)){
		$user_id = is_login();
	}

	//查询行为,判断是否执行
	$action_info = db('Action')->getByName($action);
	if($action_info['status'] != 1){
		return '该行为被禁用或删除';
	}

	//插入行为日志
	$data['action_id']      =   $action_info['id'];
	$data['user_id']        =   $user_id;
	$data['action_ip']      =   ip2long(get_client_ip());
	$data['model']          =   $model;
	$data['record_id']      =   $record_id;
	$data['create_time']    =   app()->getBeginTime();

	//解析日志规则,生成日志备注
	if(!empty($action_info['log'])){
		if(preg_match_all('/\[(\S+?)\]/', $action_info['log'], $match)){
			$log['user']    =   $user_id;
			$log['record']  =   $record_id;
			$log['model']   =   $model;
			$log['time']    =   app()->getBeginTime();
			$log['data']    =   array('user'=>$user_id,'model'=>$model,'record'=>$record_id,'time'=>app()->getBeginTime());
			foreach ($match[1] as $value){
				$param = explode('|', $value);
				if(isset($param[1])){
					$replace[] = call_user_func($param[1],$log[$param[0]]);
				}else{
					$replace[] = $log[$param[0]];
				}
			}
			$data['remark'] =   str_replace($match[0], $replace, $action_info['log']);
		}else{
			$data['remark'] =   $action_info['log'];
		}
	}else{
		//未定义日志规则，记录操作url
		$data['remark']     =   '操作url：'.$_SERVER['REQUEST_URI'];
	}

	db('ActionLog')->insert($data);

	if(!empty($action_info['rule'])){
		//解析行为
		$rules = parse_action($action, $user_id);

		//执行行为
		$res = execute_action($rules, $action_info['id'], $user_id);
	}
}
/**
 * 解析行为规则
 * 规则定义  table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
 * 规则字段解释：table->要操作的数据表，不需要加表前缀；
 *              field->要操作的字段；
 *              condition->操作的条件，目前支持字符串，默认变量{$self}为执行行为的用户
 *              rule->对字段进行的具体操作，目前支持四则混合运算，如：1+score*2/2-3
 *              cycle->执行周期，单位（小时），表示$cycle小时内最多执行$max次
 *              max->单个周期内的最大执行次数（$cycle和$max必须同时定义，否则无效）
 * 单个行为后可加 ； 连接其他规则
 * @param string $action 行为id或者name
 * @param int $self 替换规则里的变量为执行用户的id
 * @return boolean|array: false解析出错 ， 成功返回规则数组
 * @author huajie <banhuajie@163.com>
 * @modify Jason<1878566968@qq.com>
 */
function parse_action($action = null, $self){
	if(empty($action)){
		return false;
	}

	//参数支持id或者name
	if(is_numeric($action)){
		$map[] = ['id','=',$action];
	}else{
		$map[] = ['name','=',$action];
	}

	//查询行为信息
	$info = db('Action')->where($map)->find();
	if(!$info || $info['status'] != 1){
		return false;
	}

	//解析规则:table:$table|field:$field|condition:$condition|rule:$rule[|cycle:$cycle|max:$max][;......]
	$rules = $info['rule'];
	$rules = str_replace('{$self}', $self, $rules);
	$rules = explode(';', $rules);
	$return = array();
	foreach ($rules as $key=>&$rule){
		if(empty($rule)) continue;
		$rule = explode('|', $rule);
		foreach ($rule as $k=>$fields){
			$field = empty($fields) ? array() : explode(':', $fields);
			if(!empty($field)){
				$return[$key][$field[0]] = $field[1];
			}
		}
		//cycle(检查周期)和max(周期内最大执行次数)必须同时存在，否则去掉这两个条件
		if(!array_key_exists('cycle', $return[$key]) || !array_key_exists('max', $return[$key])){
			unset($return[$key]['cycle'],$return[$key]['max']);
		}
	}

	return $return;
}

/**
 * 执行行为
 * @param array $rules 解析后的规则数组
 * @param int $action_id 行为id
 * @param array $user_id 执行的用户id
 * @return boolean false 失败 ， true 成功
 * @author huajie <banhuajie@163.com>
 */
function execute_action($rules = false, $action_id = null, $user_id = null){
	if(!$rules || empty($action_id) || empty($user_id)){
		return false;
	}

	$return = true;
	foreach ($rules as $rule){

		//检查执行周期
		$map = [['action_id','=',$action_id]
			,['user_id','=',$user_id]];
		$map[] = ['create_time','>', app()->getBeginTime() - intval($rule['cycle']) * 3600];
		$exec_count = db('ActionLog')->where($map)->count();
		if($exec_count > $rule['max']){
			continue;
		}

		//执行数据库操作
		$Model = db(ucfirst($rule['table']));
		$field = $rule['field'];
		$res = $Model->where($rule['condition'])->exp($field,$rule['rule'])->update();

		if(!$res){
			$return = false;
		}
	}
	return $return;
}

/**
 * 权限检测
 * @param string  $rule    检测的规则
 * @param string  $mode    check模式 字符串 
 * @return boolean
 */
function check_rule($rule, $type=1 , $mode='string'){
	//如果是超级管理员
	if(is_administrator()){
		return true;
	}
	static $Auth    =   null;
	if (!$Auth) {
		$Auth       =   new \auth\Auth();
	}
	if(!$Auth->check($rule,is_login(),$type,$mode)){
		return false;
	}
	return true;
}

/**
 * 获取文档封面图片
 * @param int $cover_id
 * @param string $field
 * @return 完整的数据  或者  指定的$field字段值
 * @author huajie <banhuajie@163.com>
 */
function get_cover($cover_id, $field = 'path'){
	if(empty($cover_id)){
		return false;
	}
	$picture = db('Picture')->where([
		['status','=',1]
		,['id','=',$cover_id]
	])->find();
	if($field == 'path'){
		if(!empty($picture['url'])){
			$picture['path'] = $picture['url'];
		}else{
			$tmpArr = explode('.',$picture['path']);
			$tmpArr[0] = strtolower($tmpArr[0]);
			$picture['path'] = implode('.',$tmpArr);
		}
	}
	return empty($field) ? $picture : $picture[$field];
}
function get_cover_info($id){
	$path = get_cover($id,'path');
	if(empty($path))
		return null;
	if(!is_file(Env::get('root_path').'public'.$path))
		return null;
	$pic = \think\Image::open(Env::get('root_path').'public'.$path);
	return array('path'=>$path,'width'=>$pic->width(),'height'=>$pic->height());
}

/**
 * @param $filename
 * @param int $width
 * @param string $height
 * @param bool $replace 是否替换原有缩略图
 * @return mixed|string
 * @auth Jason <1878566968@qq.com>
 */
function get_thumb_image($filename, $width = 100, $height = 'auto', $replace = false)
{
	$UPLOAD_URL = '';
	$UPLOAD_PATH = '';
	$filename = str_ireplace($UPLOAD_URL, '', $filename); //将URL转化为本地地址
	$info = pathinfo($filename);
	$oldFile = $info['dirname'] . config('pathinfo_depr') . $info['filename'] . '.' . $info['extension'];
	$thumbFile = $info['dirname'] . config('pathinfo_depr') . $info['filename'] . '_' . $width . '_' . $height . '.' . $info['extension'];

	$oldFile = str_replace('\\', '/', $oldFile);
	$thumbFile = str_replace('\\', '/', $thumbFile);

	$filename = ltrim($filename, '/');
	$oldFile = ltrim($oldFile, '/');
	$thumbFile = ltrim($thumbFile, '/');

    //原图不存在直接返回
    if (!file_exists($UPLOAD_PATH . $oldFile)) {
        @unlink($UPLOAD_PATH . $thumbFile);
        $info['src'] = $oldFile;
        $info['width'] = intval($width);
        $info['height'] = intval($height);
        return $info;
        //缩图已存在并且  replace替换为false
    } elseif (file_exists($UPLOAD_PATH . $thumbFile) && !$replace) {
        $imageinfo = getimagesize($UPLOAD_PATH . $thumbFile);
        $info['src'] = $thumbFile;
        $info['width'] = intval($imageinfo[0]);
        $info['height'] = intval($imageinfo[1]);
        return $info;
        //执行缩图操作
    } else {
        $oldimageinfo = getimagesize($UPLOAD_PATH . $oldFile);
        $old_image_width = intval($oldimageinfo[0]);
        $old_image_height = intval($oldimageinfo[1]);
        if ($old_image_width <= $width && $old_image_height <= $height) {
            @unlink($UPLOAD_PATH . $thumbFile);
            @copy($UPLOAD_PATH . $oldFile, $UPLOAD_PATH . $thumbFile);
            $info['src'] = $thumbFile;
            $info['width'] = $old_image_width;
            $info['height'] = $old_image_height;
            return $info;
        } else {
            if ($height == "auto") $height = $old_image_height * $width / $old_image_width;
            if ($width == "auto") $width = $old_image_width * $width / $old_image_height;
            if (intval($height) == 0 || intval($width) == 0) {
                return 0;
            }
			$image = \think\Image::open($oldFile);
			$res = $image->thumb($width , $height)->save($UPLOAD_PATH . $thumbFile);

            $info['src'] = $UPLOAD_PATH . $thumbFile;
            $info['width'] = $old_image_width;
            $info['height'] = $old_image_height;
            return $info;
        }
    }



}

/**通过ID获取到图片的缩略图
 * @param        $image_id 图片的ID
 * @param int    $width 需要取得的宽
 * @param string $height 需要取得的高
 * @param int    $type 图片的类型，qiniu 七牛，local 本地, sae SAE
 * @param bool   $replace 是否强制替换
 * @return string
 * @auth Jason <1878566968@qq.com>
 */
function get_thumb_image_by_id($image_id, $width = 100, $height = 'auto', $replace = false)
{

    $picture = get_cover($image_id);
    if (empty($picture)) 
        return null;
	$attach = get_thumb_image($picture, $width, $height, $replace);
	$attach['src'] = $attach['src'];
	return $attach['src'];

}

/**
 * phpmailer_smtp
 * @param array $from [
 *	'username'	=>'发件人帐号'
 *	,'password'	=>'发件人密码'
 *	,'nickname'	=>'发件人昵称'
 *	,'host'	=>'发件人邮箱服务器地址'
 *	,'port'	=>'远程服务器端口号'
 *	]
 *	@param string|array $to 收件人邮件地址
 *	|
 *	[
 *		'地址1','地址2'
 *	]
 *	|
 *	[
 *		'地址1'=>'称呼1'
 *		,'地址2'=>'称呼2'
 *	]
 *	@param array $data [
 *		'title'	=> '邮件标题'
 *		,'content'	=> '邮件正文内容'
 *	]
 */
function phpmailer_smtp($from = ['username'=>'','password'=>'','nickname'=>'','host'=>'smtp.qq.com','port'=>465],$to,$data =['title'=>'','content'=>'']){
	$mail = new \PHPMailer\PHPMailer\PHPMailer;
	//配置
	$mail->IsSMTP(); // 启用SMTP
	$mail->Port = $from['port'];
	$mail->SMTPSecure = 'ssl';
	$mail->Host=$from['host']; //smtp服务器的名称（这里以QQ邮箱为例）
	$mail->SMTPAuth = true; //启用smtp认证
	$mail->Username = $from['username']; //你的邮箱名
	$mail->Password = $from['password']; //邮箱密码
	$mail->From = $from['username']; //发件人地址（也就是你的邮箱地址）
	$mail->FromName = $from['nickname']; //发件人姓名
	$mail->WordWrap = 50; //设置每行字符长度
	$mail->IsHTML(true); // 是否HTML格式邮件
	$mail->CharSet='utf-8'; //设置邮件编码
	$mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示

	//添加收件人地址
	if(is_array($to)){
		foreach($to as $key=>$val){
			if(!is_numeric($key)){
				$mail->AddAddress($key,$val);
			}
			else{
				$mail->AddAddress($val);
			}
		}
	}
	else{
		$mail->AddAddress($to);
	}

	$mail->Subject =$data['title']; //邮件主题
	$mail->Body = $data['content']; //邮件内容
	return($mail->Send());
}

/**
 * view_base
 * @param string    $template 模板文件
 * 检测并设置可用视图目录
 * @author Jason <1878566968@qq.com>
 */
function view_base($template = ''){
	$module = Request()->module();
	//如果不是安装模块
	if($module != 'install'){
		/* 读取数据库中的配置 */
		$config =   cache('DB_CONFIG_DATA');
		if(!$config){
			$config =   api('Config/getListing');
			cache('DB_CONFIG_DATA',$config);
		}
		config($config,'app'); //添加配置
	}

	$eqp = get_eqp();
	$theme = config(strtoupper('DEFAULT_THEME_'.$module.'_'.$eqp));
	$theme = $theme ?: 'default';
	defined('VIEW_BASE') || define('VIEW_BASE',config('template.view_base'));
	$view_base = VIEW_BASE;
	$app = app();

	switch($eqp){
		//手机
	case 'phone':
		//设定皮肤目录为视图根目录
		config('template.view_base',$view_base.$theme.'/view_phone/');
		$app->view->init(config('template.'));
		//如果皮肤模板文件不存在
		if(!view_exists($template)){
			config('template.view_base',$view_base.$theme.'/view/');
			config('template.tpl_replace_string.__TEMPLATE__','default');
			$app->view->init(config('template.'));
		}
		else{
			config('template.tpl_replace_string.__TEMPLATE__',$theme);
			$app->view->init(config('template.'));
			break;
		}
		//不是默认皮肤 && 皮肤模板文件不存在
		if($theme != 'default' && !view_exists($template)){
			//设定默认皮肤目录为视图根目录
			config('template.view_base',$view_base.'default/view_phone/');
			config('template.tpl_replace_string.__TEMPLATE__','default');
			$app->view->init(config('template.'));
		}
		//如果皮肤模板文件不存在
		if(!view_exists($template)){
			config('template.view_base',$view_base.'default/view/');
			$app->view->init(config('template.'));
		}
		else{
			config('template.tpl_replace_string.__TEMPLATE__','default');
			$app->view->init(config('template.'));
			break;
		}
		//如果皮肤模板文件不存在
		if(!view_exists($template)){
			//取消视图根目录设置
			config('template.view_base','');
			$app->view->init(config('template.'));
		}
		break;
		//pc
	default:
		//设定皮肤目录为视图根目录
		config('template.view_base',$view_base.$theme.'/view/');
		$app->view->init(config('template.'));
		//不是默认皮肤 && 皮肤模板文件不存在
		if($theme != 'default' && !view_exists($template)){
			//设定默认皮肤目录为视图根目录
			config('template.view_base',$view_base.'default/view/');
			$app->view->init(config('template.'));
		}
		//如果皮肤模板文件不存在
		if(!view_exists($template)){
			//取消视图根目录设置
			config('template.view_base','');
			$app->view->init(config('template.'));
		}
		else{
			config('template.tpl_replace_string.__TEMPLATE__','default');
			$app->view->init(config('template.'));
			break;
		}
		break;
	}
	return $app;
}
/**
 * view_exists
 * 检测是否存在模板文件
 * @param string $template 模板文件
 */
function view_exists($template = '',$code = 200){
	return think\Response::create($template, 'view', $code)->exists($template);
}
/**
 * 渲染模板输出
 * @param string    $template 模板文件
 * @param array     $vars 模板变量
 * @param integer   $code 状态码
 * @param callable  $filter 内容过滤
 * @return \think\response\View
 */
function view($template = '', $vars = [], $code = 200, $filter = null){
	view_base($template);
	return think\Response::create($template, 'view', $code)->assign($vars)->filter($filter);
}
/**
 * 渲染模板输出
 * @param string    $template 模板文件
 * @param array     $vars 模板变量
 * @param integer   $code 状态码
 * @param callable  $filter 内容过滤
 * @return \think\response\View
 */
function fetch($template = '', $vars = [], $code = 200, $filter = null){
	$app = view_base($template);
	return $app->view->fetch($template);
}

/**
 * get_eqp
 * 获取设备类型
 * add by Jason 2016-05-04 
 */
function get_eqp(){
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile|android)/i";
	if ((preg_match($uachar, $ua))) {
		$eqp = 'phone';
	}
	else{
		$eqp = 'pc';
	}
	return $eqp;
}

/**
 * time_version
 * 用于生成time_version识别码，以便于统一更新用户缓存文件
 */
function time_version(){
	$version = cache('time_version');
	if(empty($version)){
		$version = app()->getBeginTime();
		cache('time_version',$version);
	}
	return $version;
}
