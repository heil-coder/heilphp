<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

// 检测环境是否支持可写
define('IS_WRITE',true);		//检查是否sae模式

/**
 * 系统环境检测
 * @return array 系统环境数据
 */
function check_env(){
    $items = array(
        'os'      => array('操作系统', '不限制', '类Unix', PHP_OS, 'success'),
        'php'     => array('PHP版本', '5.5', '5.5+', PHP_VERSION, 'success'),
        'upload'  => array('附件上传', '不限制', '2M+', '未知', 'success'),
        'gd'      => array('GD库', '2.0', '2.0+', '未知', 'success'),
        'disk'    => array('磁盘空间', '5M', '不限制', '未知', 'success'),
    );

    //PHP环境检测
    if($items['php'][3] < $items['php'][1]){
        $items['php'][4] = 'error';
		Session::set('error', true);
    }

    //附件上传检测
    if(@ini_get('file_uploads'))
        $items['upload'][3] = ini_get('upload_max_filesize');

    //GD库检测
    $tmp = function_exists('gd_info') ? gd_info() : array();
    if(empty($tmp['GD Version'])){
        $items['gd'][3] = '未安装';
        $items['gd'][4] = 'error';
		Session::set('error', true);
    } else {
        $items['gd'][3] = $tmp['GD Version'];
    }
    unset($tmp);

    //磁盘空间检测
    if(function_exists('disk_free_space')) {
        $items['disk'][3] = floor(disk_free_space(realpath('./') . '/') / (1024*1024)).'M';
    }

    return $items;
}

/**
 * 目录，文件读写检测
 * @return array 检测数据
 */
function check_dirfile(){

}

/**
 * 函数检测
 * @return array 检测数据
 */
function check_func(){
    $items = array(
        array('pdo','支持','success','类'),
        array('pdo_mysql','支持','success','模块'),
        array('file_get_contents', '支持', 'success','函数'),
        array('mb_strlen',		   '支持', 'success','函数'),
    );

    foreach ($items as &$val) {
        if(('类'==$val[3] && !class_exists($val[0]))
            || ('模块'==$val[3] && !extension_loaded($val[0]))
            || ('函数'==$val[3] && !function_exists($val[0]))
            ){
            $val[1] = '不支持';
            $val[2] = 'error';
			Session::set('error', true);
        }
    }

    return $items;
}

/**
 * 写入配置文件
 * @param  array $config 配置信息
 */
function write_config($config){
    if(is_array($config)){
        //读取配置内容
        $database = file_get_contents(Env::get('module_path'). 'data/database.tpl');
        //替换配置项
        foreach ($config as $name => $value) {
            $database = str_replace("[{$name}]", $value, $database);
        }
        $app = file_get_contents(Env::get('module_path'). 'data/app.tpl');
        $heilphp = file_get_contents(Env::get('module_path'). 'data/heilphp.tpl');

        //写入应用配置文件
        if(!IS_WRITE){
            return '由于您的环境不可写，请复制下面的配置文件内容覆盖到相关的配置文件，然后再登录后台。<p>'.realpath(Env::get('config_path')).'/database.php</p>
            <textarea name="" style="width:650px;height:185px">'.$database.'</textarea>
            <p>'.realpath(Env::get('config_path')).'/app.php</p>
            <textarea name="" style="width:650px;height:125px">'.$app.'</textarea>
            <p>'.realpath(Env::get('config_path')).'/heilphp.php</p>
            <textarea name="" style="width:650px;height:125px">'.$heilphp.'</textarea>';
        }else{
            if(file_put_contents(Env::get('config_path') . 'database.php', $database) &&
               file_put_contents(Env::get('config_path') . 'app.php', $app) &&
               file_put_contents(Env::get('config_path') . 'heilphp.php', $heilphp)){
                show_msg('配置文件写入成功');
            } else {
                show_msg('配置文件写入失败！', 'error');
                session('error', true);
            }
            return '';
        }

    }
}

/**
 * 创建数据表
 * @param  resource $db 数据库连接资源
 */
function create_tables($db, $prefix = ''){
    //读取SQL文件
    $sql = file_get_contents(Env::get('module_path') . 'data/install.sql');
    $sql = str_replace("\r", "\n", $sql);
    $sql = explode(";\n", $sql);

    //替换表前缀
    $orginal = Config::get('ORIGINAL_TABLE_PREFIX');
    !empty($orginal) && $sql = str_replace(" `{$orginal}", " `{$prefix}", $sql);

    //开始安装
    show_msg('开始安装数据库...');
    foreach ($sql as $value) {
        $value = trim($value);
        if(empty($value)) continue;
        if(substr($value, 0, 12) == 'CREATE TABLE') {
            $name = preg_replace("/^CREATE TABLE `(\w+)` .*/s", "\\1", $value);
            $msg  = "创建数据表{$name}";
            if(false !== $db->execute($value)){
                show_msg($msg . '...成功');
            } else {
                show_msg($msg . '...失败！', 'error');
                session('error', true);
            }
        } else {
            $db->execute($value);
        }

    }
}

function register_administrator($db, $prefix, $admin, $salt){
    show_msg('开始注册创始人帐号...');
    $password = encrypt_password($admin['password'], $salt);

    $sql = "INSERT INTO `[PREFIX]member` VALUES ".
           "('1', '[NAME]','[PASSWORD]','[SALT]','','','[NICK]', '0', '0000-00-00', '', '0', '1', '0', '[TIME]', '0', '[TIME]', '1',null);";
    $sql = str_replace(
        array('[PREFIX]', '[NAME]','[PASSWORD]','[SALT]','[NICK]', '[TIME]'),
        array($prefix, $admin['username'],$password,$salt,$admin['username'], App::getBeginTime()),
        $sql);
    $db->execute($sql);
    show_msg('创始人帐号注册完成！');
}

/**
 * 更新数据表
 * @param  resource $db 数据库连接资源
 */
function update_tables($db, $prefix = ''){
 }

/**
 * 及时显示提示信息
 * @param  string $msg 提示信息
 */
function show_msg($msg, $class = ''){
    echo "<script type=\"text/javascript\">showmsg(\"{$msg}\", \"{$class}\")</script>";
    flush();
    ob_flush();
}
/**
 * 完成后重定向
 */
function complete_redirect(){
    echo "<script type=\"text/javascript\">completRedirect()</script>";
    flush();
    ob_flush();
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
