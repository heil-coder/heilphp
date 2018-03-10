<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use Request;
use app\admin\controller\Admin;
use Env;

/**
 * 扩展插件后台管理页面
 * @author Jason <1878566968@qq.com>
 */
class Addons extends Admin {

    public function _initialize(){
        $this->assign('_extra_menu',array(
            '已装插件后台'=> model('Addons')->getAdminList(),
        ));
        parent::_initialize();
    }
    /**
     * 插件列表
     */
    public function index(){
        $this->assign('meta_title','插件列表');
        $list       =   model('Addons')->getList();
        $request    =   (array)Request::param();
        $total      =   $list? count($list) : 1 ;
        $listRows   =   config('LIST_ROWS') > 0 ? config('LIST_ROWS') : 10;
        $page       =   model('Addons')->paginate($listRows,$total);
        $voList     =   array_slice($list, $listRows *($page->getCurrentPage()-1), $listRows);
        $p          =   $page->render();
        $this->assign('_list', $voList);
        $this->assign('_page', $p? $p: '');
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
		return view();
    }
    /**
     * 设置插件页面
     */
    public function config(){
        $id     =   Request::param('id/d');
        $addon  =   db('Addons')->find($id);
        if(!$addon)
            $this->error('插件未安装');
        $addon_class = get_addon_class($addon['name']);
        if(!class_exists($addon_class))
            trace("插件{$addon['name']}无法实例化,",'ADDONS','ERR');
        $data  =   new $addon_class;
        $addon['addon_path'] = $data->addon_path;
        $addon['custom_config'] = $data->custom_config;
        $this->assign('meta_title','设置插件-'.$data->info['title']);
        $db_config = $addon['config'];
        $addon['config'] = include $data->config_file;
        if($db_config){
            $db_config = json_decode($db_config, true);
            foreach ($addon['config'] as $key => $value) {
                if($value['type'] != 'group'){
                    $addon['config'][$key]['value'] = $db_config[$key];
                }else{
                    foreach ($value['options'] as $gourp => $options) {
                        foreach ($options['options'] as $gkey => $value) {
                            $addon['config'][$key]['options'][$gourp]['options'][$gkey]['value'] = $db_config[$gkey];
                        }
                    }
                }
            }
        }
        $this->assign('data',$addon);
        if($addon['custom_config'])
            $this->assign('custom_config', $this->fetch($addon['addon_path'].$addon['custom_config']));
		return view();
    }
    /**
     * 保存插件设置
     */
    public function saveConfig(){
        $id     =   Request::param('id/d');
        $config =   Request::param('config/a');
        $flag = db('Addons')->where('id',$id)->setField('config',json_encode($config));
        if($flag !== false){
            $this->success('保存成功', Cookie('__forward__'));
        }else{
            $this->error('保存失败');
        }
    }
    /**
     * 安装插件
     */
    public function install(){
        $addon_name     =   trim(Request::param('addon_name/s'));
        $class          =   get_addon_class($addon_name);
        if(!class_exists($class))
            $this->error('插件不存在');
        $addons  =   new $class;
        $info = $addons->info;
        if(!$info || !$addons->checkInfo())//检测信息的正确性
            $this->error('插件信息缺失');
        session('addons_install_error',null);
        $install_flag   =   $addons->install();
        if(!$install_flag){
            $this->error('执行插件预安装操作失败'.session('addons_install_error'));
        }
        $addonsModel    =   model('Addons');
        $data           =   $info;
        if(is_array($addons->admin_list) && $addons->admin_list !== array()){
            $data['has_adminlist'] = 1;
        }else{
            $data['has_adminlist'] = 0;
        }
        if(!$data)
            $this->error($addonsModel->getError());
        if($addonsModel->save($data)){
            $config         =   array('config'=>json_encode($addons->getConfig()));
            $addonsModel->where('name',$addon_name)->find()->save($config);
            $hooks_update   =   model('Hooks')->updateHooks($addon_name);
            if($hooks_update){
                cache('hooks', null);
                $this->success('安装成功');
            }else{
                $addonsModel->where("name='{$addon_name}'")->delete();
                $this->error('更新钩子处插件失败,请卸载后尝试重新安装');
            }

        }else{
            $this->error('写入插件数据失败');
        }
    }
    /**
     * 卸载插件
     */
    public function uninstall(){
        $addonsModel    =   model('Addons');
        $id             =   trim(Request::param('id/d'));
        $db_addons      =   $addonsModel->find($id);
        $class          =   get_addon_class($db_addons['name']);
        $this->assign('jumpUrl',Url('index'));
        if(!$db_addons || !class_exists($class))
            $this->error('插件不存在');
        session('addons_uninstall_error',null);
        $addons =   new $class;
        $uninstall_flag =   $addons->uninstall();
        if(!$uninstall_flag)
            $this->error('执行插件预卸载操作失败'.session('addons_uninstall_error'));
        $hooks_update   =   model('Hooks')->removeHooks($db_addons['name']);
        if($hooks_update === false){
            $this->error('卸载插件所挂载的钩子数据失败');
        }
        cache('hooks', null);
        $delete = $addonsModel->where('name',$db_addons['name'])->delete();
        if($delete === false){
            $this->error('卸载插件失败');
        }else{
            $this->success('卸载成功');
        }
    }
    /**
     * 启用插件
     */
    public function enable(){
        $id     =   Request::param('id/d');
        $msg    =   array('success'=>'启用成功', 'error'=>'启用失败');
        cache('hooks', null);
        $this->resume('Addons', [['id','=',$id]], $msg);
    }

    /**
     * 禁用插件
     */
    public function disable(){
        $id     =   Request::param('id/d');
        $msg    =   array('success'=>'禁用成功', 'error'=>'禁用失败');
        cache('hooks', null);
        $this->forbid('Addons',[['id','=',$id]], $msg);
    }
    /**
     * 钩子列表
     */
    public function hooks(){
        $this->assign('meta_title','钩子列表');
        $map    =   $fields =   array();
        $list   =   $this->getListing(model("Hooks")->field($fields),$map)->toArray();
        int_to_string($list, array('type'=>config('HOOKS_TYPE')));
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('list', $list );
		return view();
    }
    //钩子新增、挂载插件页面
	public function addhook(){
        $this->assign('data', null);
        $this->assign('meta_title','新增钩子');
        return view('edithook');
    }
    //钩子编辑、挂载插件页面
    public function edithook($id){
        $hook = model('Hooks')->field(true)->find($id);
        $this->assign('data',$hook);
        $this->assign('meta_title','编辑钩子');
        return view();
    }
	//钩子信息更新
    public function updateHook(){
        $hookModel  =   model('Hooks');
        $data       =   Request::only('id,name,description,type,addons,status');
        if($data){
            if($data['id']){
                $flag = $hookModel->get($data['id'])->save($data);
                if($flag !== false){
                    cache('hooks', null);
                    $this->success('更新成功', Cookie('__forward__'));
                }else{
                    $this->error('更新失败');
                }
            }else{
                $flag = $hookModel->save($data);
                if($flag){
                    cache('hooks', null);
                    $this->success('新增成功', Cookie('__forward__'));
                }else{
                    $this->error('新增失败');
                }
            }
        }else{
            $this->error($hookModel->getError());
        }
    }
    //超级管理员删除钩子
    public function delhook($id){
        if(db('Hooks')->where('id',$id)->delete() !== false){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    //创建向导首页
    public function create(){
        if(!is_writable(Env::get('root_path').HEILPHP_ADDON_PATH))
            $this->error('您没有创建目录写入权限，无法使用此功能');

        $hooks = db('Hooks')->field('name,description')->select();
        $this->assign('Hooks',$hooks);
        $this->assign('meta_title','创建向导');
		return view();
    }
}
