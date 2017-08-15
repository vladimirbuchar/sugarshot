<?php

namespace Controller;

use Types\TableHeader;
use Model\UserGroups;
use Model\AdminLangs;
use Components\Table;
use Model\Users;
use Model\UserModulesView;
use Model\WebsList;
use Types\TableHeaderFiltrType;
use Model\UsersInGroup;
use Model\UserGroupsModules;
use Model\UserGroupsWeb;

class UsersItem extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        if ($this->IsPostBack || $this->IsGet) {
            $this->SetTemplateData("controllerName", $this->ControllerName);
            $this->AddScript("/Scripts/Domain.js");
            $this->SetViewPermition("UserList", array("system", "Administrators"));
            $this->SetViewPermition("UserGroup", array("system", "Administrators"));
            $this->SetViewPermition("MyProfile", array("system", "Administrators"));
        }
        if (self::$IsAjax) {
            $this->SetAjaxFunction("GroupDetail", array("system", "Administrators"));
            $this->SetAjaxFunction("AddUserGroup", array("system", "Administrators"));
            $this->SetAjaxFunction("DeleteUserGroup", array("system", "Administrators"));
            $this->SetAjaxFunction("GroupListLoadTable", array("system", "Administrators"));
            $this->SetAjaxFunction("UserDetail", array("system", "Administrators"));
            $this->SetAjaxFunction("AddUser", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveProfile", array("system", "Administrators"));
            $this->SetAjaxFunction("RecoveryUserGroup", array("system", "Administrators"));
            $this->SetAjaxFunction("UserListLoadTable", array("system", "Administrators"));
            $this->SetAjaxFunction("DeleteUser", array("system", "Administrators"));
            $this->SetAjaxFunction("ChangePassword", array("system", "Administrators"));
            $this->SetAjaxFunction("GetDefaultState", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveOtherUserGroups", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveUsersGroupWeb", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveUsersModules", array("system", "Administrators"));
        }
    }

    public function UserList() {
        $this->SharedView = "List";
        $colums = array("Id", "UserName", "FirstName", "LastName", "UserEmail",);
        $header = array();
        $header[] = new TableHeader($this->GetWord("word170"), "WebName", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word171"), "FirstName", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word172"), "LastName", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word173"), "UserEmail", TableHeaderFiltrType::$Textbox);
        $userGroups =  new \Objects\Users();
        $systemUserGroups = $userGroups->GetSystemGroups();
        $noSystemUserGroup = $userGroups->GetNoSystemGroups();
        $this->SetTemplateData("SystemUserGroups", $systemUserGroups);
        $this->SetTemplateData("NoSystemUserGroups", $noSystemUserGroup);
        $adminLangs = AdminLangs::GetInstance();
        $this->SetTemplateData("AdminLangs", $adminLangs->Select());

        $table = new Table();
        $table->Header = $header;
        $table->AddAction = "AddUser";
        $table->HideColumns = array("Id");
        $table->ModelName = "Users";
        $table->AddDialog = "./AddUser.html";
        $table->ColName = "UserName";
        $table->IdColumn = "Id";
        $table->DetailFunction = "UserDetail";
        $table->DeleteAction = "DeleteUser";
        $table->ShowHistoryButton = false;
        $table->ShowFiltr = false;
        $table->ShowSort = false;
        $table->ShowExportAllButton = false;
        $table->ShowImport = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->RefreschTable = "UserListLoadTable";
        $table->ControllerName = "UsersItem";
        $table->ScrollClass = "scrollTable1200";
        $table->ShowRecoveryButton = false;
        $table->ShowRecoveryMultiSelect = false;
        $links = array();
        $links[] = $this->CreateLink("UsersItem", "MyProfile", "word770", "xadm", $this->WebId, $this->LangId, "{" . $table->IdColumn . "}");


        $table->SpecialLinks = $links;

        $where = "";
        if (!self::$User->IsSystemUser()) {
            $where = " UserName <> 'system'";
        }
        $this->PrepareList($this->GetWord("word140"), $colums, $table, 0, $where);
    }

    public function UserDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userDetail = self::$User->GetUserDetail($id);
        return $userDetail;
    }

    public function AddUser() {
        $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $user =  new \Objects\Users();
        $id = $user->CreateUser($id, $ajaxParametrs["FirstName"], $ajaxParametrs["LastName"], $ajaxParametrs["UserEmail"], $ajaxParametrs["UserName"], $ajaxParametrs["UserPassword"], $ajaxParametrs["BlockDiscusion"], $ajaxParametrs["MainUserGroup"], array(), $ajaxParametrs["IsActive"], $ajaxParametrs["DefaultLang"], true);
        return $id;
    }

    public function UserGroup() {
        $modules = new UserModulesView();
        $modulesList = $modules->SelectByCondition("UserGroupId = " . self::$UserGroupId);
        $this->SetTemplateData("ModulesList", $modulesList);
        $webs = new WebsList();
        $websList = $webs->SelectByCondition("UserGroupId = " . self::$UserGroupId);
        $this->SetTemplateData("WebsList", $websList);
        $this->SharedView = "List";
        $colums = array("Id", "GroupName");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word164"), "GroupName", TableHeaderFiltrType::$Textbox);
        $table = new Table();
        $table->Header = $header;
        $table->AddAction = "AddUserGroup";
        $table->HideColumns = array("Id");
        $table->ModelName = "usergroups";
        $table->AddDialog = "./AddUserGroup.html";
        $table->ColName = "GroupName";
        $table->IdColumn = "Id";
        $table->DetailFunction = "GroupDetail";
        $table->DeleteAction = "DeleteUserGroup";
        $table->ShowHistoryButton = false;
        $table->ShowFiltr = false;
        $table->ShowSort = false;
        $table->ShowExportAllButton = false;
        $table->ShowImport = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->RefreschTable = "GroupListLoadTable";
        $table->ControllerName = "UsersItem";
        $table->RecoveryServerAction = "RecoveryUserGroup";
        $where = "";
        if (!self::$User->IsSystemUser())
            $where = "GroupName <> 'system'";
        $this->PrepareList($this->GetWord("word142"), $colums, $table, 0, $where);
        $this->SetWordList();
    }

    public function RecoveryUserGroup() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $ug = UserGroups::GetInstance();
        $ug->RecoveryObject($id);
    }

    public function UserListLoadTable() {
        self::$SessionManager->UnsetKey("where");
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $sort = null;
        $where = "";
        $saveToSession = false;
        if (!empty($ajaxParametrs["SortColumn"]) && !empty($ajaxParametrs["SortType"]))
            $sort = new SortDatabase($ajaxParametrs["SortType"], $ajaxParametrs["SortColumn"]);
        $model =  Users::GetInstance();
        if (!empty($ajaxParametrs["SaveFiltrSortToSession"])) {
            $saveToSession = $ajaxParametrs["SaveFiltrSortToSession"];
        }
        if (!empty($ajaxParametrs["Where"])) {
            if ($saveToSession) {
                $modelName = $ajaxParametrs["ModelName"];
                self::$SessionManager->SetSessionValue("where", $ajaxParametrs["Where"],$modelName);
                
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

    public function GroupListLoadTable() {
        self::$SessionManager->UnsetKey("where");
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
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

    public function GroupDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userGroup = new \Objects\Users();
        $userGroupDetail = $userGroup->GetUserGroupDetail($id);
        return $userGroupDetail;
    }

    public function AddUserGroup() {
        $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);

        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userGroup = UserGroups::GetInstance();
        $userGroup->Id = $id;
        $userGroup->GroupIdentificator = $ajaxParametrs["GroupIdentificator"];
        $userGroup->GroupName = $ajaxParametrs["GroupName"];
        $userGroup->IsSystemGroup = $ajaxParametrs["IsSystemGroup"];
        $userGroup->UserDefaultState = $ajaxParametrs["UserDefaultState"];
        $id = $userGroup->SaveObject($userGroup);
        /*

          if (!empty($ajaxParametrs["ModuleId"]))
          {

          foreach ($ajaxParametrs["ModuleId"] as $moduleId) {
          $ugm = new UserGroupsModules();
          if (empty($moduleId) || $moduleId == 0)
          continue;
          $ugm->ModuleId = $moduleId;
          $ugm->UserGroupId = $id;
          $ugm->SaveObject($ugm);
          }
          } */
        /* if (!empty($ajaxParametrs["UserWebId"]))
          {
          if (is_array($ajaxParametrs["UserWebId"]))
          {
          foreach ($ajaxParametrs["UserWebId"] as $webId) {
          if (empty($webId) || $webId == 0)
          {
          continue;
          }
          $uweb->UserWebId = $webId;
          $uweb->UserGroupId = $id;
          $uweb->SaveObject($uweb);
          }
          }
          else
          {
          $uweb->UserWebId = $ajaxParametrs["UserWebId"];
          $uweb->UserGroupId = $id;
          $uweb->SaveObject($uweb);
          }
          } */
        return $id;
    }

    public function SaveUsersGroupWeb() {
        $ajax = $this->PrepareAjaxParametrs();
        $id = $ajax["UserGroupId"];
        $uweb =  UserGroupsWeb::GetInstance();
        $uweb->DeleteByCondition("UserGroupId = $id AND Deleted= 0", false, false);

        if (!empty($ajax["WebList"])) {
            foreach ($ajax["WebList"] as $webId) {
                $webId = $webId[0];
                $webId = trim($webId);
                $webId = \Utils\StringUtils::RemoveString($webId, "web-");
                $uweb->UserWebId = $webId;
                $uweb->UserGroupId = $id;
                $uweb->SaveObject($uweb);
            }
        }
    }

    public function SaveUsersModules() {
        $ajax = $this->PrepareAjaxParametrs();
        $id = $ajax["UserGroupId"];

        $ugm =  UserGroupsModules::GetInstance();
        $ugm->DeleteByCondition("UserGroupId = $id AND Deleted= 0", false, false);
        foreach ($ajax["ModuleList"] as $moduleId) {
            $moduleId = $moduleId[0];
            $moduleId = trim($moduleId);
            $moduleId = \Utils\StringUtils::RemoveString($moduleId, "module-");
            $ugm->ModuleId = $moduleId;
            $ugm->UserGroupId = $id;
            $ugm->SaveObject($ugm);
        }
    }

    public function DeleteUserGroup() {

        $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userGroup = UserGroups::GetInstance();
        $pernametly = false;
        if ($ajaxParametrs["DeletePernamently"] == "true")
            $pernametly = true;
        $userGroup->DeleteObject($id, $pernametly);
    }

    public function DeleteUser() {

        $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userGroup =  Users::GetInstance();
        $pernametly = false;
        if ($ajaxParametrs["DeletePernamently"] == "true")
            $pernametly = true;
        $userGroup->DeleteObject($id, $pernametly);
    }

    public function MyProfile() {

        $this->SharedView = "DomainDetail";
        $this->SetStateTitle($this->GetWord("word226"));
        $userId = empty($_GET["objectid"]) ? self::$User->GetUserId() : $_GET["objectid"];
        $domainHtml = $this->GetUserDomain("UserProfile", $userId);
        $this->SetTemplateData("domainId", "UserProfile");
        $this->SetTemplateData("DomainData", $domainHtml);
    }

    public function SaveProfile() {
        $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        $this->SaveUserDomain($ajaxParametrs);
    }

    public function ChangePassword() {
        $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            return;
        $password1 = $ajaxParametrs["password1"];
        $password2 = $ajaxParametrs["password2"];
        $userId = $ajaxParametrs["UserId"];
        $user = new \Objects\Users();
        return $user->ChangePassword($password1, $password2, $userId);
    }

    public function GetDefaultState() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $user = UserGroups::GetInstance();
        $user->GetObjectById(self::$UserGroupId, true);
        $state = $user->UserDefaultState;
        if (empty($state))
            return "";
        $ar = explode("#", $state);
        $url = "/xadm/" . $ar[0] . "/" . $ar[1] . "/" . $ajaxParametrs["SelectWebId"] . "/" . $ajaxParametrs["SelectLangId"] . "/";
        return $url;
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

}
