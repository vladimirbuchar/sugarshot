<?php

namespace Controller;

use Components\Table;
use Model\WebsList;
use Types\TableHeader;
use Model\ContentVersion;
use Model\UserGroups;
use Model\Langs;
use Model\AdminLangs;
use Utils\Files;
use Types\TableHeaderFiltrType;
use Model\UserDomains;
use Model\UserDomainsItems;
use Model\UserDomainsValues;
use Model\UserDomainsGroups;
use Model\UserDomainsAddiction;
use Model\MailingContactsInGroups;
use Types\ExportSettings;
use Model\ExportSetting;
use Model\Content;
use Model\UserDomainsItemsInGroups;

class SettingsApi extends SettingsController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        $this->SetAjaxFunction("GetUserDomainDetail", array("system", "Administrators"));
        $this->SetAjaxFunction("SaveUserDomainValue", array("system", "Administrators"));
        $this->SetAjaxFunction("UserDomaiReloadList", array("system", "Administrators"));
        $this->SetAjaxFunction("DeleteValueInUserDomain", array("system", "Administrators"));
        $this->SetAjaxFunction("ExportSettings", array("system", "Administrators"));
        $this->SetAjaxFunction("SaveWebPrivileges", array("system", "Administrators"));
        $this->SetAjaxFunction("StartCopyLang", array("system", "Administrators"));
        $this->SetAjaxFunction("GetUserDomainGroupDetail", array("system", "Administrators"));
        $this->SetAjaxFunction("SaveUserDomainGroup", array("system", "Administrators"));
        $this->SetAjaxFunction("UserDomainGroupReloadTable", array("system", "Administrators"));
        $this->SetAjaxFunction("DeleteUserDomainGroupItem", array("system", "Administrators"));
        $this->SetAjaxFunction("AddDomainItemToGroup", array("system", "Administrators"));
        $this->SetAjaxFunction("GetIntemsInDomainGroup", array("system", "Administrators"));
        $this->SetAjaxFunction("StartCleanDatabase", array("system", "Administrators"));
        $this->SetAjaxFunction("GetUserDomainAddictionDetail", array("system", "Administrators"));
        $this->SetAjaxFunction("SaveUserDomainAddiction", array("system", "Administrators"));
        $this->SetAjaxFunction("UserDomainAddictionReloadTable", array("system", "Administrators"));
        $this->SetAjaxFunction("DeleteUserDomainAddctionItem", array("system", "Administrators"));
        $this->SetAjaxFunction("GetMailingItemDetail", array("system", "Administrators"));
        $this->SetAjaxFunction("SetDefaultWebPriviles", array("system", "Administrators"));
        $this->SetAjaxFunction("SaveMailinContact", array("system", "Administrators"));
    }

    public function GetUserDomainDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $domainValues = new \Objects\UserDomains();
        $data = $domainValues->GetDomainValueByDomainId($_GET["objectid"], $id);
        return $data;
    }

    public function SaveUserDomainValue() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userValues = new \Objects\UserDomains();
        $out["Id"] = $userValues->SaveUserDomainData($ajaxParametrs);
        return $out;
    }

    public function UserDomaiReloadList() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $deleted = $ajaxParametrs["ShowItem"] == "DeleteItem" ? TRUE : FALSE;
        $this->SetTemplateData("DomainId", $_GET["objectid"]);
        $domainid = $_GET["objectid"];
        $domain = UserDomains::GetInstance();
        $domainInfo = $domain->GetObjectById($_GET["objectid"]);
        $userDomainItem = new \Objects\UserDomains();
        $items = $userDomainItem->GetUserDomainItemById($domainid);
        $userDomainValue = new \Objects\UserDomains();
        $values = $userDomainValue->GetDomainValueList($domainid, $deleted);
        return $values;
    }

    public function DeleteValueInUserDomain() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userDomainValue = new \Objects\UserDomains();
        $userDomainValue->Delete($id);
    }

    public function ExportSettings() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        $files = array();
        $folderName = \Utils\StringUtils::GenerateRandomString();
        \Utils\Folders::CreateFolder(TEMP_EXPORT_PATH, $folderName);
        foreach ($ajaxParametrs as $key => $value) {
            if ($value == 1) {
                $ex = new ExportSetting($key, $folderName);
                $out = $ex->CallExport();
                if (!empty($out))
                    $files[] = $out;
            }
        }
        $zipFolder = TEMP_EXPORT_PATH . $folderName;
        return Files::ZipFolder($zipFolder);
    }

    public function SaveWebPrivileges() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        $web = new \Objects\Webs();
        $web->SaveWebPrivileges($ajaxParametrs["Id"], $ajaxParametrs["privileges"]);
    }

    public function StartCopyLang() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $source = $ajaxParametrs["SourceLang"];
        $dest = $ajaxParametrs["DestinationLang"];
        $content = new \Objects\Content();
        $content->CopyLang($source, $dest);
    }

    public function GetUserDomainGroupDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];

        $userDomGroup = UserDomainsGroups::GetInstance();
        return $userDomGroup->GetObjectById($id, true);
    }

    public function SaveUserDomainGroup() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $group = UserDomainsGroups::GetInstance();
        $out["Id"] = $group->SaveGroup($ajaxParametrs["Id"], $ajaxParametrs["GroupName"], $ajaxParametrs["DomainId"]);
        return $out;
    }

    public function UserDomainGroupReloadTable() {
        $domainid = $_GET["objectid"];
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $udg = UserDomainsGroups::GetInstance();
        $deleted = $ajaxParametrs["ShowItem"] == "DeleteItem" ? TRUE : FALSE;
        if ($deleted)
            return $udg->SelectByCondition("Deleted= 1 AND DomainId = $domainid");
        else
            return $udg->SelectByCondition("Deleted= 0 AND DomainId = $domainid");
    }

    public function DeleteUserDomainGroupItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userDomainValue = UserDomainsGroups::GetInstance();
        $userDomainValue->DeleteObject($id);
    }

    public function AddDomainItemToGroup() {
        $params = $_POST["params"];
        $groupSetting = new \Objects\UserDomains();
        $groupSetting->SaveItemInGroup($params[0][1], $params);
    }

    public function GetIntemsInDomainGroup() {
        $groupSetting = new \Objects\UserDomains();
        return $groupSetting->GetUserItemInGroups($_GET["params"]);
    }

    public function StartCleanDatabase() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if ($ajaxParametrs["DeleteContent"]) {
            $content = Content::GetInstance();
            $contentVersion = ContentVersion::GetInstance();
            $content->Clean();
            $contentVersion->Clean();
        }

        if ($ajaxParametrs["DeleteUsers"]) {
            $obj = new Users();
            $deletedUsers = $obj->SelectByCondition("Deleted = 1");
            $usersInGroup = new UsersInGroup();
            foreach ($deletedUsers as $row) {
                $id = $row["Id"];
                $usersInGroup->DeleteByCondition("UserId = $id");
            }
            $usersInGroup->Clean();
            $obj->Clean();
        }
        if ($ajaxParametrs["DeleteUsersGroups"]) {
            $obj = UserGroups::GetInstance();
            $deletedUserGroups = $obj->SelectByCondition("Deleted = 1");
            $usersInGroup = new UsersInGroup();
            $userGroupModules = new UserGroupsModules();
            $userGroupWeb = new UserGroupsModules();
            foreach ($deletedUserGroups as $row) {
                $id = $row["Id"];
                $usersInGroup->DeleteByCondition("GroupId = $id");
                $userGroupModules->DeleteByCondition("UserGroupId = $id");
                $userGroupWeb->DeleteByCondition("UserGroupId = $id");
            }
            $userGroupWeb->Clean();
            $userGroupModules->Clean();
            $usersInGroup->Clean();
            $obj->Clean();
        }
        if ($ajaxParametrs["DeleteWebs"]) {
            $obj = \Model\Webs::GetInstance();
            $deletedWebs = $obj->SelectByCondition("Deleted = 1");
            $langs = Langs::GetInstance();
            foreach ($deletedWebs as $row) {
                $id = $row["Id"];
                $langs->DeleteByCondition("WebId = $id");
            }
            $langs->Clean();
            $obj->Clean();
        }
        if ($ajaxParametrs["DeleteLangs"]) {
            $obj = Langs::GetInstance();
            $obj->Clean();
        }
        if ($ajaxParametrs["DeleteAdminLangs"]) {
            $obj = AdminLangs::GetInstance();
            $obj->Clean();
        }
        if ($ajaxParametrs["DeleteWords"]) {
            $obj = new WordGroups();
            $obj->Clean();
        }
        if ($ajaxParametrs["DeleteModules"]) {
            $obj = new Modules();
            $obj->Clean();
        }
        if ($ajaxParametrs["DeleteDomains"]) {
            $obj = UserDomains::GetInstance();
            $obj->Clean();
            $obj = UserDomainsGroups::GetInstance();
            $obj->Clean();
            $obj = UserDomainsItems::GetInstance();
            $obj->Clean();
            $obj = UserDomainsItemsInGroups::GetInstance();
            $obj->Clean();
            $obj = UserDomainsValues::GetInstance();
            $obj->Clean();
        }
    }

    public function GetUserDomainAddictionDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];

        $userDomAd = UserDomainsAddiction::GetInstance();
        return $userDomAd->GetObjectById($id, true);
    }

    public function SaveUserDomainAddiction() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $addiction = new \Objects\UserDomains();
        $out["Id"] = $addiction->SaveAddiction($ajaxParametrs["Id"], $ajaxParametrs["DomainId"], $ajaxParametrs["AddictionName"], $ajaxParametrs["Item1"], $ajaxParametrs["RuleName"], $ajaxParametrs["Item1Value"], $ajaxParametrs["ActionName"], $ajaxParametrs["ItemXValue"], $ajaxParametrs["ItemX"], $ajaxParametrs["Priority"]);
        return $out;
    }

    public function UserDomainAddictionReloadTable() {
        $domainid = $_GET["objectid"];
        $ajaxParametrs = array();
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $udg = UserDomainsAddiction::GetInstance();
        $deleted = $ajaxParametrs["ShowItem"] == "DeleteItem" ? TRUE : FALSE;
        if ($deleted)
            return $udg->SelectByCondition("Deleted= 1 AND DomainId = $domainid");
        else
            return $udg->SelectByCondition("Deleted= 0 AND DomainId = $domainid");
    }

    public function DeleteUserDomainAddctionItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userDomainValue = UserDomainsAddiction::GetInstance();
        $userDomainValue->DeleteObject($id);
    }

    public function GetMailingItemDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $out = array();
        $mailinig = new \Objects\MailingContacts();
        $detail = $mailinig->GetMailingDetail($ajaxParametrs["Id"]);
        $malingGroups = $mailinig->GetUserMailingGroups($ajaxParametrs["Id"]);
        $out["Detail"] = $detail[0];
        $out["MailingGroups"] = $malingGroups;
        return $out;
    }

    public function SetDefaultWebPriviles() {
        $web = new \Objects\Webs();
        $web->SetDefaultWebPrivileges($_POST["params"]);
    }

    public function SaveMailinContact() {

        $ajaxParametrs = $this->PrepareAjaxParametrs();

        if (empty($ajaxParametrs))
            return;
        $id = empty($ajaxParametrs["Id"]) ? 0 : $ajaxParametrs["Id"];
        if ($id == 0)
            return;

        $mg = new \Objects\MailingContacts();
        $mg->AddContactToMailingGroup($id, $ajaxParametrs["MailingGroups"]);
    }

}
