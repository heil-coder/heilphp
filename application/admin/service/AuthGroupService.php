<?php

namespace app\admin\service;
use think\Model;
use think\Db;
use app\admin\model\AuthGroup;

class AuthGroupService{
    public function getUserAuthRulesByUid(){
        $userRules = Db::name("AuthGroup")->alias("authGroup")
            ->join("AuthGroupAccess authGroupAccess", "authGroup.id = authGroupAccess.group_id")
            ->where([
                ["authGroupAccess.uid", "=", UID],
                ["authGroup.status", ">", 0],
                ["module", "=", "admin"],
                ['type','=',AuthGroup::TYPE_ADMIN] 			
            ])
            ->column("rules");
        $userRules = explode(",",
            implode(",", $userRules)
        );
        return array_unique($userRules);
    }
}
