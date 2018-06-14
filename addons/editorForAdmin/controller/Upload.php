<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace addons\editorForAdmin\controller;
use app\index\controller\Addons;

class Upload extends Addons{

	public $uploader = null;

	/* 上传图片 */
	public function upload(){
		session('upload_error', null);
		/* 上传配置 */
		$setting = Config('EDITOR_UPLOAD');

		/* 调用文件上传组件上传文件 */
		$file = Request()->file('imgFile');	
		$info = $file->validate(['ext'=>'jpg,jpeg,png,gif,bmp'])->move(env('root_path').'public/uploads/picture');
		if(empty($info)){
			$this->error = $file->getError();
			$return = [
				'error'	=> 1
				,'message'	=> $this->error
			];
			return null;
		}

		$data = [
			'type'	=>		'local'
			,'path'	=>		'/uploads/picture/'.$info->getSaveName()
			,'md5'	=>		$info->hash('md5')
			,'sha1'	=>		$info->hash('sha1')
		];

		$map = [];
		$map[] = ['md5','=',$data['md5']];
		$map[] = ['sha1','=',$data['sha1']];
		$res = Db('Picture')->where($map)->find();
		if(empty($res)){
			$res = Db('Picture')->insertGetId($data);
			$data['id'] = $res;
		}

		$return['fullpath'] = $data['path'];
		return $return;
	}

	//keditor编辑器上传图片处理
	public function ke_upimg(){
		/* 返回标准数据 */
		$return  = array('error' => 0, 'info' => '上传成功', 'data' => '');
		$img = $this->upload();
		/* 记录附件信息 */
		if($img){
			$return['url'] = $img['fullpath'];
			unset($return['info'], $return['data']);
		} else {
			$return['error'] = 1;
			$return['message']   = $this->error;
		}

		/* 返回JSON数据 */
		exit(json_encode($return));
	}

	//ueditor编辑器上传图片处理
	public function ue_upimg(){

		$img = $this->upload();
		$return = array();
		$return['url'] = $img['fullpath'];
		$title = htmlspecialchars($_POST['pictitle'], ENT_QUOTES);
		$return['title'] = $title;
		$return['original'] = $img['imgFile']['name'];
		$return['state'] = ($img)? 'SUCCESS' : session('upload_error');
		/* 返回JSON数据 */
		$this->ajaxReturn($return);
	}

}
