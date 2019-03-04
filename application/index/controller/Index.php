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
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $category = model('Category')->getTree();
        $lists    = model('Document')->lists(null);

        $this->assign('category',$category);//栏目
        $this->assign('lists',$lists);//列表
        $this->assign('page',model('Document')->page);//分页
		return view();
    }
}
