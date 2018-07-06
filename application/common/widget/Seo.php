<?php
// +----------------------------------------------------------------------
// | HeilPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.heilphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jason <1878566968@qq.com>
// +----------------------------------------------------------------------


namespace app\common\widget;
use think\Controller;
use think\Container;

/**
 * SEO设置widget
 * 用于动态调用SEO信息
 */

class Seo extends Controller{
	/**
     * 构造方法
     * @access public
     */
    public function __construct()
    {
        $this->request = Container::get('request');
        $this->app     = Container::get('app');
		config('template.view_base','');
        $this->view    = Container::get('view')->init(
            $this->app['config']->pull('template')
        );

        // 控制器初始化
        $this->initialize();

        // 前置操作方法
        foreach ((array) $this->beforeActionList as $method => $options) {
            is_numeric($method) ?
            $this->beforeAction($options) :
            $this->beforeAction($method, $options);
        }
    }

	/* 获取当前页面SEO信息 */
	public function detail(){
		$module = strtolower(Request()->module());
		$controller = strtolower(Request()->controller());
		$action = strtolower(Request()->action());

		$Seo = Db('Seo');

		//如果是index模块
		if($module == 'index'){
			$Seo->where(function($query) use($module){
				$query->whereNull('module')
					->whereOr('module','=','')
					->whereOr('module','=',$module);
			});
		}
		//如果是其他模块
		else{
			$Seo->where('module','=',$module);
		}

		//如果是index控制器
		if($controller == 'index'){
			$Seo->where(function($query) use($controller){
				$query->whereNull('controller')
					->whereOr('controller','=','')
					->whereOr('controller','=',$controller);
			});
		}
		else{
			$Seo->where('controller','=',$controller);
		}

		//如果是index方法
		if($action == 'index'){
			$Seo->where(function($query) use($action){
				$query->whereNull('action')
					->whereOr('action','=','')
					->whereOr('action','=',$action);
			});
		}
		else{
			$Seo->where('action','=',$action);
		}

		$map = [
			['status','=',1]
		];
		$seo = $Seo->where($map)->order('sort asc,id desc')->find();

		$seo['seo_title'] = isset($seo['seo_title']) ? $seo['seo_title'] : config('WEB_SITE_TITLE');
		$seo['seo_title'] = $this->display($seo['seo_title']);

		$seo['seo_keywords'] = isset($seo['seo_keywords']) ? $seo['seo_keywords'] : config('WEB_SITE_KEYWORD');
		$seo['seo_keywords'] = $this->display($seo['seo_keywords']);

		$seo['seo_description'] = isset($seo['seo_description']) ? $seo['seo_description'] : config('WEB_SITE_DESCRIPTION');
		$seo['seo_description'] = $this->display($seo['seo_description']);

		$this->assign('seo',$seo);
		return $this->fetch('common@seo/detail',[],['view_base'=>env('root_path').'public/theme/']);
	}
	
}
