<?php

namespace app\admin\service;
use think\Model;
use think\Db;
use Request;

class MenuService{
    /**
     * 返回后台节点数据
     * @param boolean $tree    是否返回多维数组结构(生成菜单时用到),为false返回一维数组(生成权限节点时用到)
     * @retrun array
     *
     * 注意,返回的主菜单节点数组中有'controller'元素,以供区分子节点和主节点
     * @author 朱亚杰 <xcoolcc@gmail.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function returnNodes($tree = true){
        static $tree_nodes = array();
        if ( $tree && !empty($tree_nodes[(int)$tree]) ) {
            return $tree_nodes[$tree];
        }
        if((int)$tree){
            $list = Db::name('Menu')->field('id,pid,title,url,tip,hide')->order('sort asc,id asc')->select();
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
            $nodes = Db::name('Menu')->field('title,url,tip,pid')->order('sort asc,id asc')->select();
            foreach ($nodes as $key => $value) {
                if( stripos($value['url'],Request::module())!==0 ){
                    $nodes[$key]['url'] = Request::module().'/'.$value['url'];
                }
            }
        }
        $tree_nodes[(int)$tree]   = $nodes;
        return $nodes;
    }
}
