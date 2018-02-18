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

        if(empty($where)){
            $where  =   array('status'=>array('>=',0));
        }
        if( !empty($where)){
            $options['where']   =   $where;
        }
        $options      =   array_merge( (array)$OPT->getValue($Db), $options );

		$total        =   $Db->where($options['where'])->count();
        $this->assign('_total',$total);

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = Config::get('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }

		$Db = $Db->newQuery()->table($table);
		$page = $Db->where($options['where'])->paginate($listRows,$total);
        $p = $page->render(); 
        $this->assign('_page', $p? $p: '');

		$Db = $Db->newQuery()->table($table);
        $Db->setOption('options',$options);
        $limit = ($page->currentPage()-1) * $page->listRows() + 1 .','.$page->listRows();
		$listing = $Db->where($options['where'])->field($field)->limit($limit)->select();

		return $listing;
    }
}
