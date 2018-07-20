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
        $list = model('AdPosition')->where($map)->order('sort asc,id asc')->select();

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
     * 状态修改
     * @author Jason <1878566968@qq.com>
     */
    public function changeStatus($method=null){
        $ids = array_unique(input('param.ids/a',[]));
        $ids = is_array($ids) ? implode(',',$ids) : $ids;
        if ( empty($ids) ) {
            $this->error('请选择要操作的数据!');
        }
		$map = [];
        $map[] =   ['id','in',$ids];
        switch ( strtolower($method) ){
            case 'forbid':
                $this->forbid('AdPosition', $map );
                break;
            case 'resume':
                $this->resume('AdPosition', $map );
                break;
            case 'delete':
                $this->delete('AdPosition', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }
    /**
     * 排序
	 * @author Jason<1878566968@qq.com>
     */
    public function sort(){
        if(Request()->isGet()){
            $ids = input('param.ids');

            //获取排序的数据
            $map = [['status','>',-1]];
            if(!empty($ids)){
                $map['id'] = ['id','in',$ids];
            }
            $list = db('AdPosition')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->assign('meta_title','广告位排序');
			return view();
        }elseif (Request()->isPost()){
            $ids = Input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = db('AdPosition')->where('id',$value)->setField('sort', $key+1);
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
