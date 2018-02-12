<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace app\admin\controller;

use Db;
use Config;
use Session;
use Request;
use Env;
use App;
use app\admin\controller\Admin;
use heil\Database;

/**
 * 数据库备份还原控制器
 * @author Jason <1878566968@qq.com>
 */
class Datamanage extends Admin{

    /**
     * 数据库备份/还原列表
     * @param  String $type import-还原，export-备份
	 * @author Jason <1878566968@qq.com>
     */
    public function index($type = null){
        switch ($type) {
            /* 数据还原 */
            case 'import':
                break;

            /* 数据备份 */
            case 'export':
                $Db    = Db::connect();
                $list  = $Db->query('SHOW TABLE STATUS');
                $list  = array_map('array_change_key_case', $list);
                $title = '数据备份';
                break;

            default:
                $this->error('参数错误！');
        }

        //渲染模板
        $this->assign('meta_title', $title);
        $this->assign('list', $list);
		return view($type);
    }
    /**
     * 备份数据库
     * @param  String  $tables 表名
     * @param  Integer $id     表ID
     * @param  Integer $start  起始行数
     * @author Jason <1878566968@.qq.com>
     */
    public function export($tables = null, $id = null, $start = null){
        if(Request::isPost() && !empty($tables) && is_array($tables)){ //初始化
            $path = Env::get('root_path').'data';
            if(!is_dir($path)){
                mkdir($path, 0755, true);
            }
            //读取备份配置
            $config = array(
                'path'     => realpath($path) . DIRECTORY_SEPARATOR,
                'part'     => 20971520,
                'compress' => 1,
                'level'    => 9,
            );

            //检查是否有正在执行的任务
            $lock = "{$config['path']}backup.lock";
            if(is_file($lock)){
                $this->error('检测到有一个备份任务正在执行，请稍后再试！');
            } else {
                //创建锁文件
                file_put_contents($lock, App::getBeginTime());
            }

            //检查备份目录是否可写
            is_writeable($config['path']) || $this->error('备份目录不存在或不可写，请检查后重试！');
			Session::set('backup_config', $config);

            //生成备份文件信息
            $file = array(
                'name' => date('Ymd-His', App::getBeginTime()),
                'part' => 1,
            );
			Session::set('backup_file', $file);

            //缓存要备份的表
			Session::set('backup_tables', $tables);

            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $tab = array('id' => 0, 'start' => 0);
				$this->success('初始化成功','',array('tables'=>$tables,'tab'=>$tab));
            } else {
				$this->error('初始化失败，备份文件创建失败！');
            }
        } elseif (Request::isGet() && is_numeric($id) && is_numeric($start)) { //备份数据
            $tables = Session::get('backup_tables');
            //备份指定表
            $Database = new Database(Session::get('backup_file'), Session::get('backup_config'));
            $start  = $Database->backup($tables[$id], $start);
            if(false === $start){ //出错
				$this->error('参数错误！');
            } elseif (0 === $start) { //下一表
                if(isset($tables[++$id])){
                    $tab = array('id' => $id, 'start' => 0);
					$this->success('备份完成！','',array('tab'=>$tab));
                } else { //备份完成，清空缓存
                    unlink(session('backup_config.path') . 'backup.lock');
					Session::delete('backup_tables');
					Session::delete('backup_file');
					Session::delete('backup_config');
					$this->success('备份完成！');
				}
            } else {
                $tab  = array('id' => $id, 'start' => $start[0]);
                $rate = floor(100 * ($start[0] / $start[1]));
				$this->success("正在备份...({$rate}%)",'',array('tab'=>$tab));
            }

		} else { //出错
			$this->error('参数错误！');
		}
    }
    /**
     * 优化表
     * @param  String $tables 表名
     * @author Jason <1878566968@qq.com>
     */
    public function optimize($tables = null){
        if($tables) {
            $Db   = Db::connect();
            if(is_array($tables)){
                $tables = implode('`,`', $tables);
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");

                if($list){
					$this->success('数据表优化完成！');
                } else {
					$this->error('数据表优化出错请重试！');
				}
            } else {
                $list = $Db->query("OPTIMIZE TABLE `{$tables}`");
                if($list){
					$this->success("数据表'{$tables}'优化完成！");
                } else {
					$this->error("数据表'{$tables}'优化出错请重试！");
                }
            }
        } else {
			$this->error('请指定要优化的表！');
		}
    }
    /**
     * 修复表
     * @param  String $tables 表名
     * @author Jason	<1878566968@qq.com>
     */
	public function repair($tables = null){
		if($tables) {
			$Db   = Db::connect();
			if(is_array($tables)){
				$tables = implode('`,`', $tables);
				$list = $Db->query("REPAIR TABLE `{$tables}`");

				if($list){
					$this->success('数据表修复完成！');
				} else {
					$this->error('数据表修复出错请重试！');
				}
			} else {
				$list = $Db->query("REPAIR TABLE `{$tables}`");
				if($list){
					$this->success("数据表'{$tables}'修复完成！");
				} else {
					$this->error("数据表'{$tables}'修复出错请重试！");
				}
			}
		} else {
			$this->error('请指定要修复的表！');
		}
	}

}
