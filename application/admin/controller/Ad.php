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
 * 广告控制器
 * @author Jason<1878566968@qq.com>
 */

class Ad extends Admin {

    /**
     * 列表
	 * @author Jason<1878566968@qq.com>
     */
    public function index(){
		$positionId = input('param.position/d');
		if(empty($positionId)){
			$this->error('信息错误');	
		}
		$position = model('AdPosition')->where('id',input('param.position/d'))->find();
		if(empty($positionId)){
			$this->error('信息错误');	
		}
		$this->assign('position',$position);
        /* 获取列表 */
		$map  = [
				['position','=', $position['id']]
				,['status','>', -1]
			];
        $list = model('Ad')->where($map)->order('sort asc,id asc')->select();

        $this->assign('list', $list);
        $this->assign('meta_title','广告');
		return view();
    }

    /**
     * 添加
	 * @author Jason<1878566968@qq.com>
     */
	public function add(){
		if(Request()->isPost()){
			$Ad = model('Ad');
			$res = $Ad->edit();
			if($res !== false){
				$this->success('新增成功', Url('index',['position'=>input('param.position')]));
			} else {
				$this->error($Ad->getError() ?: '新增失败');
			}
		} else {
			$position = model('AdPosition')->where('id',input('param.position/d'))->find();
			if(empty($position)){
				$this->error('广告位异常');
			}
			$this->assign('position',$position);
			$this->assign('info',null);
			$this->assign('meta_title','新增广告');
			switch($position['type']){
				case 0:
				case 1:
					return view('edit_pic');
					break;	
				case 2:
					return view('edit_text');
					break;
				case 3:
					return view('edit_code');
					break;
			}
		}
	}

    /**
     * 编辑
	 * @author Jason<1878566968@qq.com>
     */
    public function edit($id = 0){
        if(Request()->isPost()){
			$Ad = model('Ad');
			$res = $Ad->edit();
			if($res !== false){
				$this->success('编辑成功', Url('index',['position'=>input('param.position')]));
			} else {
				$this->error($Ad->getError() ?: '编辑失败');
			}
        } else {


            $info = array();
            /* 获取数据 */
            $info = model('Ad')->find($id);

            if(false === $info){
                $this->error('获取广告信息错误');
            }
			$position = model('AdPosition')->where('id',$info['position'])->find();
			if(empty($position)){
				$this->error('广告位异常');
			}
			$this->assign('position',$position);

            $this->assign('info', $info);
            $this->assign('meta_title','编辑广告');
			switch($position['type']){
				case 0:
				case 1:
					return view('edit_pic');
					break;	
				case 2:
					return view('edit_text');
					break;
				case 3:
					return view('edit_code');
					break;
			}
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
                $this->forbid('Ad', $map );
                break;
            case 'resume':
                $this->resume('Ad', $map );
                break;
            case 'delete':
                $this->delete('Ad', $map );
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
            $list = db('Ad')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->assign('meta_title','广告位排序');
			return view();
        }elseif (Request()->isPost()){
            $ids = Input('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = db('Ad')->where('id',$value)->setField('sort', $key+1);
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
