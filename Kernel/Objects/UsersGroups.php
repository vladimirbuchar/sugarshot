<?php

namespace Objects;
class UsersGroups extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    public function AddUserToGroup($userId,$groupId, $isMain=FALSE)
    {
        /**
         * @var \Model\UsersInGroup
         */
        $model = \Model\UsersInGroup::GetInstance();
        $model->UserId = $userId;
        $model->GroupId = $groupId;
        $model->IsMainGroup = $isMain;
        return $model->SaveObject();
    }
    
    public function DeleteByCondition($condition)
    {
        /**
         * @var \Model\UsersInGroup
         */
        $model = \Model\UsersInGroup::GetInstance();
        $model->DeleteByCondition($condition);
    }
    
    public function GetMainUserGroup($userid)
    {
        /**
         * @var \Model\UsersInGroup
         */
        $model = \Model\UsersInGroup::GetInstance();
        $res = $model->SelectByCondition("UserId = $userid AND IsMainGroup = 1 AND Deleted = 0");
        if (empty($res)) return 0;
        return $res[0]["GroupId"];
    }
    
    public function GetMinorityUserGroup($userId)
    {
        $model = \Model\UsersInGroup::GetInstance();
        return $model->SelectByCondition("UserId =  $userId AND Deleted= 0 AND IsMainGroup = 0");
    }
}
