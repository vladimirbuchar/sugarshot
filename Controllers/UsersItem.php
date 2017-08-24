<?php

namespace Controller;

use Types\TableHeader;
use Model\AdminLangs;
use Components\Table;
use Model\UserModulesView;
use Model\WebsList;


class UsersItem extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        $this->SetTemplateData("controllerName", $this->ControllerName);
        $this->AddScript("/Scripts/Domain.js");
        $this->SetViewSettings("UserList", array("system", "Administrators"));
        $this->SetViewSettings("UserGroup", array("system", "Administrators"));
        $this->SetViewSettings("MyProfile", array("system", "Administrators"));
    }

    public function UserList() {
        $this->SharedView = "List";
        $colums = array("Id", "UserName", "FirstName", "LastName", "UserEmail",);
        $header = array();
        $header[] = new TableHeader($this->GetWord("word170"), "WebName");
        $header[] = new TableHeader($this->GetWord("word171"), "FirstName");
        $header[] = new TableHeader($this->GetWord("word172"), "LastName");
        $header[] = new TableHeader($this->GetWord("word173"), "UserEmail");
        $userGroups = new \Objects\Users();
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
        $header[] = new TableHeader($this->GetWord("word164"), "GroupName");
        $table = new Table();
        $table->Header = $header;
        $table->AddAction = "AddUserGroup";
        $table->HideColumns = array("Id");
        $table->ModelName = "UserGroups";
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

    public function MyProfile() {

        $this->SharedView = "DomainDetail";
        $this->SetStateTitle($this->GetWord("word226"));
        $userId = empty($_GET["objectid"]) ? self::$User->GetUserId() : $_GET["objectid"];
        $domainHtml = $this->GetUserDomain("UserProfile", $userId);
        $this->SetTemplateData("domainId", "UserProfile");
        $this->SetTemplateData("DomainData", $domainHtml);
    }

}
