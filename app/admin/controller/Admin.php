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
use Db;
use Config;
use Request;

/**
 * 后台基础控制器
 * @author Jason <1878566968@qq.com>
 */
class Admin extends Controller {
    /**
     * 后台控制器初始化
     */
    protected function initialize(){
        /* 读取数据库中的配置 */
        $config =   cache('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/getListing');
            cache('DB_CONFIG_DATA',$config);
        }
		config($config,'app'); //添加配置
    }
    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=example
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Db $Db	表名或数据库实例 模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author Jason <1878566968@qq.com>
     *
     * @return array|false
     * 返回数据集
     */
    protected function getListing ($Db,$where=array(),$order='',$field=true){
        $options    =   array();
        $REQUEST    =   (array)Request::param();
        if(is_string($Db)){
            $Db =   Db::name($Db);
        }

		$table = $Db->getTable();
		$tableFields = $Db->getConnection()->getTableFields($table);
		//如果存在软删除字段
		$isSoftDelete = in_array('delete_time',$tableFields) ? true : false;

        $OPT        =   new \ReflectionProperty($Db,'options');
        $OPT->setAccessible(true);

        $pk         =   $Db->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
		}elseif( $order==='' /*&& empty($options['order'])*/ && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        if( !empty($where)){
            $options['where']   =   $where;
        }
		else{
            $options['where']   =  1; 
		}
        $options      =   array_merge( (array)$OPT->getValue($Db), $options );

		$total        =   $isSoftDelete ? $Db->where($options['where'])->whereNull('delete_time')->count() : $Db->where($options['where'])->count();
        $this->assign('_total',$total);

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = Config::get('LIST_ROWS') > 0 ? Config::get('LIST_ROWS') : 10;
        }

		$Db = $Db->removeOption(true);
		$page = $isSoftDelete ? $Db->where($options['where'])->whereNull('delete_time')->paginate($listRows,$total) : $Db->where($options['where'])->paginate($listRows,$total);
        $p = $page->render(); 
        $this->assign('_page', $p? $p: '');

		$Db = $Db->removeOption(true);
        //$Db->setOption('options',$options);

        $limit = ($page->currentPage()-1) * $page->listRows() .','.$page->listRows();
		$listing = $isSoftDelete ? $Db->where($options['where'])->whereNull('delete_time')->field($field)->order($options['order'])->limit($limit)->select() : $Db->where($options['where'])->field($field)->order($options['order'])->limit($limit)->select();

		return $listing;
    }
    /**
     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
     *
     * @param string $model 模型名称,供model函数使用的参数
     * @param array  $data  修改的数据
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     */
    final protected function editRow ( $model ,$data, $where , $msg ){
        $id    = array_unique(Request::param('id/a',[]));
        $id    = is_array($id) ? implode(',',$id) : $id;
        //如存在id字段，则加入该条件
		$table = model($model)->getTable();
		$fields = model($model)->getConnection()->getTableFields($table);
        if(in_array('id',$fields) && !empty($id)){
			$where['id'] =  ['id','in', $id];
        }

        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>Request::isAjax()) , (array)$msg );
		$test = model($model);
        if( model($model)->where($where)->update($data)!==false ) {
            $this->success($msg['success'],$msg['url'],$msg['ajax']);
        }else{
            $this->error($msg['error'],$msg['url'],$msg['ajax']);
        }
    }
    /**
     * 禁用条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的 where()方法的参数
     * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     */
    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
        $data    =  array('status' => 0);
        $this->editRow( $model , $data, $where, $msg);
    }
    /**
     * 恢复条目
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     */
    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
        $data    =  array('status' => 1);
        $this->editRow(   $model , $data, $where, $msg);
    }
    /**
     * 条目假删除
     * @param string $model 模型名称,供D函数使用的参数
     * @param array  $where 查询时的where()方法的参数
     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
     *
     */
    protected function delete ( $model , $where = [] , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！')) {
        $data['delete_time']         =   Request::time();
        $this->editRow(   $model , $data, $where, $msg);
    }
    /**
     * 返回后台节点数据
     * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     *
     */
    final protected function returnNodes($tree = true){
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }
        if((int)$tree){
            $list = db('Menu')->field('id,pid,title,url,tip,hide')->order('sort asc')->select();
            foreach ($list as $key => $value) {
                if( stripos($value['url'],Request::module())!==0 ){
                    $list[$key]['url'] = Request::module().'/'.$value['url'];
                }
            }
            $nodes = list_to_tree($list,$pk='id',$pid='pid',$child='operator',$root=0);
            foreach ($nodes as $key => $value) {
                if(!empty($value['operator'])){
                    $nodes[$key]['child'] = $value['operator'];
                    unset($nodes[$key]['operator']);
                }
            }
        }else{
            $nodes = db('Menu')->field('title,url,tip,pid')->order('sort asc')->select();
            foreach ($nodes as $key => $value) {
                if( stripos($value['url'],Request::module())!==0 ){
                    $nodes[$key]['url'] = Request::module().'/'.$value['url'];
                }
            }
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }
    /**
     * 设置一条或者多条数据的状态
     */
    public function setStatus($Model){
		empty($model) && $model = Request::module();
        $ids    =   Request::param('ids');
        $status =   Request::param('status/d');
        if(empty($ids)){
            $this->error('请选择要操作的数据');
        }

        $map[] = ['id','in',$ids];
        switch ($status){
            case -1 :
                $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));
                break;
            case 0  :
                $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));
                break;
            case 1  :
                $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));
                break;
            default :
                $this->error('参数错误');
                break;
        }
    }
}
