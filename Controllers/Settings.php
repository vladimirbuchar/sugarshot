<?php

namespace Controller;

use Components\Table;
use Model\WebsList;
use Types\TableHeader;
use Model\ContentVersion;
use Model\UserGroups;
use Model\Langs;
use Model\AdminLangs;
use Kernel\Files;
use Types\TableHeaderFiltrType;
use Model\UserDomains;
use Model\UserDomainsItems;
use Model\UserDomainsValues;
use Model\UserDomainsGroups;
use Model\UserDomainsAddiction;
use Model\MailingContacts;
use Model\MailingContactsInGroups;
use Types\ExportSettings;
use Model\ExportSetting;
use Model\Content;
use Model\UserDomainsItemsInGroups;

class Settings extends SettingsController {

    public function __construct() {

        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        if ($this->IsPostBack || $this->IsGet) {

            $this->SetTemplateData("controllerName", $this->ControllerName);
            $this->SetViewPermition("WebList", array("system", "Administrators"));
            $this->SetViewPermition("CronManager", array("system", "Administrators"));
            $this->SetCommnadFunction("ClearLog", array("system", "Administrators"));
            $this->SetCommnadFunction("DowlandFile", array("system", "Administrators"));
            $this->SetViewPermition("LangList", array("system", "Administrators"));
            $this->SetViewPermition("Words", array("system", "Administrators"));
            $this->SetViewPermition("AdminLangs", array("system", "Administrators"));
            $this->SetViewPermition("Modules", array("system", "Administrators"));
            $this->SetViewPermition("UserDomain", array("system", "Administrators"));
            $this->SetViewPermition("UserDomainDetail", array("system", "Administrators"));
            $this->SetViewPermition("UserDomainValueList", array("system", "Administrators"));
            $this->SetViewPermition("DeveloperTools", array("system", "Administrators"));
            $this->SetMustBeWebId("LangList");
            $this->SetMustBeWebId("Modules");
            $this->SetMustBeWebId("UserDomain");
            $this->SetMustBeLang("DeveloperTools");
            $this->SetMustBeWebId("DeveloperTools");
            $this->AddScript("/Scripts/Domain.js");
            $this->CheckWebPrivileges("LangList");
            $this->CheckWebPrivileges("UserDomain");
            $this->CheckWebPrivileges("UserDomainDetail");
            $this->CheckWebPrivileges("UserDomainValueList");
            $this->CheckWebPrivileges("GetUserDomainDetail");
            $this->CheckWebPrivileges("SaveUserDomainValue");
            $this->CheckWebPrivileges("UserDomaiReloadList");
            $this->CheckWebPrivileges("DeleteValueInUserDomain");
            $this->CheckWebPrivileges("DeveloperTools");
            $this->CheckWebPrivileges("Modules");
            $this->SetViewPermition("CopyLang", array("system", "Administrators"));
            $this->SetViewPermition("LogView", array("system", "Administrators"));
            $this->SetViewPermition("UserDomainGroupItem", array("system", "Administrators"));
            $this->SetViewPermition("CleanDatabase", array("system", "Administrators"));
            $this->SetViewPermition("UserDomainAddiction", array("system", "Administrators"));
            $this->SetViewPermition("AutoComplecteList", array("system", "Administrators"));
            $this->SetViewSettings("MailingContacts", array("system", "Administrators"));
        }
        if (self::$IsAjax) {
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
    }
    
    public function CronManager() {
        

        $table = new Table();
        $cron = \Model\Crons::GetInstance();
        $data = $cron->Select();
        $colums = array("Id", "CronName");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word857"), "CronName", TableHeaderFiltrType::$Textbox);
        $table->Header = $header;
        $table->HideColumns = array("Id", "Deleted", "IsSystem", "CronUrl","IsActive","IsRun","RunMode","LastRun");
        $table->Data = $data;
        $table->ModelName = "Crons";
        $table->AddDialog = "./AddCron.html";
        $table->ColName = "CronName";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowCopyItem = FALSE;
        $table->ShowCopySelected = FALSE;
        $table->ShowHistoryButton = false;
        $table->ShowExportAllButton = false;
        $this->PrepareList($this->GetWord("word855"), $colums, $table);
        
    }

    public function WebList() {
        

        $table = new Table();
        $webList = new WebsList();
        $data = $webList->Select(array(), false, false, false, null, "UserGroupId = " . self::$UserGroupId, true);
        $colums = array("Id", "WebName");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word74"), "WebName", TableHeaderFiltrType::$Textbox);
        $table->Header = $header;
        $table->HideColumns = array("Id", "Deleted", "IsSystem", "SmallHeight", "SmallWidth", "MediumHeight", "MediumWidth", "BigHeight", "BigWidth", "UserGroupId", "WebPrivileges", "AdminUserActive", "UserEmailActivate", "EmailUserLogin", "BlockSendEmails", "BlockAdmin", "UseHttps", "WebIpRestrictionAll", "WebIpRestrictionAceptIp", "WebIpRestrictionBlockIp", "WebIpAddress", "AdminIpRestrictionAll", "AdminIpRestrictionAceptIp", "AdminIpRestrictionBlockIp", "AdminIpAddress", "GenerateAjaxLink", "DefaultFramework", "AfterLoginUrl", "AfterLoginAction", "SendInfoEmailToAdmin", "AdminInfoEmail", "AdmiInfoMailId", "SendInfoEmailToUser", "UserInfoEmailFrom", "UserInfoMailId", "CookiesAccept");
        $table->Data = $data;
        $table->ModelName = "Webs";
        $table->ViewName = "WEBSLIST";
        $table->AddDialog = "./AddWeb.html";
        $table->ColName = "WebName";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowCopyItem = FALSE;
        $table->ShowCopySelected = FALSE;
        $table->ShowHistoryButton = false;
        $table->ShowExportAllButton = false;
        $table->AddScriptAction = "SavePrivileges();";
        $content =  ContentVersion::GetInstance();
        $mailList = $content->GetMailList(self::$UserGroupId, $this->LangId, true, "", "Name ASC");
        $this->SetTemplateData("MailListAdmin", $mailList);

        $this->PrepareList($this->GetWord("word73"), $colums, $table);
        $userGroup = UserGroups::GetInstance();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
    }

    public function MailingContacts() {
        $this->SetLeftMenu("contentMenu", "contentMenuMailing");
        $colums = array("Id", "Email");
        $header = array();
        $header[] = new TableHeader("Email", "Email", TableHeaderFiltrType::$Textbox);
        $table = new Table();
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $table->ModelName = "MailingContacts";
        $table->AddDialog = "./AddMailContact.html";
        $table->ColName = "Email";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem =  false;
        $table->ShowCopySelected = false;
        $table->ShowHistoryButton = false;
        
        $table->ScrollClass = "scrollTable1200";
        $this->PrepareList($this->GetWord("word551"), $colums, $table);

        $ud =  UserDomains::GetInstance();
        $info = $ud->GetDomainInfo("Mailinggroups");
        $udv =  UserDomainsValues::GetInstance();
        $values = $udv->GetDomainValueList($info["Id"], false);
        $this->SetTemplateData("MailingGroups", $values);
    }

    public function GetMailingItemDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $out = array();
        $mailinig =  MailingContacts::GetInstance();
        $detail = $mailinig->GetMailingDetail($ajaxParametrs["Id"]);
        $malingGroups = $mailinig->GetUserMailingGroups($ajaxParametrs["Id"]);
        $out["Detail"] = $detail[0];
        $out["MailingGroups"] = $malingGroups;
        return $out;
    }

    public function SaveMailinContact() {
        
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        
        if (empty($ajaxParametrs))
            return;
        $id = empty($ajaxParametrs["Id"]) ?  0: $ajaxParametrs["Id"];
        if ($id == 0)
            return;
        
        $mg =  MailingContactsInGroups::GetInstance();
        $mg->AddContactToMailingGroup($id, $ajaxParametrs["MailingGroups"]);
    }

    public function SaveWebPrivileges() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        $web = new \Objects\Webs();
        $web->SaveWebPrivileges($ajaxParametrs["Id"], $ajaxParametrs["privileges"]);
    }

    public function LangList() {
        $colums = array("Id", "LangName");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word76"), "LangName", TableHeaderFiltrType::$Textbox);
        $table = new Table();
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $table->ModelName = "Langs";
        $table->AddDialog = "./AddLang.html";
        $table->ColName = "LangName";
        $table->IdColumn = "Id";
        $table->ShowImport = false;
        $table->ShowExportAllButton = false;
        $table->ScrollClass = "scrollTable1200";
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ShowHistoryButton = false;
        $table->ShowSort = true;

        $this->PrepareList($this->GetWord("word75"), $colums, $table);
    }

    public function Words() {
        $table = new Table();
        $header = array();
        $header[] = new TableHeader($this->GetWord("word78"), "GroupName", TableHeaderFiltrType::$Textbox);
        $adminLang = AdminLangs::GetInstance();
        $adminLangData = $adminLang->Select();
        $colums = array("Id", "GroupName");
        foreach ($adminLangData as $row) {
            $colums[] = "Word" . $row->LangIdentificator;
            $header[] = new TableHeader($row->LangName, "Word" . $row->LangIdentificator, TableHeaderFiltrType::$Textbox);
        }
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $this->SetTemplateData("AdminForm", $adminLangData);
        $table->ModelName = "WordGroups";
        $table->AddDialog = "./AddWordGroup.html";
        $table->ColName = "GroupName";
        $table->IdColumn = "Id";
        $table->ScrollClass = "scrollTable1200";
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ShowHistoryButton = false;
        $this->PrepareList($this->GetWord("word77"), $colums, $table);
    }

    public function AdminLangs() {
        $table = new Table();
        $colums = array("Id", "LangName", "LangIdentificator");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word80"), "LangName", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word81"), "LangIdentificator", TableHeaderFiltrType::$Textbox);
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $table->ModelName = "AdminLangs";
        $table->AddDialog = "./AddAdminLang.html";
        $table->ColName = "LangName";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ShowHistoryButton = false;
        $table->ScrollClass = "scrollTable1200";
        $this->PrepareList($this->GetWord("word79"), $colums, $table);
    }

    public function Modules() {
        $table = new Table();
        $colums = array("Id", "ModuleName", "ModuleControler", "ModuleView");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word153"), "ModuleName", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word154"), "ModuleControler", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word155"), "ModuleView", TableHeaderFiltrType::$Textbox);
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $table->ModelName = "Modules";
        $table->AddDialog = "./AddModule.html";
        $table->ColName = "ModuleName";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ScrollClass = "scrollTable1200";
        $this->SetWordList();

        $this->PrepareList($this->GetWord("word133"), $colums, $table);
    }

    public function UserDomain() {

        $table = new Table();
        $colums = array("Id", "DomainName", "DomainIdentificator");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word189"), "DomainName", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word190"), "DomainIdentificator", TableHeaderFiltrType::$Textbox);
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $table->ModelName = "UserDomains";
        $table->AddDialog = "./AddUserDomain.html";
        $table->ColName = "DomainName";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ScrollClass = "scrollTable1200";
        $links = array();
        $links[] = $this->CreateLink("Settings", "UserDomainDetail", "word195", "xadm", $this->WebId, $this->LangId, "{" . $table->IdColumn . "}");
        $links[] = $this->CreateLink("Settings", "UserDomainValueList", "word231", "xadm", $this->WebId, $this->LangId, "{" . $table->IdColumn . "}");
        $links[] = $this->CreateLink("Settings", "UserDomainGroupItem", "word628", "xadm", $this->WebId, $this->LangId, "{" . $table->IdColumn . "}");
        $links[] = $this->CreateLink("Settings", "UserDomainAddiction", "word705", "xadm", $this->WebId, $this->LangId, "{" . $table->IdColumn . "}");

        $table->SpecialLinks = $links;
        $this->PrepareList($this->GetWord("word188"), $colums, $table);
        $this->SetWordList();
    }

    public function UserDomainDetail() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $this->SetTemplateData("DomainId", $_GET["objectid"]);
        $table = new Table();
        $colums = array("Id", "ShowName", "Identificator");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word199"), "ShowName", TableHeaderFiltrType::$Textbox);
        $header[] = new TableHeader($this->GetWord("word200"), "Identificator", TableHeaderFiltrType::$Textbox);
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $table->ModelName = "UserDomainsItems";
        $table->AddDialog = "./AddUserDomainItem.html";
        $table->ColName = "ShowName";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ScrollClass = "scrollTable1200";
        $links = array();
        $links[] = $this->CreateLink("Settings", "AutoComplecteList", "word721", "xadm", $this->WebId, $this->LangId, "{" . $table->IdColumn . "}");


        $table->SpecialLinks = $links;
        $parent = $_GET["objectid"];

        $userDomain = UserDomains::GetInstance();
        $userDomainList = $userDomain->Select(array("Id", "DomainName"), false, false, true);
        $this->SetTemplateData("UserDomainList", $userDomainList);
        $this->PrepareList($this->GetWord("word198"), $colums, $table, $parent);
        $this->SetWordList();
    }

    public function UserDomainValueList() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $this->SetTemplateData("DomainId", $_GET["objectid"]);
        $domainid = $_GET["objectid"];
        $domain = UserDomains::GetInstance();
        $domainInfo = $domain->GetObjectById($_GET["objectid"]);
        if ($domainInfo["EditValue"] == 0)
            $this->GoToBack();
        $userDomainItem =  UserDomainsItems::GetInstance();
        $items = $userDomainItem->GetUserDomainItemById($domainid);
        $userDomainValue = UserDomainsValues::GetInstance();
        $values = $userDomainValue->GetDomainValueList($domainid);

        $header = array();
        $colums = array();
        $removeColumns = array();
        foreach ($items as $row) {
            if ($row["ShowOnlyDetail"] == 0) {
                $header[] = new TableHeader($row["ShowName"], $row["Identificator"], TableHeaderFiltrType::$None);
                $colums[] = $row["Identificator"];
            } else {
                $removeColumns[] = $row["Identificator"];
            }
        }
        $colums[] = "ObjectId";

        $values = \Utils\ArrayUtils::SortColumns($values, $colums);
        for ($x = 0; $x < count($values); $x++) {
            //$row =$values[$x];
            for ($k = 0; $k < count($removeColumns); $k++) {
                $key = $removeColumns[$k];
                unset($values[$x][$key]);
            }
        }


        $table = new Table();
        $table->Header = $header;
        $table->HideColumns = array("ObjectId");
        $formHtml = $this->GetUserDomain($domainInfo["DomainIdentificator"]);
        $this->SetTemplateData("dataForm", $formHtml);
        $table->ModelName = "UserDomainsValues";
        $table->AddDialog = "./EditItemDomain.html";
        $table->ScrollClass = "scrollTable1200";
        if (!empty($colums))
            $table->ColName = $colums[0];
        $table->IdColumn = "ObjectId";
        $table->ShowImport = FALSE;
        $table->Data = $values;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ShowFiltr = false;
        $table->ShowSort = false;
        $table->DetailFunction = "GetUserDomainDetail";
        $table->AddAction = "SaveUserDomainValue";
        $table->RefreschTable = "UserDomaiReloadList";
        $table->ShowHistoryButton = false;
        $table->DeleteAction = "DeleteValueInUserDomain";
        $table->AceptEmptyData = true;
        $parent = $_GET["objectid"];
        $this->PrepareList($this->GetWord("word198"), $colums, $table, $parent);
    }

    public function UserDomainGroupItem() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $this->SetTemplateData("DomainId", $_GET["objectid"]);
        $domainid = $_GET["objectid"];
        $colums = array("Id", "GroupName");
        $userDomainGroups =  UserDomainsGroups::GetInstance();
        $groups = $userDomainGroups->SelectByCondition("DomainId = $domainid AND Deleted = 0");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word629"), "GroupName", TableHeaderFiltrType::$Textbox);
        $table = new Table();
        $table->Header = $header;
        $table->Data = $groups;
        $this->SetTemplateData("DomainId", $domainid);
        $table->HideColumns = array("Id", "IsSystem", "WebId", "DomainId", "Deleted");
        $table->ModelName = "UserDomainsGroups";
        $table->AddDialog = "./EditUserDomainGroup.html";

        if (!empty($colums))
            $table->ColName = $colums[1];
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        //$table->Data = $values;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ShowFiltr = false;
        $table->ShowSort = false;
        $table->DetailFunction = "GetUserDomainGroupDetail";
        $table->AddAction = "SaveUserDomainGroup";
        $table->RefreschTable = "UserDomainGroupReloadTable";
        $table->ShowHistoryButton = false;
        $table->DeleteAction = "DeleteUserDomainGroupItem";
        $table->AceptEmptyData = true;
        $domainItems = UserDomainsItems::GetInstance();
        $items = $domainItems->GetUserDomainItemById($domainid);
        $this->SetTemplateData("DomainItems", $items);
        $this->PrepareList($this->GetWord("word628"), $colums, $table);
    }

    public function UserDomainAddiction() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $this->SetTemplateData("DomainId", $_GET["objectid"]);
        $domainid = $_GET["objectid"];
        $colums = array("Id", "AddictionName");
        $userDomainAddiction =  UserDomainsAddiction::GetInstance();
        $addiction = $userDomainAddiction->SelectByCondition("DomainId = $domainid AND Deleted = 0");
        //print_r($addiction);
        $header = array();
        $header[] = new TableHeader($this->GetWord("word706"), "AddictionName", TableHeaderFiltrType::$Textbox);
        $table = new Table();
        $table->Header = $header;
        $table->Data = $addiction;
        $this->SetTemplateData("DomainId", $domainid);
        $table->HideColumns = array("Id", "IsDomain1", "DomainId1", "ItemId1", "IsSystem", "WebId", "DomainId", "Deleted", "Item1", "ItemX", "ActionName", "RuleName", "Priority", "ItemXValue", "Item1Value", "ItemIdX", "DomainIdX", "IsDomainX");
        $table->ModelName = "UserDomainsAddiction";
        $table->AddDialog = "./EditUserDomainAddiction.html";

        if (!empty($colums))
            $table->ColName = $colums[1];
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        //$table->Data = $values;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ShowFiltr = false;
        $table->ShowSort = false;
        $table->DetailFunction = "GetUserDomainAddictionDetail";
        $table->AddAction = "SaveUserDomainAddiction";
        $table->RefreschTable = "UserDomainAddictionReloadTable";
        $table->ShowHistoryButton = false;
        $table->DeleteAction = "DeleteUserDomainAddctionItem";
        $table->ShowDelete = true;
        $table->AceptEmptyData = true;
        $domainItems = UserDomainsItems::GetInstance();
        $items = $domainItems->GetUserDomainItemById($domainid);
        $this->SetTemplateData("DomainItems", $items);
        $this->PrepareList($this->GetWord("word705"), $colums, $table);
        $di = $domainItems->GetUserDomainItemById($domainid);
        $di = $this->CretateDomainItemsAddiction($di);
        $this->SetTemplateData("ItemList1", $di);
        $this->SetTemplateData("ItemList2", $di);
    }

    private function CretateDomainItemsAddiction($domainItems, $addName = "") {
        $out = array();
        $di = UserDomainsItems::GetInstance();

        foreach ($domainItems as $row) {
            if ($row["Type"] == "password" || $row["Type"] == "file" || $row["Type"] == "textarea" || $row["Type"] == "html" || $row["Type"] == "range") {
                continue;
            }
            $row["ShowName"] = $addName . " " . $row["ShowName"];


            if ($row["Type"] == "domainData") {
                if ($row["DomainSettings"] == "1n" || $row["DomainSettings"] == "mn") {

                    $userdomain = UserDomains::GetInstance();
                    $udv = UserDomainsValues::GetInstance();
                    $list = $udv->GetDomainValueList($row["Domain"]);
                    $list = $userdomain->GenerateShowName($row["Domain"], $list, $row["ShowName"] . ":");
                    $out = array_merge($out, $list);
                } else {
                    $ar = $di->GetUserDomainItemById($row["Domain"]);
                    $tmp = $addName . "-";
                    $ar = $this->CretateDomainItemsAddiction($ar, $tmp);
                    $out = array_merge($out, $ar);
                }
            } else {
                $out[] = $row;
            }
        }
        return $out;
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
        $addiction = UserDomainsAddiction::GetInstance();
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

    public function GetUserDomainDetail() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $domainValues = UserDomainsValues::GetInstance();
        $data = $domainValues->GetDomainValueByDomainId($_GET["objectid"], $id);
        return $data;
    }

    public function SaveUserDomainValue() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $userValues = UserDomainsValues::GetInstance();
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
        $userDomainItem = UserDomainsItems::GetInstance();
        $items = $userDomainItem->GetUserDomainItemById($domainid);
        $userDomainValue = UserDomainsValues::GetInstance();
        $values = $userDomainValue->GetDomainValueList($domainid, $deleted);
        return $values;
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

    public function DeleteValueInUserDomain() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userDomainValue = UserDomainsValues::GetInstance();
        $userDomainValue->Delete($id);
    }

    public function DeleteUserDomainGroupItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userDomainValue = UserDomainsGroups::GetInstance();
        $userDomainValue->DeleteObject($id);
    }

    public function DeleteUserDomainAddctionItem() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $userDomainValue = UserDomainsAddiction::GetInstance();
        $userDomainValue->DeleteObject($id);
    }

    public function DeveloperTools() {
        $this->SharedView = "";
        $this->SetStateTitle($this->GetWord("word305"));
    }

    public function CopyLang() {
        $this->SharedView = "";
        $this->SetStateTitle($this->GetWord("word506"));
        $lang = Langs::GetInstance();
        $this->SetTemplateData("LangList", $lang->Select());
    }

    public function StartCopyLang() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $source = $ajaxParametrs["SourceLang"];
        $dest = $ajaxParametrs["DestinationLang"];
        $content =  ContentVersion::GetInstance();
        $content->CopyLang($source, $dest);
    }

    public function ExportSettings() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        $files = array();
        $folderName = \Utils\StringUtils::GenerateRandomString();
        \Kernel\Folders::CreateFolder(TEMP_EXPORT_PATH, $folderName);
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

    public function LogView() {
        $this->SharedView = "";
        $this->SetStateTitle($this->GetWord("word595"));
        $errors = Files::ReadFile(ROOT_PATH . "/Log/Errors.log");
        $errors = str_replace("\n", "<br />", $errors);
        $this->SetTemplateData("Errors", $errors);
    }

    public function AddDomainItemToGroup() {
        $params = $_POST["params"];
        $groupSetting = UserDomainsItemsInGroups::GetInstance();
        $groupSetting->SaveItemInGroup($params[0][1], $params);
    }

    public function GetIntemsInDomainGroup() {
        $groupSetting = UserDomainsItemsInGroups::GetInstance();
        return $groupSetting->GetUserItemInGroups($_GET["params"]);
    }

    public function CleanDatabase() {
        $this->SharedView = "";
        $this->SetStateTitle($this->GetWord("word641"));
    }

    public function StartCleanDatabase() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if ($ajaxParametrs["DeleteContent"]) {
            $content =  Content::GetInstance();
            $contentVersion =  ContentVersion::GetInstance();
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

    public function AutoComplecteList() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $itemId = $_GET["objectid"];
        $table = new Table();
        $colums = array("Id", "Value");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word722"), "Value", TableHeaderFiltrType::$Textbox);

        $table->Header = $header;
        $table->HideColumns = array("Id", "Deleted", "IsSysystem", "DomainItemId");

        $table->AddDialog = "./AddUserDomainAutoComplecte.html";
        $table->ColName = "Value";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowExportAllButton = false;
        $table->ModelName = "UserDomainsAutoComplete";
        $table->ShowDeletedItem = false;
        $table->ShowHistoryButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $this->PrepareList($this->GetWord("word721"), $colums, $table, 0, "DomainItemId = $itemId");
        $this->SetTemplateData("DomainItemId", $_GET["objectid"]);
    }

    public function ClearLog() {
        $logPath = LOG_PATH . ERROR_LOG_FILENAME;
        $logCopy = LOG_PATH . \Utils\StringUtils::GenerateRandomString() . ".log";
        copy($logPath, $logCopy);
        unlink($logPath);
    }

    public function DowlandFile() {
        //Files::DowlandFile(SERVER_NAME."Log/Errors.log");
        Files::DowlandFile(SERVER_NAME . "Log/KUXOolX1Tc.log");
    }

    public function SetDefaultWebPriviles() {
        $web = new \Objects\Webs();
        $web->SetDefaultWebPrivileges($_POST["params"]);
    }

}
