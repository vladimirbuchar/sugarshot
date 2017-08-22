<?php

namespace Controller;

use Model\UserGroups;
use Model\Users;
use Model\UserGroupsModules;
use Model\UserGroupsWeb;

class UsersItemApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        $this->SetApiFunction("GroupDetail", array("system", "Administrators"));
        $this->SetApiFunction("AddUserGroup", array("system", "Administrators"));
        $this->SetApiFunction("DeleteUserGroup", array("system", "Administrators"));
        $this->SetApiFunction("GroupListLoadTable", array("system", "Administrators"));
        $this->SetApiFunction("UserDetail", array("system", "Administrators"));
        $this->SetApiFunction("AddUser", array("system", "Administrators"));
        $this->SetApiFunction("SaveProfile", array("system", "Administrators"));
        $this->SetApiFunction("RecoveryUserGroup", array("system", "Administrators"));
        $this->SetApiFunction("UserListLoadTable", array("system", "Administrators"));
        $this->SetApiFunction("DeleteUser", array("system", "Administrators"));
        $this->SetApiFunction("ChangePassword", array("system", "Administrators"));
        $this->SetApiFunction("GetDefaultState", array("system", "Administrators"));
        $this->SetApiFunction("SaveOtherUserGroups", array("system", "Administrators"));
        $this->SetApiFunction("SaveUsersGroupWeb", array("system", "Administrators"));
        $this->SetApiFunction("SaveUsersModules", array("system", "Administrators"));
    }

    public function GroupDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userGroup = new \Objects\Users();
        $userGroupDetail = $userGroup->GetUserGroupDetail($id);
        return $userGroupDetail;
    }

    public function AddUserGroup() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userGroup = UserGroups::GetInstance();
        $userGroup->Id = $ajaxParametrs["Id"];
        $userGroup->GroupIdentificator = $ajaxParametrs["GroupIdentificator"];
        $userGroup->GroupName = $ajaxParametrs["GroupName"];
        $userGroup->IsSystemGroup = $ajaxParametrs["IsSystemGroup"];
        $userGroup->UserDefaultState = $ajaxParametrs["UserDefaultState"];
        return $userGroup->SaveObject();
    }

    public function DeleteUserGroup() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userGroup = UserGroups::GetInstance();
        $pernametly = $ajaxParametrs["DeletePernamently"] == "true";
        $userGroup->DeleteObject($ajaxParametrs["Id"], $pernametly);
    }

    public function GroupListLoadTable() {
        self::$SessionManager->UnsetKey("where");
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $sort = null;
        $where = "";
        $saveToSession = false;
        if (!empty($ajaxParametrs["SortColumn"]) && !empty($ajaxParametrs["SortType"]))
            $sort = new SortDatabase($ajaxParametrs["SortType"], $ajaxParametrs["SortColumn"]);
        $model = UserGroups::GetInstance();
        if (!empty($ajaxParametrs["SaveFiltrSortToSession"])) {
            $saveToSession = $ajaxParametrs["SaveFiltrSortToSession"];
        }
        if (!empty($ajaxParametrs["Where"])) {
            if ($saveToSession) {
                $modelName = $ajaxParametrs["ModelName"];
                self::$SessionManager->SetSessionValue("where", $ajaxParametrs["Where"], $modelName);
            }
            $where = $model->PrepareWhere($ajaxParametrs["Where"]);
        }

        $showDeleteItem = false;
        if (!empty($ajaxParametrs["ShowItem"])) {
            if ($ajaxParametrs["ShowItem"] == "DeleteItem")
                $showDeleteItem = true;
            else
                $showDeleteItem = false;
        }
        $outData = $model->Select(array(), $showDeleteItem, true, true, $sort, $where, $saveToSession);
        $outData = $this->ReplaceHtmlWord($outData);
        return $outData;
    }

    public function UserDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userDetail = self::$User->GetUserDetail($ajaxParametrs["Id"]);
        return $userDetail;
    }

    public function AddUser() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $user = new \Objects\Users();
        return $user->CreateUser($ajaxParametrs["Id"], $ajaxParametrs["FirstName"], $ajaxParametrs["LastName"], $ajaxParametrs["UserEmail"], $ajaxParametrs["UserName"], $ajaxParametrs["UserPassword"], $ajaxParametrs["BlockDiscusion"], $ajaxParametrs["MainUserGroup"], array(), $ajaxParametrs["IsActive"], $ajaxParametrs["DefaultLang"], true);
    }

    public function SaveProfile() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        $this->SaveUserDomain($ajaxParametrs);
    }

    public function RecoveryUserGroup() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $ug = UserGroups::GetInstance();
        $ug->RecoveryObject($ajaxParametrs["Id"]);
    }

    public function UserListLoadTable() {
        self::$SessionManager->UnsetKey("where");
        $ajaxParametrs = $this->PrepareAjaxParametrs();

        if (empty($ajaxParametrs))
            return;
        $sort = null;
        $where = "";
        $saveToSession = false;
        if (!empty($ajaxParametrs["SortColumn"]) && !empty($ajaxParametrs["SortType"]))
            $sort = new SortDatabase($ajaxParametrs["SortType"], $ajaxParametrs["SortColumn"]);
        $model = Users::GetInstance();
        if (!empty($ajaxParametrs["SaveFiltrSortToSession"])) {
            $saveToSession = $ajaxParametrs["SaveFiltrSortToSession"];
        }
        if (!empty($ajaxParametrs["Where"])) {
            if ($saveToSession) {
                $modelName = $ajaxParametrs["ModelName"];
                self::$SessionManager->SetSessionValue("where", $ajaxParametrs["Where"], $modelName);
            }
            $where = $model->PrepareWhere($ajaxParametrs["Where"]);
        }

        $showDeleteItem = false;
        if (!empty($ajaxParametrs["ShowItem"])) {
            if ($ajaxParametrs["ShowItem"] == "DeleteItem")
                $showDeleteItem = true;
            else
                $showDeleteItem = false;
        }
        return $model->Select(array(), $showDeleteItem, true, true, $sort, $where, $saveToSession);
    }

    public function DeleteUser() {

        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userGroup = Users::GetInstance();
        $pernametly = $ajaxParametrs["DeletePernamently"] == "true";
        $userGroup->DeleteObject($ajaxParametrs["Id"], $pernametly);
    }

    public function ChangePassword() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $user = new \Objects\Users();
        return $user->ChangePassword($ajaxParametrs["password1"], $ajaxParametrs["password2"], $ajaxParametrs["UserId"]);
    }

    public function GetDefaultState() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $user = UserGroups::GetInstance();
        $user->GetObjectById(self::$UserGroupId,true,array("UserDefaultState"));
        $state = $user->UserDefaultState;
        if (empty($state))
            return "";
        $ar = explode("#", $state);
        return "/xadm/" . $ar[0] . "/" . $ar[1] . "/" . $ajaxParametrs["SelectWebId"] . "/" . $ajaxParametrs["SelectLangId"] . "/";
    }

    public function SaveOtherUserGroups() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userGr = new \Objects\UsersGroups();
        $userId = $ajaxParametrs["UserId"];
        unset($ajaxParametrs["UserId"]);
        $userGr->DeleteByCondition("UserId = $userId AND IsMainGroup = 0");
        foreach ($ajaxParametrs as $k => $v) {
            if (!empty($v)) {
                $groupId = StringUtils::RemoveString($k, "group_");
                $userGr->AddUserToGroup($userId, $groupId, false);
            }
        }
    }

    public function SaveUsersGroupWeb() {
        $ajax = $this->PrepareAjaxParametrs();
        $id = $ajax["UserGroupId"];
        $uweb = UserGroupsWeb::GetInstance();
        $uweb->DeleteByCondition("UserGroupId = $id AND Deleted= 0", false, false);

        if (!empty($ajax["WebList"])) {
            foreach ($ajax["WebList"] as $webId) {
                $webId = $webId[0];
                $webId = trim($webId);
                $webId = \Utils\StringUtils::RemoveString($webId, "web-");
                $uweb->UserWebId = $webId;
                $uweb->UserGroupId = $ajax["UserGroupId"];
                $uweb->SaveObject();
            }
        }
    }

    public function SaveUsersModules() {
        $ajax = $this->PrepareAjaxParametrs();
        $ugm = UserGroupsModules::GetInstance();
        $id = $ajax["UserGroupId"];
        $ugm->DeleteByCondition("UserGroupId = $id AND Deleted= 0", false, false);
        foreach ($ajax["ModuleList"] as $moduleId) {
            $moduleId = $moduleId[0];
            $moduleId = trim($moduleId);
            $moduleId = \Utils\StringUtils::RemoveString($moduleId, "module-");
            $ugm->ModuleId = $moduleId;
            $ugm->UserGroupId = $ajax["UserGroupId"];
            $ugm->SaveObject();
        }
    }

}
