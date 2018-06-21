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

namespace app\admin\controller;
use think\Controller;

/**
 * 后台频道控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 * @modify Jason<1878566968@qq.com>
 */

class Channel extends Admin {

    /**
     * 频道列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 * @modify Jason<1878566968@qq.com>
     */
    public function index(){
        $pid = input('param.pid/d');
        /* 获取频道列表 */
		$map  = [
				['status','>', -1]
				,['pid','=',$pid ?: 0]
			];
        $list = db('Channel')->where($map)->order('sort asc,id asc')->select();

        $this->assign('list', $list);
        $this->assign('pid', $pid);
        $this->assign('meta_title','导航管理');
		return view();
    }

    /**
     * 添加频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 * @modify Jason<1878566968@qq.com>
     */
    public function add(){
        if(Request()->isPost()){
			$Channel = model('Channel');
			$res = $Channel->edit();
			if($res !== false){
				$this->success('新增成功', Url('index'));
			} else {
				$this->error($Channel->getError() ?: '新增失败');
			}
        } else {
            $pid = input('param.pid/d');
            //获取父导航
            if(!empty($pid)){
                $parent = db('Channel')->where('id',$pid)->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info',null);
            $this->assign('meta_title','新增导航');
			return view('edit');
        }
    }

    /**
     * 编辑频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 * @modify Jason<1878566968@qq.com>
     */
    public function edit($id = 0){
        if(Request()->isPost()){
			$Channel = model('Channel');
			$res = $Channel->edit();
			if($res !== false){
				$this->success('新增成功', Url('index'));
			} else {
				$this->error($Channel->getError() ?: '新增失败');
			}
        } else {
            $info = array();
            /* 获取数据 */
            $info = db('Channel')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $pid = input('parent.pid/');
            //获取父导航
            if(!empty($pid)){
            	$parent = db('Channel')->where('id',$pid)->field('title')->find();
            	$this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->assign('meta_title','编辑导航');
			return view();
        }
    }

    /**
     * 删除频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
	 * @modify Jason<1878566968@qq.com>
     */
    public function del(){
        $id = array_unique(Input('id/a',[]));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = [['id','in',$id]];
        if(db('Channel')->where($map)->delete()){
            //记录行为
            action_log('update_channel', 'channel', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 导航排序
     * @author huajie <banhuajie@163.com>
	 * @modify Jason<1878566968@qq.com>
     */
    public function sort(){
        if(Request()->isGet()){
            $ids = input('param.ids');
            $pid = input('param.pid');

            //获取排序的数据
            $map = [['status','>',-1]];
            if(!empty($ids)){
                $map['id'] = ['id','in',$ids];
            }else{
                if($pid !== ''){
                    $map['pid'] = ['pid','=',$pid];
                }
            }
            $list = db('Channel')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->assign('meta_title','导航排序');
			return view();
        }elseif (Request()->isPost()){
            $ids = Input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = db('Channel')->where('id',$value)->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
}
