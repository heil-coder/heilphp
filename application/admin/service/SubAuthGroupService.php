<?php

namespace app\admin\service;
use think\Model;
use think\Db;
use app\admin\model\AuthRule;

class SubAuthGroupService{
    private $error = null;

    public function getError(){
        return $this->error;
    }
    /**
     * @return bool true 操作成功 false 操作失败
     */
    public function addToGroup(){
        $uid = input("param.uid/d");
        $gid = input("group_id/a");
        if( empty($uid) ){
            $this->error = "参数有误";
        }

        $subAuthGroupDb = Db::name("SubAuthGroup");

        if (is_administrator($uid)){
            $this->error = "该用户为超级管理员";
            return false;
        }

        $subUser = Db::name("SubMember")->where([
            ["uid", "=", $uid]
        ])->find();

        if (!$subUser){
            $this->error = "用户不存在";
            return false;
        }

        if(!empty($gid) && !$this->checkGroupId($gid)){
            return false;
        }

        $subAuthGroupModel = model("SubAuthGroup");
        if ($subAuthGroupModel->addToGroup($uid, $gid)){
            return true;
        }
        else{
            $this->error = $subAuthGroupModel->getError();
            dump("2");
            return false;
        }
    }
    /**
     * @param id组成的数组
     */
    private function checkGroupId($groupIds){
        return $this->checkId("SubAuthGroup", $groupIds, "以下用户组ID不存在:");
    }
    
    private function checkId($modelName, $mid, $msg = "以下ID不存在"){
        if (is_array($mid)){
            $count = count($mid);
        }
        else{
            $mid = explode(",", $mid);
            $count = count($mid);
        }

        $queryIds = Db::name($modelName)
            ->where([
                ["id", "in", $mid]
            ])->column("id");

        if (count($queryIds) === $count){
            return true;
        }
        else{
            $diff = implode(",", array_diff($mid, $queryIds));
            $this->error = $msg . $diff;
            return false;
        }
    }
}
