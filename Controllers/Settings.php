<?php

namespace Controller;

use Components\Table;
use Model\WebsList;
use Types\TableHeader;
use Model\Langs;
use Model\AdminLangs;
use Utils\Files;
use Model\UserDomains;
use Model\UserDomainsGroups;
use Model\UserDomainsAddiction;

class Settings extends SettingsController {

    public function __construct() {

        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        $this->AddScript("/Scripts/Domain.js");
        $this->SetTemplateData("controllerName", $this->ControllerName);
        $this->SetViewSettings("WebList", array("system", "Administrators"));
        $this->SetViewSettings("CronManager", array("system", "Administrators"));
        $this->SetViewSettings("LangList", array("system", "Administrators"), true);
        $this->SetViewSettings("Words", array("system", "Administrators"));
        $this->SetViewSettings("AdminLangs", array("system", "Administrators"));
        $this->SetViewSettings("Modules", array("system", "Administrators", true));
        $this->SetViewSettings("UserDomain", array("system", "Administrators"), true);
        $this->SetViewSettings("UserDomainDetail", array("system", "Administrators"));
        $this->SetViewSettings("UserDomainValueList", array("system", "Administrators"));
        $this->SetViewSettings("DeveloperTools", array("system", "Administrators"), true, true);
        $this->SetViewSettings("CopyLang", array("system", "Administrators"));
        $this->SetViewSettings("LogView", array("system", "Administrators"));
        $this->SetViewSettings("UserDomainGroupItem", array("system", "Administrators"));
        $this->SetViewSettings("CleanDatabase", array("system", "Administrators"));
        $this->SetViewSettings("UserDomainAddiction", array("system", "Administrators"));
        $this->SetViewSettings("AutoComplecteList", array("system", "Administrators"));
        $this->SetViewSettings("MailingContacts", array("system", "Administrators"));
        $this->SetCommnadFunction("ClearLog", array("system", "Administrators"));
        $this->SetCommnadFunction("DowlandFile", array("system", "Administrators"));
    }

    public function CronManager() {


        $table = new Table();
        $cron = \Model\Crons::GetInstance();
        $data = $cron->Select();
        $colums = array("Id", "CronName");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word857"), "CronName");
        $table->Header = $header;
        $table->HideColumns = array("Id", "Deleted", "IsSystem", "CronUrl", "IsActive", "IsRun", "RunMode", "LastRun");
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
        $header[] = new TableHeader($this->GetWord("word74"), "WebName");
        $table->Header = $header;
        $table->HideColumns = array("Id", "Deleted", "IsSystem", "SmallHeight", "SmallWidth", "MediumHeight", "MediumWidth", "BigHeight", "BigWidth", "UserGroupId", "WebPrivileges", "AdminUserActive", "UserEmailActivate", "EmailUserLogin", "BlockSendEmails", "BlockAdmin", "UseHttps", "WebIpRestrictionAll", "WebIpRestrictionAceptIp", "WebIpRestrictionBlockIp", "WebIpAddress", "AdminIpRestrictionAll", "AdminIpRestrictionAceptIp", "AdminIpRestrictionBlockIp", "AdminIpAddress", "GenerateAjaxLink", "DefaultFramework", "AfterLoginUrl", "AfterLoginAction", "SendInfoEmailToAdmin", "AdminInfoEmail", "AdmiInfoMailId", "SendInfoEmailToUser", "UserInfoEmailFrom", "UserInfoMailId", "CookiesAccept", "RobotsTxt", "SiteMapStart", "SiteMapEnd", "SiteMapItemUrl", "SiteMapItemImage", "SiteMapItemVideo", "SiteMapItemStart", "SiteMapItemEnd");
        $table->Data = $data;
        $table->ModelName = "Webs";
        $table->ViewName = "WEBSLIST";
        $table->ViewNameClass = "WebsList";
        $table->AddDialog = "./AddWeb.html";
        $table->ColName = "WebName";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowCopyItem = FALSE;
        $table->ShowCopySelected = FALSE;
        $table->ShowHistoryButton = false;
        $table->ShowExportAllButton = false;
        $table->AddScriptAction = "SavePrivileges();";
        $content = new \Objects\Content();
        $mailList = $content->GetMailList(self::$UserGroupId, $this->LangId, true, "", "Name ASC");
        $this->SetTemplateData("MailListAdmin", $mailList);

        $this->PrepareList($this->GetWord("word73"), $colums, $table);
        $userGroup = new \Objects\Users();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
    }

    public function MailingContacts() {
        $this->SetLeftMenu("contentMenu", "contentMenuMailing");
        $colums = array("Id", "Email");
        $header = array();
        $header[] = new TableHeader("Email", "Email");
        $table = new Table();
        $table->Header = $header;
        $table->HideColumns = array("Id");
        $table->ModelName = "MailingContacts";
        $table->AddDialog = "./AddMailContact.html";
        $table->ColName = "Email";
        $table->IdColumn = "Id";
        $table->ShowImport = FALSE;
        $table->ShowExportAllButton = false;
        $table->ShowCopyItem = false;
        $table->ShowCopySelected = false;
        $table->ShowHistoryButton = false;

        $table->ScrollClass = "scrollTable1200";
        $this->PrepareList($this->GetWord("word551"), $colums, $table);
        $ud = new \Objects\UserDomains();
        $info = $ud->GetDomainInfo("Mailinggroups");
        $values = $ud->GetDomainValueList($info["Id"], false);
        $this->SetTemplateData("MailingGroups", $values);
    }

    public function LangList() {
        $colums = array("Id", "LangName");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word76"), "LangName");
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
        $header[] = new TableHeader($this->GetWord("word78"), "GroupName");
        $adminLang = AdminLangs::GetInstance();
        $adminLangData = $adminLang->Select();
        $colums = array("Id", "GroupName");
        foreach ($adminLangData as $row) {
            $colums[] = "Word" . $row->LangIdentificator;
            $header[] = new TableHeader($row->LangName, "Word" . $row->LangIdentificator);
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
        $header[] = new TableHeader($this->GetWord("word80"), "LangName");
        $header[] = new TableHeader($this->GetWord("word81"), "LangIdentificator");
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
        $header[] = new TableHeader($this->GetWord("word153"), "ModuleName");
        $header[] = new TableHeader($this->GetWord("word154"), "ModuleControler");
        $header[] = new TableHeader($this->GetWord("word155"), "ModuleView");
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
        $header[] = new TableHeader($this->GetWord("word189"), "DomainName");
        $header[] = new TableHeader($this->GetWord("word190"), "DomainIdentificator");
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


        $this->SetTemplateData("DomainId", $_GET["objectid"]);

        $table = new Table();
        $colums = array("Id", "ShowName", "Identificator");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word199"), "ShowName");
        $header[] = new TableHeader($this->GetWord("word200"), "Identificator");
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
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
    }

    public function UserDomainValueList() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $this->SetTemplateData("DomainId", $_GET["objectid"]);
        $domainid = $_GET["objectid"];
        $domain = UserDomains::GetInstance();
        $domainInfo = $domain->GetObjectById($_GET["objectid"]);
        if ($domainInfo["EditValue"] == 0)
            $this->GoToBack();
        $userDomainItem = new \Objects\UserDomains();
        $items = $userDomainItem->GetUserDomainItemById($domainid);
        $values = $userDomainItem->GetDomainValueList($domainid);

        $header = array();
        $colums = array();
        $removeColumns = array();
        foreach ($items as $row) {
            if ($row["ShowOnlyDetail"] == 0) {
                $header[] = new TableHeader($row["ShowName"], $row["Identificator"]);
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
        $userDomainGroups = UserDomainsGroups::GetInstance();
        $groups = $userDomainGroups->SelectByCondition("DomainId = $domainid AND Deleted = 0");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word629"), "GroupName");
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
        $domainItems = new \Objects\UserDomains();
        $items = $domainItems->GetUserDomainItemById($domainid);
        $this->SetTemplateData("DomainItems", $items);
        $this->PrepareList($this->GetWord("word628"), $colums, $table);
    }

    public function UserDomainAddiction() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $this->SetTemplateData("DomainId", $_GET["objectid"]);
        $domainid = $_GET["objectid"];
        $colums = array("Id", "AddictionName");
        $userDomainAddiction = UserDomainsAddiction::GetInstance();
        $addiction = $userDomainAddiction->SelectByCondition("DomainId = $domainid AND Deleted = 0");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word706"), "AddictionName");
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
        $domainItems = new \Objects\UserDomains();
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
        $di = new \Objects\UserDomains();

        foreach ($domainItems as $row) {
            if ($row["Type"] == "password" || $row["Type"] == "file" || $row["Type"] == "textarea" || $row["Type"] == "html" || $row["Type"] == "range") {
                continue;
            }
            $row["ShowName"] = $addName . " " . $row["ShowName"];


            if ($row["Type"] == "domainData") {
                if ($row["DomainSettings"] == "1n" || $row["DomainSettings"] == "mn") {
                    $list = $di->GetDomainValueList($row["Domain"]);
                    $list = $di->GenerateShowName($row["Domain"], $list, $row["ShowName"] . ":");
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

    public function LogView() {
        $this->SharedView = "";
        $this->SetStateTitle($this->GetWord("word595"));
        $errors = Files::ReadFile(ROOT_PATH . "/Log/Errors.log");
        $errors = htmlentities($errors);
        $errors = str_replace("\n", "<br />", $errors);

        $this->SetTemplateData("Errors", $errors);
    }

    public function CleanDatabase() {
        $this->SharedView = "";
        $this->SetStateTitle($this->GetWord("word641"));
    }

    public function AutoComplecteList() {
        $this->SetLeftMenu("settingsmenu", "settingsmenuUserDomain");
        $itemId = $_GET["objectid"];
        $table = new Table();
        $colums = array("Id", "Value");
        $header = array();
        $header[] = new TableHeader($this->GetWord("word722"), "Value");

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
        if (Files::FileExists($logPath)) {
            $logCopy = LOG_PATH . \Utils\StringUtils::GenerateRandomString() . ".log";
            copy($logPath, $logCopy);
            unlink($logPath);
        }
        $this->Referesch();
    }   

    public function DowlandFile() {
        Files::DowlandFile(SERVER_NAME . "Log/Errors.log");
    }

}
