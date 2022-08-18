<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use think\Controller;

/**
 * 帮助文件制器
 * @author Jason <1878566968@qq.com>
 */
class Help extends Admin{

    //默认操作
    public function index(){
		$docRoot = $_SERVER['DOCUMENT_ROOT'] . (strrchr($_SERVER['DOCUMENT_ROOT'],'/') =='/' ? '' : '/');
		$dir = $docRoot . "uploads/help/";
        try {
            $file = scandir($dir);
        }
        catch (\Exception $e){
            $file = [];
        }
		$fileList = array();
		foreach($file as $name){
			if( in_array(substr(strrchr($name, '.'), 1) , array('doc','docx','pdf'))){
				$fileList[] = $name;
			}
		}

		!empty($fileList) && $this->assign("fileList", $fileList);
        return view();
    }
}
