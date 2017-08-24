<?php

namespace Controller;

use Model\Langs;
use Model\UserDomains;
use Utils\ArrayUtils;
use Components\HtmlEditor;
use Utils\StringUtils;
use Types\ContentTypes;

class WebEdit extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        $this->SetTemplateData("controllerName", $this->ControllerName);
        $this->AddScript("/Scripts/ContentTree.js");
        $this->SetViewSettings("Tree", array("system", "Administrators"), true, true);
        $this->SetViewSettings("TemplateEditor", array("system", "Administrators"), true, true);
        $this->SetViewSettings("Detail", array("system", "Administrators"));
        $this->SetViewSettings("TemplateDetail", array("system", "Administrators"));
        $this->SetViewSettings("CssList", array("system", "Administrators"), true, true);
        $this->SetViewSettings("FormsList", array("system", "Administrators"), true, true);
        $this->SetViewSettings("MailList", array("system", "Administrators"), true, true);
        $this->SetViewSettings("CssEditor", array("system", "Administrators"));
        $this->SetViewSettings("FileFolder", array("system", "Administrators"));
        $this->SetViewSettings("FileUploader", array("system", "Administrators"));
        $this->SetViewSettings("Discusion", array("system", "Administrators"));
        $this->SetViewSettings("JsList", array("system", "Administrators"), true, true);
        $this->SetViewSettings("JsEditor", array("system", "Administrators"));
        $this->SetViewSettings("FormEditor", array("system", "Administrators"));
        $this->SetViewSettings("MailEditor", array("system", "Administrators"));
        $this->SetViewSettings("SendMailList", array("system", "Administrators"), true, true);
        $this->SetViewSettings("Trash", array("system", "Administrators"), true, true);
        $this->SetViewSettings("NoPublishItems", array("system", "Administrators"), true, true);
        $this->SetViewSettings("Mailing", array("system", "Administrators"), true, true);
        $this->SetViewSettings("MailingDetail", array("system", "Administrators"));
        $this->SetViewSettings("DataSource", array("system", "Administrators"), true, true);
        $this->SetViewSettings("DataSourceDetail", array("system", "Administrators"));
        $this->SetViewSettings("InquryList", array("system", "Administrators"), true, true);
        $this->SetViewSettings("InqueryDetail", array("system", "Administrators"));
        $this->SetViewSettings("FileManager", array("system", "Administrators"), true, true);
        $this->SetViewSettings("DiscusionList", array("system", "Administrators"), true, true);
    }

    public function Mailing() {
        $this->SetStateTitle($this->GetWord("word544"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeMailing());
    }

    public function Tree() {

        $this->SetStateTitle($this->GetWord("word135"));
        $content = new \Objects\Content();
        $tree = $content->GetTree($_GET["langid"]);
        $html = $content->CreateHtml($tree);
        $this->SetTemplateData("tree", $html);
    }

    public function TemplateEditor() {
        $this->SetStateTitle($this->GetWord("word138"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTree());
    }

    public function CssList() {
        $this->SetStateTitle($this->GetWord("word287"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeCss());
    }

    public function FormsList() {
        $this->SetStateTitle($this->GetWord("word470"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeForms());
    }

    public function MailList() {
        $this->SetStateTitle($this->GetWord("word473"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeMail());
    }

    public function SendMailList() {
        $this->SetStateTitle($this->GetWord("word479"));
        $this->SetTemplateData("mails", $this->CreateTreeSendMail());
    }

    public function Trash() {
        $this->SetStateTitle($this->GetWord("word499"));
        $this->SetTemplateData("trash", $this->CreateTreeTrash());
    }

    public function JsList() {
        $this->SetStateTitle($this->GetWord("word467"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeJs());
    }

    public function CreateTreeCss($search = "") {
        return \Utils\TreeUtils::CreateTreeCss(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeDiscusion($search = "") {
        return \Utils\TreeUtils::CreateTreeDiscusion(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeForms($search = "") {
        return \Utils\TreeUtils::CreateTreeForms(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeMailing($search = "") {
        return \Utils\TreeUtils::CreateTreeMailing(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeMail($search = "") {
        return \Utils\TreeUtils::CreateTreeMail(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeSendMail() {
        $content = new \Objects\Content();
        $data = $content->GetSendMailList($this->WebId, $this->LangId);

        $header = array();
        $header["EmailText"] = $this->GetWord("word512");
        $header["EmailTo"] = $this->GetWord("word513");
        $header["EmailFrom"] = $this->GetWord("word514");
        $header["Time"] = $this->GetWord("word515");
        $header["IP"] = $this->GetWord("word516");
        return ArrayUtils::XmlToHtmlTable($data, "Data", array(), $header, true, "SendEmail", false, "Id", "scrollTable1200");
    }

    public function CreateTreeTrash() {
        $content = new \Objects\Content();
        $data = $content->GetDeletedObjects($this->WebId, $this->LangId);
        return $data;
    }

    public function CreateTreeJs($search = "") {
        return \Utils\TreeUtils::CreateTreeJs(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function FileFolder() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuFileRepository");
        $this->SetUserGroupList();
        $content = new \Objects\Content();
        $id = $this->GetObjectId();



        if ($content->GetContentType($id) == ContentTypes::FILEUPLOAD) {
            $this->GoToState("WebEdit", "FileUploader", "xadm", $this->WebId, $this->LangId, $id);
        }
        $data = $content->GetFileFolderDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data)) {
            $data = $content->GetFileFolderDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        }
        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }
        $mainData = array();
        if (empty($data[0]))
            $mainData = array("Name" => "", "SeoUrl" => "", "Identificator" => "", "ActiveTo" => "", "ActiveFrom" => "", "AvailableOverSeoUrl" => 1, "NoIncludeSearch" => 0, "TemplateId" => "", "Data" => "");
        else {
            $mainData = $data[0];
        }
        $this->UserGroupList($data);

        $this->SetTemplateData("Name", $mainData["Name"]);
        $this->SetTemplateData("SeoUrl", $mainData["SeoUrl"]);
        $this->SetTemplateData("Identificator", $mainData["Identificator"]);
        $this->SetTemplateData("ActiveTo", $mainData["ActiveTo"]);
        $this->SetTemplateData("ActiveFrom", $mainData["ActiveFrom"]);
        $this->SetTemplateData("AvailableOverSeoUrl", $mainData["AvailableOverSeoUrl"] == 1 ? 'checked= "checked"' : "" );
        $this->SetTemplateData("NoIncludeSearch", $mainData["NoIncludeSearch"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("TemplateId", $mainData["TemplateId"]);
        $this->SetTemplateData("Data", str_replace("\n", "", $mainData["Data"]));
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
    }

    public function Discusion() {
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuDiscusionList");
        $userGroup = new \Objects\Users();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
        $content = new \Objects\Content();
        if (empty($_GET["id"])) {
            $this->SetTemplateData("id", 0);
            $this->SetTemplateData("IsNew", TRUE);
        }
        $id = 0;
        if (empty($_GET["objectid"])) {
            
        } else {
            $id = $_GET["objectid"];
        }
        $data = $content->GetDiscusionDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId);

        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }
        $userGroupList = $this->PreparePrivilegesList($data, "SSValue", "SSGroupId", "SSSecurityType");
        $this->SetTemplateData("GroupList", $userGroupList);

        $discusion = new \Components\Discusion();
        $discusion->DiscusionMode = \Types\DiscusionsMode::ADMINMODE;
        $discusion->LoadDiscusionFormSeoUrl = false;
        $discusion->DiscusionId = $id;
        $htmlDiscusion = $discusion->LoadComponent();
        $this->SetTemplateData("Discusion", $htmlDiscusion);
        $mainData = array();
        if (empty($data[0]))
            $mainData = array("Name" => "");
        else {
            $mainData = $data[0];
        }
        $this->SetTemplateData("Name", $mainData["Name"]);

        $this->SetTemplateData("objectid", $id);
    }

    public function Detail() {
        $this->AddScript("/node_modules/tinymce/tinymce.min.js");
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuWeb");
        $id = $this->GetObjectId();
        $content = new \Objects\Content();

        $data = $content->GetUserItemDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        $createLangVersion = false;
        $lastEditLang = $this->GetLastEditLangVersion(false);

        if (empty($data)) {

            $data = $content->GetUserItemDetail($id, self::$User->GetUserGroupId(), $this->WebId, $lastEditLang);
            if (!empty($data)) {
                $createLangVersion = true;
            }
        }
        if (($id > 0 && empty($data)) || ($id == 0 && !$this->CanWriteParent($_GET["parentid"]))) {
            $this->GoToBack();
        }

        $mainData = array();
        if (empty($data[0])) {
            $nextPosition = $this->GetLastPosition("UserItem", $_GET["parentid"]);
            $mainData = array("Name" => "", "SeoUrl" => "", "Identificator" => "", "ActiveTo" => "", "ActiveFrom" => "", "AvailableOverSeoUrl" => 1, "NoIncludeSearch" => 0, "TemplateId" => "", "Data" => "", "ButtonSendForm" => "", "SendAdminEmail" => "", "NoChild" => "", "UseTemplateInChild" => "", "ChildTemplate" => "", "CopyDataToChild" => "", "ActivatePager" => 0, "FirstItemLoadPager" => "", "NextItemLoadPager" => "", "NoLoadSubItems" => 0, "DiscusionId" => 0, "SaveToCache" => 1, "Sort" => $nextPosition, "SortRule" => "Position");
        } else {
            $mainData = $data[0];
        }
        if ($id == 0) {
            $parentDetail = $content->GetUserItemDetail($_GET["parentid"], self::$UserGroupId, $this->WebId, $this->LangId);
            if (!empty($parentDetail)) {
                $parentDetailRow = $parentDetail[0];
                if ($parentDetailRow["UseTemplateInChild"] == 1) {
                    if (empty($parentDetailRow["ChildTemplateId"]))
                        $mainData["TemplateId"] = $parentDetailRow["TemplateId"];
                    else
                        $mainData["TemplateId"] = $parentDetailRow["ChildTemplateId"];
                }
                if ($parentDetailRow["CopyDataToChild"] == 1) {
                    $mainData = $parentDetailRow;
                    $mainData["SeoUrl"] = "";
                    $mainData["Identificator"] = "";
                }
            }
        }

        $this->SetTemplateData("Name", $mainData["Name"]);
        $this->SetTemplateData("Sort", $mainData["Sort"]);
        $this->SetTemplateData("SeoUrl", $mainData["SeoUrl"]);
        $this->SetTemplateData("Identificator", $mainData["Identificator"]);
        $this->SetTemplateData("ActiveTo", $mainData["ActiveTo"]);
        $this->SetTemplateData("ActiveFrom", $mainData["ActiveFrom"]);
        $this->SetTemplateData("AvailableOverSeoUrl", $mainData["AvailableOverSeoUrl"] == 1 ? 'checked= "checked"' : "" );
        $this->SetTemplateData("NoIncludeSearch", $mainData["NoIncludeSearch"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("NoChild", $mainData["NoChild"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("TemplateId", $mainData["TemplateId"]);
        $this->SetTemplateData("UseTemplateInChild", $mainData["UseTemplateInChild"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("CopyDataToChild", $mainData["CopyDataToChild"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("NoLoadSubitems", $mainData["NoLoadSubItems"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("ActivatePager", $mainData["ActivatePager"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("SaveToCache", $mainData["SaveToCache"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("FirstItemLoadPager", $mainData["FirstItemLoadPager"]);
        $this->SetTemplateData("NextItemLoadPager", $mainData["NextItemLoadPager"]);
        $this->SetTemplateData("Data", str_replace("'", '"', str_replace("\n", "", $mainData["Data"])));
        $this->SetTemplateData("GalleryId", !empty($mainData["GalleryId"]) ? $mainData["GalleryId"] : 0);
        $this->SetTemplateData("DiscusionSettings", !empty($mainData["DiscusionSettings"]) ? $mainData["DiscusionSettings"] : 0);
        $this->SetTemplateData("GallerySettings", !empty($mainData["GallerySettings"]) ? $mainData["GallerySettings"] : 0);
        $this->SetTemplateData("DiscusionId", empty($mainData["DiscusionId"]) ? 0 : $mainData["DiscusionId"]);

        $this->SetTemplateData("SortRulePosition", $mainData["SortRule"] == "Postion" ? "selected =\"selected\"" : "");
        $this->SetTemplateData("SortRuleName", $mainData["SortRule"] == "Name" ? "selected =\"selected\"" : "");
        $this->SetTemplateData("SortRuleDate", $mainData["SortRule"] == "Date" ? "selected =\"selected\"" : "");




        if ($createLangVersion) {
            $this->AutoCreateTemplate($mainData["TemplateId"], $lastEditLang);
            $this->CreateLangParent($mainData["ParentId"], $lastEditLang);
            $this->CreateConnectionFormLang($mainData["FormId"], $lastEditLang);
            $this->CreateConnectionGalleryLang($id, $lastEditLang);
        }
        $this->SetLangList($content, $id);
        $this->SetFormList(empty($mainData["FormId"]) ? 0 : $mainData["FormId"]);
        $this->SetInqueryList(empty($mainData["Inquery"]) ? 0 : $mainData["Inquery"]);
        $this->SetDiscusionList(empty($mainData["DiscusionId"]) ? 0 : $mainData["DiscusionId"]);
        $this->SetHistoryList($id);
        $this->SetUserGroupList();
        $this->UserGroupList($data);
        //$this->SetDiscusion(empty($mainData["DiscusionSettings"])? 0: $mainData["DiscusionSettings"]);
        $this->SetGallerySettigns(empty($mainData["GallerySettings"]) ? 0 : $mainData["GallerySettings"]);
        $this->SetTemplateList(empty($mainData["TemplateId"]) ? 0 : $mainData["TemplateId"], empty($mainData["ChildTemplateId"]) ? 0 : $mainData["ChildTemplateId"]);
    }

    public function FormEditor() {
        $this->ExitQuestion = true;

        $this->SetStateTitle($this->GetWord("word236"));

        $this->SetLeftMenu("contentMenu", "contentMenuFormsList");
        $this->SetUserGroupList();
        $content = new \Objects\Content();
        $id = $this->GetObjectId();


        $createLangVersion = false;
        $lastEditLang = $this->GetLastEditLangVersion();
        $data = $content->GetFormDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data)) {
            $createLangVersion = true;
            $data = $content->GetFormDetail($id, self::$User->GetUserGroupId(), $this->WebId, $lastEditLang);
        }
        if (($id > 0 && empty($data)) || ($id == 0 && !$this->CanWriteParent($_GET["parentid"]))) {
            $this->GoToBack();
        }


        $mainData = array();
        if (empty($data[0]))
            $mainData = array("Name" => "", "SeoUrl" => "", "Identificator" => "", "ActiveTo" => "", "ActiveFrom" => "", "AvailableOverSeoUrl" => 1, "NoIncludeSearch" => 0, "TemplateId" => "", "Data" => "");
        else {
            $mainData = $data[0];
        }

        $userGroupList = $this->PreparePrivilegesList($data, "SSValue", "SSGroupId", "SSSecurityType");
        $this->SetPrivileges($userGroupList);
        $this->SetTemplateData("GroupList", $userGroupList);
        $mailList = $content->GetMailList(self::$UserGroupId, $this->LangId, true, "", "Name ASC");
        $this->SetTemplateData("MailListAdmin", $mailList);
        $this->SetTemplateData("MailListUser", $mailList);
        $templateListPDF = $content->GetTemplateList(self::$UserGroupId, $this->LangId, true, false, "", "Name ASC");
        $this->SetTemplateData("TemplatePDF", $templateListPDF);
        $this->SetTemplateList($mainData["TemplateId"], 0);

        if ($createLangVersion) {
            $this->AutoCreateTemplate($mainData["TemplateId"], $lastEditLang);
        }
        $this->SetTemplateData("Name", $mainData["Name"]);
        $this->SetTemplateData("SeoUrl", $mainData["SeoUrl"]);
        $this->SetTemplateData("Identificator", $mainData["Identificator"]);
        $this->SetTemplateData("ActiveTo", $mainData["ActiveTo"]);
        $this->SetTemplateData("ActiveFrom", $mainData["ActiveFrom"]);
        $this->SetTemplateData("AvailableOverSeoUrl", $mainData["AvailableOverSeoUrl"] == 1 ? 'checked= "checked"' : "" );
        $this->SetTemplateData("NoIncludeSearch", $mainData["NoIncludeSearch"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("TemplateId", $mainData["TemplateId"]);
        $this->SetTemplateData("Data", str_replace("\n", "", $mainData["Data"]));
        $form = new \Utils\Forms();
        $templateId = $mainData["TemplateId"];
        $htmlFormStatistic = $form->GetFormStatistic($id, $templateId);
        $this->SetTemplateData("FormStatistic", $htmlFormStatistic);
        $this->SetLangList($content, $id);

        $this->SetHistoryList($id);
    }

    public function MailEditor() {
        $this->ExitQuestion = true;
        $this->AddScript("/node_modules/tinymce/tinymce.min.js");
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuMailList");
        $userGroup = new \Objects\Users();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
        $content = new \Objects\Content();
        $id = $this->GetObjectId();
        $data = $content->GetMailDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data)) {
            $data = $content->GetMailDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        }

        if (($id > 0 && empty($data)) || ($id == 0 && !$this->CanWriteParent($_GET["parentid"]))) {
            $this->GoToBack();
        }

        $mainData = array();
        if (empty($data[0]))
            $mainData = array("Name" => "", "SeoUrl" => "", "Identificator" => "", "ActiveTo" => "", "ActiveFrom" => "", "AvailableOverSeoUrl" => 1, "NoIncludeSearch" => 0, "TemplateId" => "", "Data" => "");
        else {
            $mainData = $data[0];
        }

        $this->UserGroupList($data);

        $this->SetTemplateData("Name", $mainData["Name"]);
        $this->SetTemplateData("SeoUrl", $mainData["SeoUrl"]);
        $this->SetTemplateData("Identificator", $mainData["Identificator"]);
        $this->SetTemplateData("ActiveTo", $mainData["ActiveTo"]);
        $this->SetTemplateData("ActiveFrom", $mainData["ActiveFrom"]);
        $this->SetTemplateData("AvailableOverSeoUrl", $mainData["AvailableOverSeoUrl"] == 1 ? 'checked= "checked"' : "" );
        $this->SetTemplateData("NoIncludeSearch", $mainData["NoIncludeSearch"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("TemplateId", $mainData["TemplateId"]);
        $data = str_replace("\n", "", $mainData["Data"]);
        $this->SetTemplateData("Data", $data);

        $htmlEditor = new HtmlEditor();
        $htmlEditor->HtmlEditorId = "EmailText";
        $htmlEditor->Html = $data;
        $this->SetTemplateData("HtmlEditor", $htmlEditor->LoadComponent());

        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
    }

    public function CssEditor() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word288"));
        $this->joinCodeMirror();
        /* $this->AddStyle("/Styles/show-hint.css");
          $this->AddStyle("/Styles/codemirror.css");
          $this->AddScript("/Scripts/ExternalApi/codemirror.js");
          $this->AddScript("/Scripts/ExternalApi/show-hint.js");
          $this->AddScript("/Scripts/ExternalApi/xml-hint.js");
          $this->AddScript("/Scripts/ExternalApi/html-hint.js");
          $this->AddScript("/Scripts/ExternalApi/xml/xml.js");
          $this->AddScript("/Scripts/ExternalApi/javascript/javascript.js");
          $this->AddScript("/Scripts/ExternalApi/css/css.js");
          $this->AddScript("/Scripts/ExternalApi/htmlmixed/htmlmixed.js");
          $this->AddScript("/Scripts/ExternalApi/show-hint.js");
          $this->AddScript("/Scripts/ExternalApi/css-hint.js"); */
        $this->SetLeftMenu("contentMenu", "contentMenuCss");
        $id = $this->GetObjectId();
        $content = new \Objects\Content();
        $data = $content->GetCssDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data))
            $data = $content->GetCssDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        $domainId = 0;
        $templateId = 0;
        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }

        $ar = array();
        if (empty($data))
            $ar = array("Name" => "", "Data" => "");
        else {
            $ar = (array) $data[0];
        }
        $this->UserGroupList($data);
        //ArrayUtils::
        $this->SetTemplateDataArray($ar);
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
    }

    public function JsEditor() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word496"));
        $this->SetLeftMenu("contentMenu", "contentMenuJs");
        $this->joinCodeMirror();
        /* $this->AddStyle("/Styles/codemirror.css");
          $this->AddStyle("/Styles/show-hint.css");
          $this->AddScript("/Scripts/ExternalApi/codemirror.js");
          $this->AddScript("/Scripts/ExternalApi/edit/matchbrackets.js");
          $this->AddScript("/Scripts/ExternalApi/comment/continuecomment.js");
          $this->AddScript("/Scripts/ExternalApi/comment/comment.js");
          $this->AddScript("/Scripts/ExternalApi/javascript.js");
          $this->AddScript("/Scripts/ExternalApi/javascript-hint.js"); */
        $id = $this->GetObjectId();
        $content = new \Objects\Content();
        $data = $content->GetJsDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data)) {
            $data = $content->GetJsDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        }
        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }

        $ar = array();
        if (empty($data))
            $ar = array("Name" => "", "Data" => "", "Sort" => "");
        else {
            $ar = (array) $data[0];
        }
        $this->UserGroupList($data);
        $ar["Data"] = htmlentities($ar["Data"]);
        $this->SetTemplateDataArray($ar);
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
    }

    public function TemplateDetail() {
        $this->ExitQuestion = true;
        $this->joinCodeMirror();
        /* $this->AddStyle("/Styles/codemirror.css");
          $this->AddStyle("/Styles/show-hint.css");
          $this->AddScript("/Scripts/ExternalApi/codemirror.js");
          $this->AddScript("/Scripts/ExternalApi/show-hint.js");
          $this->AddScript("/Scripts/ExternalApi/xml-hint.js");
          $this->AddScript("/Scripts/ExternalApi/html-hint.js");
          $this->AddScript("/Scripts/ExternalApi/xml/xml.js");
          $this->AddScript("/Scripts/ExternalApi/javascript/javascript.js");
          $this->AddScript("/Scripts/ExternalApi/css/css.js");
          $this->AddScript("/Scripts/ExternalApi/htmlmixed/htmlmixed.js"); */
        $this->SetStateTitle($this->GetWord("word269"));
        $this->SetLeftMenu("contentMenu", "contentMenuTemplate");

        $id = $this->GetObjectId();
        $content = new \Objects\Content();
        $data = $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId, $id, $this->GetVersionId());
        if (empty($data)) {
            $data = $content->GetTemplateDetail(self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $id);
        }
        $createLangVersion = false;
        $lastEditLang = $this->GetLastEditLangVersion();
        if (empty($data)) {
            $createLangVersion = true;
            $data = $content->GetTemplateDetail(self::$User->GetUserGroupId(), $this->WebId, $lastEditLang, $id);
        }
        $domainId = 0;
        $templateId = 0;
        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }

        $ar = array();
        if (empty($data))
            $ar = array("Name" => "", "Identificator" => "", "Data" => "", "Header" => "", "ContentSettings" => "");
        else {
            $ar = (array) $data[0];

            $ar["Data"] = htmlentities($ar["Data"]);
            $domainId = $ar["DomainId"];
            $templateId = $ar["TemplateId"];
        }
        if ($createLangVersion) {
            $this->AutoCreateTemplate($templateId, $lastEditLang);
        }
        $this->SetTemplateDataArray($ar);
        $this->UserGroupList($data);
        $this->SetTemplateList($templateId, 0);

        $userDomains = UserDomains::GetInstance();
        $domainList = $userDomains->SelectByCondition();
        $domainList = $this->ReplaceHtmlWord($domainList);
        $domainList = ArrayUtils::SortArray($domainList, "DomainName", SORT_ASC);
        foreach ($domainList as $row) {
            $row["Selected"] = "";
            if ($row["Id"] == $domainId) {
                $row["Selected"] = 'selected = \"selected\"';
            }
        }
        $this->SetTemplateData("DomainList", $domainList);
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
    }

    private function PreparePrivilegesList($data, $securityValue, $groupId, $securityType) {

        $ug = new \Objects\Users();
        $groupList = $ug->GetUserGroups(array("system"));
        if (empty($data)) {
            $web = \Model\Webs::GetInstance();
            $web->GetObjectById($this->WebId, true, array("WebPrivileges"));
            $ar = ArrayUtils::XmlToArray($web->WebPrivileges);
            $data = $ar["item"];

            foreach ($groupList as $row) {
                $row["canread_checked"] = false;
                $row["canwrite_checked"] = false;
                $row["candelete_checked"] = false;
                $row["canpublish_checked"] = false;
                $row["canchangeprivileges_checked"] = false;
                $row["canread"] = false;
                $row["canwrite"] = false;
                $row["candelete"] = false;
                $row["canpublish"] = false;
                $row["canchangeprivileges"] = false;
                foreach ($data as $drow) {

                    if ($row["Id"] == $drow["UserGroup"]) {

                        $rowname = strtolower($drow["PrivilegesName"]);
                        $row[$rowname . "_checked"] = $drow["Value"] == 1 ? "checked =  'checked'" : "";
                        $row[$rowname] = $drow["Value"] == "true" ? TRUE : FALSE;
                    }
                }
            }
            return $groupList;
        }
        foreach ($groupList as $row) {
            $row["canread_checked"] = false;
            $row["canwrite_checked"] = false;
            $row["candelete_checked"] = false;
            $row["canpublish_checked"] = false;
            $row["canchangeprivileges_checked"] = false;
            $row["canread"] = false;
            $row["canwrite"] = false;
            $row["candelete"] = false;
            $row["canpublish"] = false;
            $row["canchangeprivileges"] = false;

            foreach ($data as $drow) {
                if (!empty($drow[$groupId])) {
                    if ($row["Id"] == $drow[$groupId]) {
                        $rowname = strtolower($drow[$securityType]);
                        $row[$rowname . "_checked"] = $drow[$securityValue] == 1 ? "checked =  'checked'" : "";
                        $row[$rowname] = $drow[$securityValue] == 1 ? TRUE : FALSE;
                    }
                }
            }
        }
        return $groupList;
    }

    public function FileUploader() {
        $this->ExitQuestion = true;
        $isNew = false;
        $this->SetLeftMenu("contentMenu", "contentMenuFileRepository");
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetUserGroupList();
        $content = new \Objects\Content();
        $id = $this->GetObjectId();
        $data = $content->GetFileFolderDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data)) {
            $data = $content->GetFileFolderDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion(false));
        }
        //print_r($data);


        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }
        $mainData = array();
        if (empty($data[0]))
            $mainData = array("Name" => "", "SeoUrl" => "", "Identificator" => "", "ActiveTo" => "", "ActiveFrom" => "", "AvailableOverSeoUrl" => 1, "NoIncludeSearch" => 0, "TemplateId" => "", "Data" => "");
        else {
            $mainData = $data[0];
        }
        $this->UserGroupList($data);

        $this->SetTemplateData("Name", $mainData["Name"]);
        $this->SetTemplateData("SeoUrl", $mainData["SeoUrl"]);
        $this->SetTemplateData("Identificator", $mainData["Identificator"]);
        $this->SetTemplateData("ActiveTo", $mainData["ActiveTo"]);
        $this->SetTemplateData("ActiveFrom", $mainData["ActiveFrom"]);
        $this->SetTemplateData("AvailableOverSeoUrl", $mainData["AvailableOverSeoUrl"] == 1 ? 'checked= "checked"' : "" );
        $this->SetTemplateData("NoIncludeSearch", $mainData["NoIncludeSearch"] == 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("TemplateId", $mainData["TemplateId"]);
        $this->SetTemplateData("Data", str_replace("\n", "", $mainData["Data"]));
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
    }

    private function GroupHasPrivileges($privilegesList, $privilegesName, $groupId) {
        if (empty($privilegesList) || self::$User->IsSystemUser())
            return TRUE;

        foreach ($privilegesList as $row) {
            if ($row["Id"] == $groupId) {
                return $row[$privilegesName];
            }
        }
    }

    private function SetPrivileges($privilegesList) {
        $this->SetTemplateData("CanWrite", $this->GroupHasPrivileges($privilegesList, "canwrite", self::$UserGroupId));
        $this->SetTemplateData("CanPublish", $this->GroupHasPrivileges($privilegesList, "canpublish", self::$UserGroupId));
        $this->SetTemplateData("CanChangePrivileges", $this->GroupHasPrivileges($privilegesList, "canchangeprivileges", self::$UserGroupId));
    }

    private function CanWriteParent($parentId) {
        $contentSecurity = new \Objects\Content();

        return $contentSecurity->CanPrivileges($parentId, self::$UserGroupId, "canWrite");
    }

    

    private function SetLangList($content, $id) {
        $lang = Langs::GetInstance();
        $langList = $lang->Select();

        foreach ($langList as $row) {
            $exist = $content->ItemExistsInLang($id, $row["Id"]);
            $row["Selected"] = $row["Id"] == $this->LangId ? 'selected = \"selected\"' : "";
            if ($exist) {
                $row["LangName"] = $row["LangName"] . " - " . $this->GetWord("word504");
            } else {
                $row["LangName"] = $row["LangName"] . " - " . $this->GetWord("word505");
            }
        }
        $this->SetTemplateData("ItemLangList", $langList);
    }

    private function AutoCreateTemplate($templateId, $sourceLangId) {
        if ($templateId == 0)
            return;
        $content = new \Objects\Content();
        if (!$content->ItemExistsInLang($templateId, $this->LangId)) {
            $templateData = $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $sourceLangId, $templateId);
            $row = $templateData[0];
            $content->CreateVersion($templateId, $row["Name"], $row["IsActive"], self::$UserId, "", $row["TemplateId"], false, $this->LangId, $row["Data"], $row["Header"], "", "", true, "");
            if ($row["TemplateId"] > 0) {
                $this->AutoCreateTemplate($row["TemplateId"], $sourceLangId);
            }
        }
    }

    private function CreateLangParent($id, $sourceLang) {
        if ($id == 0)
            return;
        $content = new \Objects\Content();
        if (!$content->ItemExistsInLang($id, $this->LangId)) {
            $userItem = $content->GetUserItemDetail($id, self::$UserGroupId, $this->WebId, $sourceLang);
            $row = $userItem[0];
            $content->CreateVersion($id, $row["Name"], true, self::$UserId, $row["SeoUrl"], $row["TemplateId"], $row["AvailableOverSeoUrl"], $this->LangId, $row["Data"], "", $row["ActiveFrom"], $row["ActiveTo"], true, "");
            if ($row["ParentId"] > 1) {
                $this->CreateLangParent($row["ParentId"], $sourceLang);
            }
        }
    }

    private function CreateConnectionFormLang($id, $sourceLang) {
        if ($id == 0)
            return;
        $content = new \Objects\Content();
        if (!$content->ItemExistsInLang($id, $this->LangId)) {
            $userItem = $content->GetFormDetail($id, self::$UserGroupId, $this->WebId, $sourceLang);
            $row = $userItem[0];
            $content->CreateVersion($id, $row["Name"], $row["IsActive"], self::$UserId, $row["SeoUrl"], $row["TemplateId"], $row["AvailableOverSeoUrl"], $this->LangId, $row["Data"], "", $row["ActiveFrom"], $row["ActiveTo"], true, "");
        }
    }

    private function CreateConnectionGalleryLang($id, $sourceLang) {
        $content = new \Objects\Content();
        $data = $content->GetRelatedObject($id, $sourceLang);
        //print_r($data);
    }

    private function GetVersionId() {
        return empty($_GET["versionId"]) ? 0 : $_GET["versionId"];
    }

    public function NoPublishItems() {
        $this->SetStateTitle($this->GetWord("word539"));
        $content = new \Objects\Content();
        $items = $content->GetNoPublishItems($this->LangId);
        $this->SetTemplateData("items", $items);
    }

    public function MailingDetail() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuMailing");
        $this->SetUserGroupList();
        $content = new \Objects\Content();
        $id = $this->GetObjectId();

        $data = $content->GetMailingDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data)) {
            $data = $content->GetMailingDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        }

        if (($id > 0 && empty($data)) || ($id == 0 && !$this->CanWriteParent($_GET["parentid"]))) {
            $this->GoToBack();
        }

        $mainData = array();
        if (empty($data[0]))
            $mainData = array("Name" => "", "SeoUrl" => "", "Identificator" => "", "ActiveTo" => "", "ActiveFrom" => "", "AvailableOverSeoUrl" => 1, "NoIncludeSearch" => 0, "TemplateId" => "", "Data" => "");
        else {
            $mainData = $data[0];
        }

        $this->UserGroupList($data);
        $templateList = $content->GetTemplateList(self::$UserGroupId, $this->LangId, true, true);


        $this->SetTemplateData("Name", $mainData["Name"]);
        $this->SetTemplateData("SeoUrl", $mainData["SeoUrl"]);
        $this->SetTemplateData("Identificator", $mainData["Identificator"]);
        $this->SetTemplateData("ActiveTo", $mainData["ActiveTo"]);
        $this->SetTemplateData("ActiveFrom", $mainData["ActiveFrom"]);
        $this->SetTemplateData("AvailableOverSeoUrl", $mainData["AvailableOverSeoUrl"] == 1 ? 'checked= "checked"' : "" );
        $this->SetTemplateData("NoIncludeSearch", $mainData["NoIncludeSearch"] == 1 ? 'checked= "checked"' : "");
        //$this->SetSmartyData("templateList", $templateList);
        $this->SetTemplateData("TemplateId", $mainData["TemplateId"]);


        $this->SetTemplateData("Data", str_replace("\n", "", $mainData["Data"]));
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
        $mailList = $content->GetMailList(self::$UserGroupId, $this->LangId, true, "", "Name ASC");
        $this->SetTemplateData("MailList", $mailList);
        $ud = new \Objects\UserDomains();
        $domainDetail = $ud->GetDomainInfo("Mailinggroups");
        $mailingGroups = $ud->GetDomainValueList($domainDetail["Id"]);
        $mailingGroups = ArrayUtils::SortArray($mailingGroups, "MailingGroupName", SORT_ASC);
        $this->SetTemplateData("MailingGroup", $mailingGroups);
    }

    public function DataSource() {
        $this->SetStateTitle($this->GetWord("word565"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeDataSource());
    }

    public function CreateTreeDataSource($search = "") {
        return \Utils\TreeUtils::CreateTreeDataSource(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    private function joinCodeMirror() {
        $this->AddStyle("/node_modules/codemirror/lib/codemirror.css");
        $this->AddStyle("/node_modules/codemirror/addon/hint/show-hint.css");
        $this->AddScript("/node_modules/codemirror/lib/codemirror.js");
        $this->AddScript("/node_modules/codemirror/addon/hint/xml-hint.js");
        $this->AddScript("/node_modules/codemirror/addon/hint/html-hint.js");
        $this->AddScript("/node_modules/codemirror/mode/xml/xml.js");
        $this->AddScript("/node_modules/codemirror/mode/javascript/javascript.js");
        $this->AddScript("/node_modules/codemirror/mode/css/css.js");
        $this->AddScript("/node_modules/codemirror/mode/htmlmixed/htmlmixed.js");
    }

    public function DataSourceDetail() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word566"));
        $this->SetLeftMenu("contentMenu", "contentMenuDatasource");
        $this->joinCodeMirror();
        $templateData = array();
        $id = $this->GetObjectId();
        $content = new \Objects\Content();
        $data = $content->GetDataSourceDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data)) {
            $data = $content->GetDataSourceDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        }

        $domainId = 0;
        $templateId = 0;
        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }

        $ar = array();
        if (empty($data))
            $ar = array("Name" => "", "Data" => "", "Sort" => "", "SeoUrl" => "", "LastVisited" => "");
        else {
            $ar = (array) $data[0];
            $domainId = $ar["DomainId"];
            $templateId = $ar["TemplateId"];
        }
        $this->UserGroupList($data);
        $ar["Data"] = StringUtils::RemoveString($ar["Data"], "\n");
        $ar["Data"] = StringUtils::RemoveString($ar["Data"], "\r");
        $ar["Data"] = str_replace("<%", "##%#", $ar["Data"]);
        $ar["Data"] = str_replace("%>", "#%##", $ar["Data"]);
        $this->SetTemplateDataArray($ar);
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
        $userDomains = UserDomains::GetInstance();
        $domainList = $userDomains->SelectByCondition("EditValue = 1");
        $domainList = ArrayUtils::SortArray($domainList, "DomainName", SORT_ASC);
        $this->SetTemplateData("DomainList", $domainList);
    }

    public function InquryList() {
        $this->SetStateTitle($this->GetWord("word613"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeInqury());
    }

    public function CreateTreeInqury($search = "") {
        return \Utils\TreeUtils::CreateTreeInqury(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function InqueryDetail() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word615"));
        $this->SetLeftMenu("contentMenu", "contentMenuInqury");
        $id = $this->GetObjectId();
        $content = new \Objects\Content();
        $data = $content->GetInqueryDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $this->GetVersionId());
        if (empty($data))
            $data = $content->GetInqueryDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }

        $ar = array();
        if (empty($data))
            $ar = array("Name" => "", "Data" => "");
        else {
            $ar = (array) $data[0];
        }
        $this->UserGroupList($data);
        $this->SetTemplateDataArray($ar);
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
        $form = new \Utils\Forms();
        $htmlStatistic = $form->GenerateSurveyStatistic($id);
        $this->SetTemplateData("Statistic", $htmlStatistic);
    }

/// funkce pro contnet
    private function SetUserGroupList() {
        $userGroup = new \Objects\Users();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
    }

    private function GetObjectId() {
        if (empty($_GET["id"])) {
            $this->SetTemplateData("id", 0);
            $this->SetTemplateData("IsNew", TRUE);
        }
        $id = 0;
        if (empty($_GET["objectid"])) {
            
        } else {
            $id = $_GET["objectid"];
        }
        return $id;
    }

    private function SetHistoryList($id) {
        $content = new \Objects\Content();
        $history = $content->GetObjectHistoryList($id, $this->WebId, $this->LangId);
        $this->SetTemplateData("HistoryList", $history);
    }

    private function SetInqueryList($inqueryId) {
        $content = new \Objects\Content();
        $inguery = $content->GetInquryList(self::$UserGroupId, $this->LangId, true, "", "Name collate utf8_czech_ci");
        foreach ($inguery as $row) {
            $row["selected"] = "";
            if (!empty($inqueryId)) {
                if ($this->ParseInt(($row["Id"])) === $this->ParseInt(($inqueryId))) {
                    $row["selected"] = 'selected = "selected"';
                }
            }
        }
        $this->SetTemplateData("InqueryList", $inguery);
    }

    private function SetDiscusionList($connId) {
        $content = new \Objects\Content();
        $inguery = $content->GetDiscusionList(self::$UserGroupId, $this->LangId, true, "", "Name collate utf8_czech_ci");
        foreach ($inguery as $row) {
            $row["selected"] = "";
            if (!empty($connId)) {
                if ($this->ParseInt(($row["Id"])) === $this->ParseInt(($connId))) {
                    $row["selected"] = 'selected = "selected"';
                }
            }
        }
        $this->SetTemplateData("DiscusionList", $inguery);
    }

    private function SetFormList($formId) {
        $content = new \Objects\Content();
        $formList = $content->GetFormsList(self::$UserGroupId, $this->LangId, true, "", "Name collate utf8_czech_ci");
        foreach ($formList as $row) {
            $row["selected"] = "";
            if (!empty($formId)) {
                if ($this->ParseInt(($row["Id"])) === $this->ParseInt(($formId))) {
                    $row["selected"] = 'selected = "selected"';
                }
            }
        }
        $this->SetTemplateData("FormList", $formList);
    }

    private function UserGroupList($data) {
        $userGroupList = $this->PreparePrivilegesList($data, "SSValue", "SSGroupId", "SSSecurityType");

        $this->SetPrivileges($userGroupList);
        $this->SetTemplateData("GroupList", $userGroupList);
    }

    /* private function SetDiscusion($discusionSettings)
      {
      for ($i = 0; $i <= 3; $i++) {
      $selectGallerySetting = "";
      if ($i == (!empty($discusionSettings) ? $discusionSettings : 0)) {
      $selectGallerySetting = "selected = 'selected'";
      }
      $this->SetTemplateData("discusionSettings" . $i, $selectGallerySetting);
      }
      } */

    private function SetGallerySettigns($gallerySettings) {
        for ($i = 0; $i <= 3; $i++) {
            $selectGallerySetting = "";
            if ($i == (!empty($gallerySettings) ? $gallerySettings : 0)) {
                $selectGallerySetting = "selected = 'selected'";
            }
            $this->SetTemplateData("mediaGallerySettings" . $i, $selectGallerySetting);
        }
    }

    private function SetTemplateList($templateId, $childTemplateId) {
        $content = new \Objects\Content();
        $templateList = $content->GetTemplateList(self::$UserGroupId, $this->LangId, true, false, "", "Name collate utf8_czech_ci");
        foreach ($templateList as $row) {
            $row["Selected"] = "";
            $row["SelectedChild"] = "";
            if (!empty($templateId)) {
                if ($this->ParseInt(($row["Id"])) === $this->ParseInt(($templateId))) {
                    $row["Selected"] = 'selected = "selected"';
                }
            }
            if (!empty($childTemplateId)) {
                $row["SelectedChild"] = "";
                if ($this->ParseInt(($row["Id"])) === $this->ParseInt(($childTemplateId))) {
                    $row["SelectedChild"] = 'selected = "selected"';
                }
            }
        }
        $this->SetTemplateData("TemplateList", $templateList);
    }

    public function DiscusionList() {
        $this->SetStateTitle($this->GetWord("word251"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeDiscusion());
    }

    public function FileManager() {
        $this->SetStateTitle($this->GetWord("word102"));
        $contentVersion = new \Objects\Content();
        $tree = $contentVersion->GetFileTree($_GET["langid"]);
        $html = $contentVersion->CreateHtml($tree);
        $this->SetTemplateData("tree", $html);
    }

    private function GetLastPosition($contentType, $parentId) {
        /** @var \Model\Content */
        $content = \Model\Content::GetInstance();
        return $content->GetMaxValue("Sort", "ContentType = '$contentType' AND ParentId = $parentId AND Sort <> 99999  AND Deleted = 0") + 1;
    }

    public function CreateTree($search = "") {
        return \Utils\TreeUtils::CreateTree(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

}
