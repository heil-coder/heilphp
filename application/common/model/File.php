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

namespace app\common\model;
use think\Model;

/**
 * 文件模型
 * 负责文件的下载和上传
 */

class File extends Model{
	protected $autoWriteTimestamp = true;

    /**
     * 文件模型字段映射
     * @var array
     */
    protected $_map = array(
        'type' => 'mime',
    );

    /**
     * 文件上传
     * @param  string  $file 文件上传表单名 
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function upload($file,$setting, $driver = 'Local', $config = null){
        /* 上传文件 */
        //$setting['callback'] = array($this, 'isFile');
		//$setting['removeTrash'] = array($this, 'removeTrash');

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
			$this->error = '没有接收到上传文件';
			return false;
		}
    }
    /**
     * 文件上传
     * @param  string  $file 文件上传表单名 
     * @param  array  $setting 文件上传配置
     * @param  string $driver  上传驱动名称
     * @param  array  $config  上传驱动配置
     * @return array           文件上传成功后的信息
     */
    public function edit($file,$setting, $driver = 'Local', $config = null){
		if(extension_loaded('php_fileinfo')){
			$mime = $file->getMime();
		}

		$map = [];
		$map[] = ['md5','=',$file->hash('md5')];
		$map[] = ['sha1','=',$file->hash('sha1')];
		$res = $this->where($map)->find();
		if($res){
			return $res;	
		}
		else{
			$this->insert['location'] = 'ftp' === strtolower($driver) ? 1 : 0;
			$info = $file->move(env('root_path').'public/uploads/file');
			$data = [
				'name'				=> $info->getInfo('name')
				,'savename'			=> $info->getFileName()
				,'savepath'			=> '/uploads/file/'.$info->getSaveName()
				,'size'				=> $info->getInfo('size')
				,'md5'				=> $info->hash('md5')
				,'sha1'				=> $info->hash('sha1')
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
			case 1: //下载FTP文件
				$file['rootpath'] = $root;
				return $this->downFtpFile($file, $callback, $args);
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
	 * 下载ftp文件
	 * @param  array    $file     文件信息数组
	 * @param  callable $callback 下载回调函数，一般用于增加下载次数
	 * @param  string   $args     回调函数参数
	 * @return boolean            下载失败返回false
	 */
	private function downFtpFile($file, $callback = null, $args = null){
		/* 调用回调函数新增下载数 */
		is_callable($callback) && call_user_func($callback, $args);

		$host = C('DOWNLOAD_HOST.host');
		$root = explode('/', $file['rootpath']);
		$file['savepath'] = $root[3].'/'.$file['savepath'];

		$data = array($file['savepath'], $file['savename'], $file['name'], $file['mime']);
		$data = json_encode($data);
		$key = think_encrypt($data, C('DATA_AUTH_KEY'), 600);

		header("Location:http://{$host}/onethink.php?key={$key}");
	}

	/**
	 * 清除数据库存在但本地不存在的数据
	 * @param $data
	 */
	public function removeTrash($data){
		$this->where(array('id'=>$data['id'],))->delete();
	}

}
