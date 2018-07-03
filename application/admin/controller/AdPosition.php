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

/**
 * 广告位控制器
 * @author Jason<1878566968@qq.com>
 */

class AdPosition extends Admin {

    /**
     * 列表
	 * @author Jason<1878566968@qq.com>
     */
    public function index(){
        /* 获取列表 */
		$map  = [
				['status','>', -1]
			];
        $list = model('AdPosition')->where($map)->order('id desc')->select();

        $this->assign('list', $list);
        $this->assign('meta_title','广告位');
		return view();
    }

    /**
     * 添加
	 * @author Jason<1878566968@qq.com>
     */
	public function add(){
		if(Request()->isPost()){
			$Seo = model('AdPosition');
			$res = $Seo->edit();
			if($res !== false){
				$this->success('新增成功', Url('index'));
			} else {
				$this->error($Seo->getError() ?: '新增失败');
			}
		} else {
			$this->assign('info',null);
			$this->assign('meta_title','新增广告位');
			return view('edit');
		}
	}

    /**
     * 编辑
	 * @author Jason<1878566968@qq.com>
     */
    public function edit($id = 0){
        if(Request()->isPost()){
			$Seo = model('AdPosition');
			$res = $Seo->edit();
			if($res !== false){
				$this->success('新增成功', Url('index'));
			} else {
				$this->error($Seo->getError() ?: '新增失败');
			}
        } else {
            $info = array();
            /* 获取数据 */
            $info = model('AdPosition')->find($id);

            if(false === $info){
                $this->error('获取广告位信息错误');
            }

            $this->assign('info', $info);
            $this->assign('meta_title','编辑广告位');
			return view();
		}
    }

    /**
     * 删除
	 * @author Jason<1878566968@qq.com>
     */
    public function del(){
        $id = array_unique(Input('id/a',[]));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = [['id','in',$id]];
        if(model('AdPosition')->where($map)->find()->delete()){
            //记录行为
            action_log('update_ap_position', 'ad_position', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
}
