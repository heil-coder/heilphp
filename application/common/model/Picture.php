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
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace app\common\model;
use think\Model;

/**
 * 图片模型
 * 负责图片的上传
 */

class Picture extends Model{
	protected $autoWriteTimestamp = true;

	protected $insert = ['status'=>1];

    /**
     * 文件上传
     * @param  array  $file		要上传的文件表单名
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($file = '', $setting, $driver = 'Local', $config = null){
		$file = Request()->file($file);	

		//单图上传
		if(is_object($file)){
			return $this->edit($file,$setting,$driver,$config);
		}
		//多图上传
		elseif(is_array($file)){
			//foreach($file as $key =>&$value){
			//	$res = $this->edit($value,$setting,$driver,$config);
			//	if($res === false){
			//		unset($file[$key]);
			//	}
			//}
			//TODO 多图上传待调试
			//return $file;
		}
		//没有收到上传文件
		else{
			return false;
		}
    }
    /**
     * 文件上传
     * @param  array  $file		要上传文件对象 ThinkPHP的file对象
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
	protected function edit(&$file,$setting,$driver = 'Local',$config = null){
		$map = [];
		$map[] = ['md5','=',$file->hash('md5')];
		$map[] = ['sha1','=',$file->hash('sha1')];
		$res = $this->where($map)->find();
		if($res){
			return $res;	
		}
		else{
			$info = $file->validate(['ext'=>'jpg,jpeg,png,gif,bmp'])->move(env('root_path').'public/uploads/picture');
			if(empty($info)){
				$this->error = $file->getError();
				return false;	
			}
			$data = [
				'type'	=>		'local'
				,'path'	=>		'/uploads/picture/'.$info->getSaveName()
				,'md5'	=>		$info->hash('md5')
				,'sha1'	=>		$info->hash('sha1')
			];
			$res = $this->isUpdate(false)->save($data);
			$data['id'] = $this->id;
			return $data;
		}
	}

    /**
     * 下载指定文件
     * @param  number  $root 文件存储根目录
     * @param  integer $id   文件ID
     * @param  string   $args     回调函数参数
     * @return boolean       false-下载失败，否则输出下载文件
     */
    public function download($root, $id, $callback = null, $args = null){
        /* 获取下载文件信息 */
        $file = $this->find($id);
        if(!$file){
            $this->error = '不存在该文件！';
            return false;
        }

        /* 下载文件 */
        switch ($file['location']) {
            case 0: //下载本地文件
                $file['rootpath'] = $root;
                return $this->downLocalFile($file, $callback, $args);
            case 1: //TODO: 下载远程FTP文件
                break;
            default:
                $this->error = '不支持的文件存储类型！';
                return false;

        }

    }

    /**
     * 检测当前上传的文件是否已经存在
     * @param  array   $file 文件上传数组
     * @return boolean       文件信息， false - 不存在该文件
     */
    public function isFile($file){
        if(empty($file['md5'])){
            throw new \Exception('缺少参数:md5');
        }
        /* 查找文件 */
		$map = array('md5' => $file['md5'],'sha1'=>$file['sha1'],);
        return $this->field(true)->where($map)->find();
    }

    /**
     * 下载本地文件
     * @param  array    $file     文件信息数组
     * @param  callable $callback 下载回调函数，一般用于增加下载次数
     * @param  string   $args     回调函数参数
     * @return boolean            下载失败返回false
     */
    private function downLocalFile($file, $callback = null, $args = null){
        if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){
            /* 调用回调函数新增下载数 */
            is_callable($callback) && call_user_func($callback, $args);

            /* 执行下载 */ //TODO: 大文件断点续传
            header("Content-Description: File Transfer");
            header('Content-type: ' . $file['type']);
            header('Content-Length:' . $file['size']);
            if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
                header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
            } else {
                header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
            }
            readfile($file['rootpath'].$file['savepath'].$file['savename']);
            exit;
        } else {
            $this->error = '文件已被删除！';
            return false;
        }
    }

	/**
	 * 清除数据库存在但本地不存在的数据
	 * @param $data
	 */
	public function removeTrash($data){
		$this->where(array('id'=>$data['id'],))->delete();
	}
	/**
	 * cropPicture
	 * 裁剪图片
	 * @param array $crop 裁剪参数数组 ['width','height','x坐标','y坐标']
	 * @param array $data 图片信息数组 ['id'=>'picture表图片id','path'=>'图片路径']
	 * @author Jason <187866968@qq.com>
	 */
	public function cropPicture($crop = null,$data){
		$path = $data['path'];
		if(empty($crop)){
			return '请传入裁剪参数';
		}
		//解析crop参数
		$crop = explode(',', $crop);
		$x = $crop[0];
		$y = $crop[1];
		$width = $crop[2];
		$height = $crop[3];
		//本地环境
		$publicPath = env('root_path').'public';
		$image = \think\Image::open($publicPath . $path);
		//生成将单位换算成为像素
		$x = $x * $image->width();
		$y = $y * $image->height();
		$width = $width * $image->width();
		$height = $height * $image->height();
		//如果宽度和高度近似相等，则令宽和高一样
		if (abs($height - $width) < $height * 0.01) {
			$height = min($height, $width);
			$width = $height;
		}
		//调用组件裁剪头像
		$image->crop($width, $height, $x, $y);
		$tmpPath =  dirname($path).'/'.basename($path).'_'.implode('_',$crop).'.'.$image->type();
		$res = $image->save($publicPath . $tmpPath);
		if(empty($res)){
			return false;
		}
		$data = [
				'type'	=>	'local'
				,'path'	=>	$tmpPath
				,'md5'	=>	hash_file('md5',$publicPath . $tmpPath)
				,'sha1'	=>	hash_file('sha1',$publicPath . $tmpPath)
		];
		$map = [];
		$map[] = ['md5','=',$data['md5']];
		$map[] = ['sha1','=',$data['sha1']];
		$res = $this->where($map)->find();
		if($res){
			unlink($publicPath . $data['path']);
			return $res;	
		}
		else{
			$res = $this->isUpdate(false)->save($data);
			$data['id'] = $this->id;
			return $data;
		}

	}
}
