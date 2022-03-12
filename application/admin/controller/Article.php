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
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use app\admin\controller\Admin;
use app\admin\model\AuthGroup;

/**
 * 后台内容控制器
 */
class Article extends Admin {

    /* 保存允许访问的公共方法 */
    static protected $allow = array( 'draftbox','mydocument');

    private $cate_id        =   null; //文档分类id

    /**
     * 检测需要动态判断的文档类目有关的权限
     *
     * @return boolean|null
     *      返回true则表示当前访问有权限
     *      返回false则表示当前访问无权限
     *      返回null，则会进入checkRule根据节点授权判断权限
     *
     */
    protected function checkDynamic(){
        $cates = AuthGroup::getAuthCategories(UID);
        switch(strtolower(Request()->action())){
            case 'index':   //文档列表
            case 'add':   // 新增
				$cate_id =  input('param.cate_id/d');
                break;
            case 'edit':    //编辑
            case 'update':  //更新
                $doc_id  =  input('param.id/d');
                $cate_id =  db('Document')->where('id',$doc_id)->value('category_id');
                break;
            case 'setstatus': //更改状态
            case 'permit':    //回收站
                $doc_id  =  input('param.ids/a',[]);
                $cate_id =  db('Document')->where('id','in',$doc_id)->column('category_id');
                $cate_id =  array_unique($cate_id);
                break;
        }
        if(empty($cate_id)){
            return null;//不明
        }elseif( !is_array($cate_id) && in_array($cate_id,$cates) ) {
            return true;//有权限
        }elseif( is_array($cate_id) && $cate_id==array_intersect($cate_id,$cates) ){
            return true;//有权限
        }else{
            return false;//无权限
        }
    }

    /**
     * 显示左边菜单，进行权限控制
     */
    protected function getMenu(){
        //获取动态分类
        $cate_auth  =   AuthGroup::getAuthCategories(UID); //获取当前用户所有的内容权限节点
        $cate_auth  =   $cate_auth == null ? array() : $cate_auth;
        $cate       =   db('Category')->where('status',1)->field('id,title,pid,allow_publish')->order('pid,sort')->select();

        //没有权限的分类则不显示
        if(!is_administrator()){
            foreach ($cate as $key=>$value){
                if(!in_array($value['id'], $cate_auth)){
                    unset($cate[$key]);
                }
            }
        }

        $cate           =   list_to_tree($cate);    //生成分类树

        //获取分类id
        $cate_id        =   input('param.cate_id/d');
        $this->cate_id  =   $cate_id;

        //是否展开分类
        $hide_cate = false;
        if(Request()->action()!= 'recycle' && Request()->action() != 'draftbox' && Request()->action() != 'mydocument'){
            $hide_cate  =   true;
        }

        //生成每个分类的url
        foreach ($cate as $key=>&$value){
            $value['url']   =   'Article/index?cate_id='.$value['id'];
            if($cate_id == $value['id'] && $hide_cate){
                $value['current'] = true;
            }else{
                $value['current'] = false;
            }
            if(!empty($value['_child'])){
                $is_child = false;
                foreach ($value['_child'] as $ka=>&$va){
                    $va['url']      =   'Article/index?cate_id='.$va['id'];
                    if(!empty($va['_child'])){
                        foreach ($va['_child'] as $k=>&$v){
                            $v['url']   =   'Article/index?cate_id='.$v['id'];
                            $v['pid']   =   $va['id'];
                            $is_child = $v['id'] == $cate_id ? true : false;
                        }
                    }
                    //展开子分类的父分类
                    if($va['id'] == $cate_id || $is_child){
                        $is_child = false;
                        if($hide_cate){
                            $value['current']   =   true;
                            $va['current']      =   true;
                        }else{
                            $value['current']   =   false;
                            $va['current']      =   false;
                        }
                    }else{
                        $va['current']      =   false;
                    }
                }
            }
        }
        $this->assign('nodes',      $cate);
        $this->assign('cate_id',    $this->cate_id);

        //获取面包屑信息
        $nav = get_parent_category($cate_id);
        $this->assign('rightNav',   $nav);

        //获取回收站权限
        $this->assign('show_recycle', is_administrator() || $this->checkRule('Admin/article/recycle'));
        //获取草稿箱权限
        $this->assign('show_draftbox', Config('OPEN_DRAFTBOX'));
        //获取审核列表权限
        $this->assign('show_examine', is_administrator() || $this->checkRule('Admin/article/examine'));
    }

    /**
     * 分类文档列表页
     * @param integer $cate_id 分类id
     * @param integer $model_id 模型id
     * @param integer $position 推荐标志
     * @param integer $group_id 分组id
     */
    public function index($cate_id = null, $model_id = null, $position = null,$group_id=null){
        //获取左边菜单
        $this->getMenu();

        if($cate_id===null){
            $cate_id = $this->cate_id;
        }
        if(!empty($cate_id)){
            $pid = input('param.pid/d',null);
            // 获取列表绑定的模型
            if (empty($pid)) {
                $models     =   get_category($cate_id, 'model');
				// 获取分组定义
				$groups		=	get_category($cate_id, 'groups');
				if($groups){
					$groups	=	parse_field_attr($groups);
					$this->assign('groups', $groups);
				}
            }else{ // 子文档列表
                $models     =   get_category($cate_id, 'model_sub');
            }
            if(is_null($model_id) && !is_numeric($models)){
                // 绑定多个模型 取基础模型的列表定义
                $model = db('Model')->getByName('document');
            }else{
                $model_id   =   $model_id ? : $models;
                //获取模型信息
                $model = db('Model')->getById($model_id);
                if (empty($model['list_grid'])) {
                    $model['list_grid'] = db('Model')->getFieldByName('document','list_grid');
                }                
            }
            $this->assign('model', explode(',', $models));
        }else{
            // 获取基础模型信息
            $model = db('Model')->getByName('document');
            $model_id   =   null;
            $cate_id    =   0;
            $this->assign('model', null);
        }

        //解析列表规则
        $fields =	array();
        $grids  =	preg_split('/[;\r\n]+/s', trim($model['list_grid']));
        foreach ($grids as &$value) {
            // 字段:标题:链接
            $val      = explode(':', $value);
            // 支持多个字段显示
            $field   = explode(',', $val[0]);
            $value    = array('field' => $field, 'title' => $val[1]);
            if(isset($val[2])){
                // 链接信息
                $value['href']  =   $val[2];
                // 搜索链接信息中的字段信息
                preg_replace_callback('/\[([a-z_]+)\]/', function($match) use(&$fields){$fields[]=$match[1];}, $value['href']);
            }
            if(strpos($val[1],'|')){
                // 显示格式定义
                list($value['title'],$value['format'])    =   explode('|',$val[1]);
            }
            foreach($field as $val){
                $array  =   explode('|',$val);
                $fields[] = $array[0];
            }
        }
        // 文档模型列表始终要获取的数据字段 用于其他用途
        $fields[] = 'category_id';
        $fields[] = 'model_id';
        $fields[] = 'pid';
        // 过滤重复字段信息
        $fields =   array_unique($fields);
        // 列表查询
        $list   =   $this->getDocumentList($cate_id,$model_id,$position,$fields,$group_id);
        // 列表显示处理
        $list   =   $this->parseDocumentList($list,$model_id);
        
        $this->assign('model_id',$model_id);
		$this->assign('group_id',$group_id);
        $this->assign('position',$position);
        $this->assign('list',   $list);
        $this->assign('list_grids', $grids);
        $this->assign('model_list', $model);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
		return view();
    }

    /**
     * 默认文档列表方法
     * @param integer $cate_id 分类id
     * @param integer $model_id 模型id
     * @param integer $position 推荐标志
     * @param mixed $field 字段列表
     * @param integer $group_id 分组id
     */
    protected function getDocumentList($cate_id=0,$model_id=null,$position=null,$field=true,$group_id=null){
        /* 查询条件初始化 */
        $map = array();
        if(Request()->has('title')){
            $map[]  = ['title','like','%'.input('param.title/s').'%'];
        }
        if(Request()->has('status')){
			$status = input('param.status/d');
            $map[] = ['status','=',$status];
        }else{
            $status = null;
            $map[] = ['status','in','0,1,2'];
        }
        if (Request()->has('time-start') ) {
            $map[] = ['update_time','>=',strtotime(input('time-start'))];
        }
        if (Request()->has('time-end') ) {
            $map[] = ['update_time','<=',24*60*60 + strtotime(input('time-end'))];
        }
        if (Request()->has('nickname') ) {
            $map[] = ['uid','in',db('Member')->where('nickname','like','%'.input('nickname').'%')->column('id')];
        }

        // 构建列表数据
        $Document = db('Document');

        $map[]         =   ['pid','=',input('param.pid/d',0)];
        //非子文档列表 && 收到分类id条件
		if(!Request()->has('pid') && $cate_id){
            $map[] =   ['category_id','=',$cate_id];
		}
        $Document->alias('DOCUMENT');
        if(!is_null($model_id)){
            $map[]    =   ['model_id','=',$model_id];
            if(is_array($field) && array_diff($Document->getConnection()->getTableFields($Document->getTable()),$field)){
                $modelName  =   db('Model')->getFieldById($model_id,'name');
                $Document->join('__DOCUMENT_'.strtoupper($modelName).'__ '.$modelName,'DOCUMENT.id='.$modelName.'.id');
                $key = array_search('id',$field);
                if(false  !== $key){
                    unset($field[$key]);
                    $field[] = 'DOCUMENT.id';
                }
            }            
        }
        if(!is_null($position)){
			$Document = $Document->where('position','exp'," & {$position} > 0");
        }
		if(!is_null($group_id)){
			$map[]	=	['group_id','=',$group_id];
		}

        $list = $this->getListing($Document,$map,'level DESC,id DESC',$field);

        if(Request()->has('pid')){
            // 获取上级文档
            $article    =   $Document->field('id,title,type')->find(input('param.pid/d'));
            $this->assign('article',$article);
        }
        //检查该分类是否允许发布内容
        $allow_publish  =   get_category($cate_id, 'allow_publish');

        $this->assign('status', $status);
        $this->assign('allow',  $allow_publish);
        $this->assign('pid',    input('param.pid/d',null));

        $this->assign('meta_title','文档列表');
        return $list;
    }

    /**
     * 设置一条或者多条数据的状态
     * @author huajie <banhuajie@163.com>
     */
    public function setStatus($model='Document'){
        return parent::setStatus('Document');
    }

    /**
     * 文档新增页面初始化
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function add(){
        //获取左边菜单
        $this->getMenu();

        $cate_id    =   input('param.cate_id/d',null);
        $model_id   =   input('param.model_id/d',null);
		$group_id	=	input('param.group_id/d',null);

        empty($cate_id) && $this->error('参数不能为空！');
        empty($model_id) && $this->error('该分类未绑定模型！');

        //检查该分类是否允许发布
        $allow_publish = check_category($cate_id);
        !$allow_publish && $this->error('该分类不允许发布内容！');

        // 获取当前的模型信息
        $model    =   get_document_model($model_id);

        //处理结果
        $info['pid']            =   input('param.pid',0);
        $info['model_id']       =   $model_id;
        $info['category_id']    =   $cate_id;
		$info['group_id']		=	$group_id;

        if($info['pid']){
            // 获取上级文档
            $article            =   db('Document')->field('id,title,type')->find($info['pid']);
            $this->assign('article',$article);
        }

        //获取表单字段排序
        $fields = get_model_attribute($model['id']);
        $this->assign('info',       $info);
        $this->assign('fields',     $fields);
        $this->assign('type_list',  get_type_bycate($cate_id));
        $this->assign('model',      $model);
        $this->assign('meta_title','新增'.$model['title']);
		return view();
    }

    /**
     * 文档编辑页面初始化
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function edit(){
        //获取左边菜单
        $this->getMenu();

        $id     =   input('param.id','');
        if(empty($id)){
            $this->error('参数不能为空！');
        }

        // 获取详细数据 
        $Document = model('Document');
        $data = $Document->detail($id);
        if(!$data){
            $this->error($Document->error);
        }

        if($data['pid']){
            // 获取上级文档
            $article        =   $Document->field('id,title,type')->find($data['pid']);
            $this->assign('article',$article);
        }
        // 获取当前的模型信息
        $model    =   get_document_model($data['model_id']);

        $this->assign('data', $data);
        $this->assign('model_id', $data['model_id']);
        $this->assign('model',      $model);

        //获取表单字段排序
        $fields = get_model_attribute($model['id']);
        $this->assign('fields',     $fields);


        //获取当前分类的文档类型
        $this->assign('type_list', get_type_bycate($data['category_id']));

        $this->assign('meta_title','编辑文档');
		return view();
    }

    /**
     * 更新一条数据
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function update(){
        $document   =   model('Document');
        $res = $document->edit();
        if(!$res){
            $this->error($document->error);
        }else{
            $this->success($res['id']?'更新成功':'新增成功', Cookie('__forward__'));
        }
    }

    /**
     * 待审核列表
     */
    public function examine(){
        //获取左边菜单
        $this->getMenu();

        $map[]  =   ['status','=',2];
        if ( !is_administrator() ) {
            $cate_auth  =   AuthGroup::getAuthCategories(UID);
            if($cate_auth){
                $map[]    =   ['category_id','IN',$cate_auth];
            }else{
                $map[]    =   ['category_id','=',-1];
            }
        }
        $list = $this->getListing(db('Document'),$map,'update_time desc');
        //处理列表数据
        if(is_array($list)){
            foreach ($list as $k=>&$v){
                $v['username']      =   get_nickname($v['uid']);
            }
        }

        $this->assign('list', $list);
        $this->assign('meta_title','待审核');
		return view();
    }

    /**
     * 回收站列表
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function recycle(){
        //获取左边菜单
        $this->getMenu();

        $map =   [];
        if ( !is_administrator() ) {
            $cate_auth  =   AuthGroup::getAuthCategories(UID);
            if($cate_auth){
                $map[]    =   ['category_id','IN',$cate_auth];
            }else{
                $map[]    =   ['category_id','=',-1];
            }
        }
        $list = $this->getListing(db('Document')->where('delete_time','not null'),$map,'update_time desc');

        //处理列表数据
        if(is_array($list)){
            foreach ($list as $k=>&$v){
                $v['username']      =   get_nickname($v['uid']);
            }
        }

        $this->assign('list', $list);
        $this->assign('meta_title','回收站');
		return view();
    }

    /**
     * 写文章时自动保存至草稿箱
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function autoSave(){
        $res = model('Document')->autoSave();
        if($res !== false){
            $return['data']     =   $res;
            $return['msg']     =   '保存草稿成功';
            $return['code']   =   1;
			return json($return);
		}else{
            $this->error('保存草稿失败：'.model('Document')->getError());
        }
    }

    /**
     * 草稿箱
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function draftBox(){
        //获取左边菜单
        $this->getMenu();

        $Document   =   model('Document');
		$map        =   [
			['status','=',3]
			,['uid','=',UID]
		];
        $list       =   $this->getListing($Document,$map);
        //获取状态文字
        //int_to_string($list);

        $this->assign('list', $list);
        $this->assign('meta_title','草稿箱');
		return view();
    }

    /**
     * 我的文档
     */
    public function mydocument($status = null, $title = null){
        //获取左边菜单
        $this->getMenu();

        $Document   =   model('Document');
        /* 查询条件初始化 */
        $map[] = ['uid','=',UID];
        if(isset($title)){
            $map[]   =   ['title','like', '%'.$title.'%'];
        }
        if(isset($status)){
            $map[]  =   ['status','=',$status];
        }else{
            $map[]  =   ['status','in', '0,1,2'];
        }
        if ( isset($_GET['time-start']) ) {
            $map[] = ['update_time','>=',strtotime(input('time-start'))];
        }
        if ( isset($_GET['time-end']) ) {
            $map[] = ['update_time','<=',24*60*60 + strtotime(input('time-end'))];
        }
        //只查询pid为0的文章
        $map[] = ['pid','=',0];
        $list = $this->getListing($Document,$map,'update_time desc');
        $list = $this->parseDocumentList($list,1);

        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->assign('status', $status);
        $this->assign('list', $list);
        $this->assign('meta_title','我的文档');
		return view();
    }

    /**
     * 还原被删除的数据
     */
    public function permit(){
        /*参数过滤*/
        $ids = input('param.ids/a',[]);
        if(empty($ids)){
            $this->error('请选择要操作的数据');
        }

        /*拼接参数并修改状态*/
        $Model  =   'Document';
        $map    =   array();
        $map[] = ['id','in',$ids];
        $this->restore($Model,$map);
    }

    /**
     * 清空回收站
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function clear(){
        $res = model('Document')->remove();
        if($res !== false){
            $this->success('清空回收站成功！');
        }else{
            $this->error('清空回收站失败！');
        }
    }

    /**
     * 移动文档
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function move() {
        if(empty($_POST['ids'])) {
            $this->error('请选择要移动的文档！');
        }
        session('moveArticle', $_POST['ids']);
        session('copyArticle', null);
        $this->success('请选择要移动到的分类！');
    }

    /**
     * 拷贝文档
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function copy() {
        if(empty($_POST['ids'])) {
            $this->error('请选择要复制的文档！');
        }
        session('copyArticle', $_POST['ids']);
        session('moveArticle', null);
        $this->success('请选择要复制到的分类！');
    }

    /**
     * 粘贴文档
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function paste() {
        $moveList = session('moveArticle');
        $copyList = session('copyArticle');
        if(empty($moveList) && empty($copyList)) {
            $this->error('没有选择文档！');
        }
        if(!isset($_POST['cate_id'])) {
            $this->error('请选择要粘贴到的分类！');
        }
        $cate_id = Input('post.cate_id');   //当前分类
        $pid = Input('post.pid', 0);        //当前父类数据id

        //检查所选择的数据是否符合粘贴要求
        $check = $this->checkPaste(empty($moveList) ? $copyList : $moveList, $cate_id, $pid);
        if(!$check['status']){
            $this->error($check['info']);
        }

        if(!empty($moveList)) {// 移动    TODO:检查name重复
            foreach ($moveList as $key=>$value){
                $Model              =   db('Document');
                $map['id']          =   $value;
                $data['category_id']=   $cate_id;
                $data['pid']        =   $pid;
                //获取root
                if($pid == 0){
                    $data['root'] = 0;
                }else{
                    $p_root = $Model->getFieldById($pid, 'root');
                    $data['root'] = $p_root == 0 ? $pid : $p_root;
                }
                $res = $Model->where($map)->update($data);
            }
            session('moveArticle', null);
            if(false !== $res){
                $this->success('文档移动成功！');
            }else{
                $this->error('文档移动失败！');
            }
        }elseif(!empty($copyList)){ // 复制
            foreach ($copyList as $key=>$value){
                $Model  =   db('Document');
                $data   =   $Model->find($value);
                unset($data['id']);
                unset($data['name']);
                $data['category_id']    =   $cate_id;
                $data['pid']            =   $pid;
                $data['create_time']    =   app()->getBeginTime();
                $data['update_time']    =   app()->getBeginTime();
                //获取root
                if($pid == 0){
                    $data['root'] = 0;
                }else{
                    $p_root = $Model->getFieldById($pid, 'root');
                    $data['root'] = $p_root == 0 ? $pid : $p_root;
                }

				$Model->setOption('where',[])->setOption('data',[]);
                $result   =  $Model->insertGetId($data);
                if($result){
                    $logic      =   model(get_document_model($data['model_id'],'name'),'logic',false,'admin');
                    $data       =   $logic->detail($value); //获取指定ID的扩展数据
                    $data['id'] =   $result;
                    $res        =   $logic->isUpdate(false)->save($data);
                }
            }
            session('copyArticle', null);
            if($res){
                $this->success('文档复制成功！');
            }else{
                $this->error('文档复制失败！');
            }
        }
    }

    /**
     * 检查数据是否符合粘贴的要求
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    protected function checkPaste($list, $cate_id, $pid){
        $return = array('status'=>1);
        $Document = model('Document');

        // 检查支持的文档模型
        $modelList =   db('Category')->getFieldById($cate_id,'model');   // 当前分类支持的文档模型
        foreach ($list as $key=>$value){
            //不能将自己粘贴为自己的子内容
            if($value == $pid){
                $return['status'] = 0;
                $return['info'] = '不能将编号为 '.$value.' 的数据粘贴为他的子内容！';
                return $return;
            }
            // 移动文档的所属文档模型
            $modelType  =   $Document->getFieldById($value,'model_id');
            if(!in_array($modelType,explode(',',$modelList))) {
                $return['status'] = 0;
                $return['info'] = '当前分类的文档模型不支持编号为 '.$value.' 的数据！1';
                return $return;
            }
        }

        // 检查支持的文档类型和层级规则
        $typeList =   db('Category')->getFieldById($cate_id,'type'); // 当前分类支持的文档模型
        foreach ($list as $key=>$value){
            // 移动文档的所属文档模型
            $modelType  =   $Document->getFieldById($value,'type');
            if(!in_array($modelType,explode(',',$typeList))) {
                $return['status'] = 0;
                $return['info'] = '当前分类的文档类型不支持编号为 '.$value.' 的数据！2';
                return $return;
            }
            $res = $Document->checkDocumentType($modelType, $pid);
            if(!$res['status']){
                $return['status'] = 0;
                $return['info'] = $res['info'].'。错误数据编号：'.$value;
                return $return;
            }
        }

        return $return;
    }

    /**
     * 文档排序
     * @author huajie <banhuajie@163.com>
	 * @modify Jason <1878566968@qq.com>
     */
    public function sort(){
        if(Request()->isGet()){
            //获取左边菜单
            $this->getMenu();

            $ids        =   Input('get.ids');
            $cate_id    =   Input('get.cate_id/d');
            $pid        =   Input('get.pid/d');

            //获取排序的数据
            $map[] = ['status','>',-1];
            if(!empty($ids)){
                $map[] = ['id','in',$ids];
            }else{
                if(!is_null($cate_id)){
                    $map[] = ['category_id','=',$cate_id];
                }
                if(!is_null($pid)){
                    $map[] = ['pid','=',$pid];
                }
            }
            $list = db('Document')->where($map)->field('id,title')->order('level DESC,id DESC')->select();

            $this->assign('list', $list);
            $this->assign('meta_title','文档排序');
			return view();
        }elseif (Request()->isPost()){
            $ids = Input('post.ids');
            $ids = array_reverse(explode(',', $ids));
            foreach ($ids as $key=>$value){
                $res = db('Document')->where(array('id'=>$value))->setField('level', $key+1);
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
