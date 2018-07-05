<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use app\admin\controller\Admin;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */
class File extends Admin{

    /* 文件上传 */
    public function upload(){
		$return  = ['code' => 1, 'msg' => '上传成功', 'data' => ''];
		/* 调用文件上传组件上传文件 */
		$File = model('File');
		$file_driver = Config('DOWNLOAD_UPLOAD_DRIVER');
		$info = $File->upload(
			'download',
			Config('DOWNLOAD_UPLOAD'),
			Config('DOWNLOAD_UPLOAD_DRIVER'),
			Config("UPLOAD_{$file_driver}_CONFIG")
		);

        /* 记录附件信息 */
        if($info){
			$return['msg'] = $info['name'] ?: '上传成功';
			$return['data'] = $info;
        } else {
			$return['msg'] = $File->getError() ?: '上传失败';
        }
		return json($return);
    }

    /* 下载文件 */
    public function download($id = null){
        if(empty($id) || !is_numeric($id)){
            $this->error('参数错误！');
        }

        $logic = model('Download', 'Logic');
        if(!$logic->download($id)){
            $this->error($logic->getError());
        }

    }

    /**
     * 上传图片
     * @author huajie <banhuajie@163.com>
     */
    public function uploadPicture(){
		$return  = ['code' => 1, 'msg' => '上传成功', 'data' => ''];
        //TODO: 用户登录检测

        /* 调用文件上传组件上传文件 */
        $Picture = model('Picture');
        $pic_driver = Config('PICTURE_UPLOAD_DRIVER');
        $info = $Picture->upload(
			'download',
            Config('PICTURE_UPLOAD'),
            Config('PICTURE_UPLOAD_DRIVER'),
            Config("UPLOAD_{$pic_driver}_CONFIG")
        ); //TODO:上传到远程服务器

        /* 记录附件信息 */
        if($info){
			$return['msg'] = '上传成功';
			$return['data'] = $info;
        } else {
			$return['msg'] = $File->getError() ?: '上传失败';
        }
		return json($return);
    }
}
