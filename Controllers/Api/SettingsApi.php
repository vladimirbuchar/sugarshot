<?php

namespace Controller;

use Model\ContentVersion;
use Model\UserGroups;
use Model\Langs;
use Model\AdminLangs;
use Utils\Files;
use Model\UserDomains;
use Model\UserDomainsItems;
use Model\UserDomainsValues;
use Model\UserDomainsGroups;
use Model\UserDomainsAddiction;
use Model\ExportSetting;
use Model\Content;
use Model\UserDomainsItemsInGroups;

class SettingsApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        $this->SetApiFunction("GetUserDomainDetail", array("system", "Administrators"));
        $this->SetApiFunction("SaveUserDomainValue", array("system", "Administrators"));
        $this->SetApiFunction("UserDomaiReloadList", array("system", "Administrators"));
        $this->SetApiFunction("DeleteValueInUserDomain", array("system", "Administrators"));
        $this->SetApiFunction("ExportSettings", array("system", "Administrators"));
        $this->SetApiFunction("SaveWebPrivileges", array("system", "Administrators"));
        $this->SetApiFunction("StartCopyLang", array("system", "Administrators"));
        $this->SetApiFunction("GetUserDomainGroupDetail", array("system", "Administrators"));
        $this->SetApiFunction("SaveUserDomainGroup", array("system", "Administrators"));
        $this->SetApiFunction("UserDomainGroupReloadTable", array("system", "Administrators"));
        $this->SetApiFunction("DeleteUserDomainGroupItem", array("system", "Administrators"));
        $this->SetApiFunction("AddDomainItemToGroup", array("system", "Administrators"));
        $this->SetApiFunction("GetIntemsInDomainGroup", array("system", "Administrators"));
        $this->SetApiFunction("StartCleanDatabase", array("system", "Administrators"));
        $this->SetApiFunction("GetUserDomainAddictionDetail", array("system", "Administrators"));
        $this->SetApiFunction("SaveUserDomainAddiction", array("system", "Administrators"));
        $this->SetApiFunction("UserDomainAddictionReloadTable", array("system", "Administrators"));
        $this->SetApiFunction("DeleteUserDomainAddctionItem", array("system", "Administrators"));
        $this->SetApiFunction("GetMailingItemDetail", array("system", "Administrators"));
        $this->SetApiFunction("SetDefaultWebPriviles", array("system", "Administrators"));
        $this->SetApiFunction("SaveMailinContact", array("system", "Administrators"));
        $this->SetApiFunction("DeleteItem", array("system", "Administrators"));
        $this->SetApiFunction("AddItem", array("system", "Administrators"));
        $this->SetApiFunction("GetDetailItem", array("system", "Administrators"));
        $this->SetApiFunction("CopyItem", array("system", "Administrators"));
        $this->SetApiFunction("ExportData", array("system", "Administrators"));
        $this->SetApiFunction("Import", array("system", "Administrators"));
        $this->SetApiFunction("LoadTable", array("system", "Administrators"));
        $this->SetApiFunction("RecoveryItem", array("system", "Administrators"));
        $this->SetApiFunction("ShowHistory", array("system", "Administrators"));
        $this->SetApiFunction("RecoveryFromHistory", array("system", "Administrators"));
    }

    public function GetUserDomainDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $domainValues = new \Objects\UserDomains();
        return $domainValues->GetDomainValueByDomainId($_GET["objectid"], $ajaxParametrs["Id"]);
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
        $domainid = $_GET["objectid"];
        $userDomainValue = new \Objects\UserDomains();
        $values = $userDomainItem->GetDomainValueList($domainid, $deleted);
        return $values;
    }

    public function DeleteValueInUserDomain() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userDomainValue = new \Objects\UserDomains();
        $userDomainValue->Delete($ajaxParametrs["Id"]);
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
        $content = new \Objects\Content();
        $content->CopyLang($ajaxParametrs["SourceLang"], $ajaxParametrs["DestinationLang"]);
    }

    public function GetUserDomainGroupDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userDomGroup = UserDomainsGroups::GetInstance();
        return $userDomGroup->GetObjectById($ajaxParametrs["Id"]);
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
        $userDomainValue = UserDomainsGroups::GetInstance();
        $userDomainValue->DeleteObject($ajaxParametrs["Id"]);
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
                $usersInGroup->DeleteByCondition("UserId = ".$row["Id"]);
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
                
                $usersInGroup->DeleteByCondition("GroupId = ".$row["Id"]);
                $userGroupModules->DeleteByCondition("UserGroupId = ".$row["Id"]);
                $userGroupWeb->DeleteByCondition("UserGroupId = ".$row["Id"]);
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
                $langs->DeleteByCondition("WebId = ".$row["Id"]);
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
        $userDomAd = UserDomainsAddiction::GetInstance();
        return $userDomAd->GetObjectById($ajaxParametrs["Id"]);
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

    /** metoda pro smazání objektu */
    public function DeleteItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $deletePernamently = false;


        if (!empty($ajaxParametrs["DeletePernamently"])) {
            if ($ajaxParametrs["DeletePernamently"] == "true")
                $deletePernamently = true;
        }
        $model = $model = "Model\\" . $ajaxParametrs["ModelName"];
        $item = new $model();
        $item->DeleteObject($ajaxParametrs["Id"], $deletePernamently);
    }

    /** metoda pro přidání položky */
    public function AddItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return 0;
        if (empty($ajaxParametrs["ModelName"]))
            return 0;

        $model = "Model\\" . $ajaxParametrs["ModelName"];
        unset($ajaxParametrs["ModelName"]);
        unset($ajaxParametrs["deleteId"]);
        unset($ajaxParametrs["recoveryId"]);
        unset($ajaxParametrs["copyId"]);
        $item = $model::GetInstance();
        if (empty($ajaxParametrs["Id"]))
            $ajaxParametrs["Id"] = 0;

        $id = $item->AddItem($item, $ajaxParametrs);

        $out = array();
        $out["Id"] = $id;
        if ($item->IsError) {
            $out["Errors"] = $item->GetError();
        }
        return $out;
    }

    /** metoda pro zobrazení detailu položky */
    public function GetDetailItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $model = "Model\\" . $ajaxParametrs["ModelName"];
        $item = new $model();
        return (array) $item->GetObjectById($ajaxParametrs["Id"]);
         
    }

    /** metoda pro zkopírování položky */
    public function CopyItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        
        $model = "Model\\" . $ajaxParametrs["ModelName"];
        $item = new $model();
        return $item->CopyObject($ajaxParametrs["Id"]);
    }

    /** metoda  pro vyexportování dat z číselníku  */
    public function ExportData() {

        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $modelName = "Model\\" . $ajaxParametrs["ModelName"];
        $model = new $modelName();
        $mode = $ajaxParametrs["ExportType"];
        return $model->Export($mode);
    }

    /** metoda pro naimportovaní dat do číselníku */
    public function Import() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $modelName = "Model\\" . $ajaxParametrs["ModelName"];
        $model = new $modelName();
        $filePath = $ajaxParametrs["FilePath"];
        $mode = $ajaxParametrs["Mode"];
        return $model->ImportFile($filePath, $mode);
    }

    /** metoda pro znovu načtení tabulky  */
    public function LoadTable() {
        self::$SessionManager->UnsetKey("where");
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;

        $sort = null;
        $where = "";
        $saveToSession = true;
        if (!empty($ajaxParametrs["SortColumn"]) && !empty($ajaxParametrs["SortType"]))
            $sort = new SortDatabase($ajaxParametrs["SortType"], $ajaxParametrs["SortColumn"]);
        $modelName = "Model\\" . $ajaxParametrs["ModelName"];
        $sessionId = $ajaxParametrs["ModelName"];
        $model = new $modelName();
        if (!empty($ajaxParametrs["SaveFiltrSortToSession"])) {
            $saveToSession = $ajaxParametrs["SaveFiltrSortToSession"] == "true" ? true : false;
        }

        $extWhere = self::$SessionManager->IsEmpty($sessionId . "_extWhere") ? "" : self::$SessionManager->GetSessionValue($sessionId . "_extWhere");
        if (!empty($extWhere)) {
            if ($saveToSession) {
                self::$SessionManager->SetSessionValue("where", $extWhere, $sessionId);
            }
        }

        $showDeleteItem = false;
        if (!empty($ajaxParametrs["ShowItem"])) {
            if ($ajaxParametrs["ShowItem"] == "DeleteItem")
                $showDeleteItem = true;
            else
                $showDeleteItem = false;
        }
        $objectid = 0;
        if (!empty($_GET["objectid"]))
            $objectid = $_GET["objectid"];
        $outData = array();
        if ($model->IsTable) {
            $outData = $model->Select(array(), $showDeleteItem, true, true, $sort, $where, $saveToSession, $objectid);
        } else if ($model->IsView) {
            $outData = $model->Select(array(), $showDeleteItem, true, true, $sort, $where, $saveToSession, $objectid);
        }
        $outData = $this->ReplaceHtmlWord($outData);
        return $outData;
    }

    /** metoda pro zobrazení smazaných dat z číselníku */
    public function RecoveryItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $modelName = "Model\\" . $ajaxParametrs["ModelName"];
        $model = new $modelName();
        $item = new $model();
        $item->RecoveryObject($ajaxParametrs["Id"]);
    }

    /** metoda pro zobrazení historie změn v objektu */
    public function ShowHistory() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $modelName = "Model\\" . $ajaxParametrs["ModelName"];
        $model = new $modelName();
        $item = new \Objects\ObjectHistory();
        return $item->GetHistoryObject($ajaxParametrs["ModelName"], $ajaxParametrs["Id"]);
    }

    /** metoda pro obnovení dat z historie */
    public function RecoveryFromHistory() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $item = new \Objects\ObjectHistory();
        return $item->RecoveryItemFromHistory($ajaxParametrs["Id"]);
    }

}
