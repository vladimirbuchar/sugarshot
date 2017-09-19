<?php

namespace Controller;

use Model\Langs;
use Utils\ArrayUtils;
use Model\MailingContacts;
use Utils\StringUtils;
use Model\ContentConnection;
use Components\SelectDialog;
use Model\ContentAlternative;

class WebEditApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        $this->SetApiFunction("GetAlernativeArticle", array("system", "Administrators"));
        $this->SetApiFunction("SaveDiscusion", array("system", "Administrators"));
        $this->SetApiFunction("CreateWebLink", array("system", "Administrators"));
        $this->SetApiFunction("GetSeoUrlById", array("system", "Administrators"));
        $this->SetApiFunction("RecoveryItem", array("system", "Administrators"));
        $this->SetApiFunction("ChangeLangVersion", array("system", "Administrators"));
        $this->SetApiFunction("ReSendMails", array("system", "Administrators"));
        $this->SetApiFunction("GetActualTree", array("system", "Administrators"));
        $this->SetApiFunction("GetArticleUrl", array("system", "Administrators"));
        $this->SetApiFunction("MoveItemFolder", array("system", "Administrators"));
        $this->SetApiFunction("GetLinkDetail", array("system", "Administrators"));
        $this->SetApiFunction("PublishItem", array("system", "Administrators"));
        $this->SetApiFunction("GetTreeCopyDialog", array("system", "Administrators"));
        $this->SetApiFunction("GetTreeLinkDialog", array("system", "Administrators"));
        $this->SetApiFunction("SaveMailing", array("system", "Administrators"));
        $this->SetApiFunction("DeteleteMailing", array("system", "Administrators"));
        $this->SetApiFunction("SendMailing", array("system", "Administrators"));
        $this->SetApiFunction("SaveDataSource", array("system", "Administrators"));
        $this->SetApiFunction("GetDomainColumns", array("system", "Administrators"));
        $this->SetApiFunction("GetReletedArticle", array("system", "Administrators"));
        $this->SetApiFunction("GetGalleryList", array("system", "Administrators"));
        $this->SetApiFunction("GetGalleryFromArticle", array("system", "Administrators"));
        $this->SetApiFunction("GetSelectedObjectName", array("system", "Administrators"));
        $this->SetApiFunction("GetObjectsXml", array("system", "Administrators"));
        $this->SetApiFunction("GetDomainIdByUserItemId", array("system", "Administrators"));
        $this->SetApiFunction("GetDomainItems", array("system", "Administrators"));
        $this->SetApiFunction("CallDataSourceImport", array("system", "Administrators"));
        $this->SetApiFunction("CallDataSourceExport", array("system", "Administrators"));
        $this->SetApiFunction("GetObjectData", array("system", "Administrators"));
        $this->SetApiFunction("GenerateXmlItem", array("system", "Administrators"));
        $this->SetApiFunction("SaveIquery", array("system", "Administrators"));
        $this->SetApiFunction("AddAlternativeItem", array("system", "Administrators"));
        $this->SetApiFunction("GetAlternativeItem", array("system", "Administrators"));
        $this->SetApiFunction("CheckFile", array("system", "Administrators"));
        $this->SetApiFunction("GetFormItemDetail", array("system", "Administrators"));
        $this->SetApiFunction("GetTreeLinkDialogCss", array("system", "Administrators"));
        $this->SetApiFunction("GetTreeLinkDialogSaveForm", array("system", "Administrators"));
        $this->SetApiFunction("GetTreeLinkDialogFormSelectFolder", array("system", "Administrators"));
        $this->SetApiFunction("GetTreeLinkDialogAddLinkForm", array("system", "Administrators"));
        $this->SetApiFunction("DeleteAlternativeItems", array("system", "Administrators"));
        $this->SetApiFunction("SaveTemplate", array("system", "Administrators"));
        $this->SetApiFunction("CreateTree", array("system", "Administrators"));
        $this->SetApiFunction("DeleteTemplate", array("system", "Administrators"));
        $this->SetApiFunction("SaveCss", array("system", "Administrators"));
        $this->SetApiFunction("GetDomainFromTemplate", array("system", "Administrators"));
        $this->SetApiFunction("SaveUserItem", array("system", "Administrators"));
        $this->SetApiFunction("SaveForm", array("system", "Administrators"));
        $this->SetApiFunction("MoveItem", array("system", "Administrators"));
        $this->SetApiFunction("CopyItem", array("system", "Administrators"));
        $this->SetApiFunction("SaveFolderFile", array("system", "Administrators"));
        $this->SetApiFunction("GetDomainByIdentificator", array("system", "Administrators"));
        $this->SetApiFunction("SaveFile", array("system", "Administrators"));
        $this->SetApiFunction("ConnectObject", array("system", "Administrators"));
        $this->SetApiFunction("GetRelatedObject", array("system", "Administrators"));
        $this->SetApiFunction("DisconnectObject", array("system", "Administrators"));
        $this->SetApiFunction("GetArticleDiscusion", array("system", "Administrators"));
        $this->SetApiFunction("BlockDiscusionUser", array("system", "Administrators"));
        $this->SetApiFunction("TestDeletePrivileges", array("system", "Administrators"));
        $this->SetApiFunction("SaveJs", array("system", "Administrators"));
        $this->SetApiFunction("SaveEmail", array("system", "Administrators"));
        $this->SetApiFunction("GetRootId", array("system", "Administrators"));
        $this->SetApiFunction("DeleteLangVersion", array("system", "Administrators"));
        $this->SetApiFunction("UpdateFormStatisticItem", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeDiscusion", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeInqury", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeDataSource", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeMailing", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeMail", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeForms", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeJs", array("system", "Administrators"));
        $this->SetApiFunction("CreateTreeCss", array("system", "Administrators"));
    }

    public function GetAlernativeArticle() {
        $dialog = new SelectDialog(true, false, false);
        $dialog->SelectFirstTab = true;
        $dialog->Id = "AlternativeArticle";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
    }

    public function SaveDiscusion($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["ObjectId"];
        if ($id == 0) {
            return $content->CreateDiscusion($ajaxParametrs["NameObject"], $privileges, $_GET["param1"], $_GET["langid"]);
        } else {
            return $content->UpdateDiscusion($id, $ajaxParametrs["NameObject"], $privileges);
        }
    }

    public function CreateWebLink($ajaxParametrs) {
        $content = new \Objects\Content();
        $linkId = empty($ajaxParametrs["LinkId"]) ? 0 : $ajaxParametrs["LinkId"];
        $objectLinkId = empty($ajaxParametrs["ObjectLinkId"]) ? 0 : $ajaxParametrs["ObjectLinkId"];
        $linkInfo = empty($ajaxParametrs["LinkInfo"]) ? array() : $ajaxParametrs["LinkInfo"];
        $privileges = empty($ajaxParametrs["Privileges"]) ? array() : $ajaxParametrs["Privileges"];

        $arrayPreparedPrivileges = array();
        for ($i = 0; $i < count($privileges); $i++) {
            $arrayPreparedPrivileges[$i][0] = "canRead";
            $arrayPreparedPrivileges[$i][1] = StringUtils::RemoveString($privileges[$i][0], "checkbox_");
            $arrayPreparedPrivileges[$i][2] = $privileges[$i][1];
        }

        return $content->CreateLink($ajaxParametrs["Type"], $ajaxParametrs["ParentId"], $linkId, $linkInfo, $objectLinkId, $arrayPreparedPrivileges);
    }

    public function GetSeoUrlById($ajaxParametrs) {
        $content = new \Objects\Content();
        $data = $content->GetUserItemDetail($ajaxParametrs["Id"], self::$UserGroupId, $this->WebId, $this->LangId);
        $seoUrl = $data[0]["SeoUrl"];
        $seoUrl = StringUtils::StartWidth($seoUrl, "/") ? $seoUrl : "/" . $seoUrl;
        $seoUrl = StringUtils::EndWith($seoUrl, "/") ? $seoUrl : $seoUrl . "/";
        return $seoUrl;
    }

    public function RecoveryItem($ajaxParametrs) {
        $id = $ajaxParametrs["Id"];
        $content = new \Objects\Content();
        $content->RecoveryItem($id);
    }

    public function ChangeLangVersion() {
        self::$SessionManager->SetSessionValue("lastEditLang", $this->LangId);
    }

    public function ReSendMails($ajaxParametrs) {
        if (empty($ajaxParametrs))
            return;
        $mailsid = $ajaxParametrs["Mails"];
        if (empty($mailsid))
            return;
        $mail = new \Utils\Mail();
        foreach ($mailsid as $mailid) {
            if (!empty($mailid))
                $mail->SendEmailById($mailid);
        }
    }

    public function GetActualTree($ajaxParametrs) {
        $type = $ajaxParametrs["Type"];
        $search = "";
        if (!empty($ajaxParametrs["Search"])) {
            $search = $ajaxParametrs["Search"];
        }


        $html = "";
        switch ($type) {
            case "useritem":
                $contentVersion = new \Objects\Content();
                $tree = $contentVersion->GetTree($this->LangId, 0, $search);
                $html = $contentVersion->CreateHtml($tree);
                break;
            case "template":
                $html = $this->CreateTree($search);
                break;
            case "css":
                $html = $this->CreateTreeCss($search);
                break;
            case "js":
                $html = $this->CreateTreeJs($search);
                break;
            case "form":
                $html = $this->CreateTreeForms($search);
                break;
            case "mail":
                $html = $this->CreateTreeMail($search);
                break;
            case "datasource":
                $html = $this->CreateTreeDataSource($search);
                break;
            case "mailing":
                $html = $this->CreateTreeMailing($search);
                break;
            case "inquery":
                $html = $this->CreateTreeInqury($search);
                break;
            case "discusion":
                $html = $this->CreateTreeDiscusion($search);
                break;
            case "file":
                $contentVersion = new \Objects\Content();
                $tree = $contentVersion->GetFileTree($this->LangId, 0, $search);
                $html = $contentVersion->CreateHtml($tree);
                break;
        }
        return $html;
    }

    public function GetArticleUrl($param) {

        $id = $param["ObjectId"];
        $lang = Langs::GetInstance();
        $lang->GetObjectById($this->LangId, true, array("RootUrl"));
        $content = new \Objects\Content();
        $detail = $content->GetUserItemDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        return $lang->RootUrl . "preview/" . $detail[0]["SeoUrl"] . "/";
    }

    public function MoveItemFolder($ajaxParametrs) {
        /** @var \Model\ContentVersion */
        $content = new \Objects\Content();
        $mode = $ajaxParametrs["Mode"];
        $id = $ajaxParametrs["Id"];
        $contentType = $ajaxParametrs["ContentType"];
        $content->SetPosition($id, $mode, $contentType);
    }

    public function GetLinkDetail($ajax) {
        $id = $ajax["id"];
        $content = new \Objects\Content();
        $res = $content->GetLinkDetail($id, $this->WebId, $this->LangId);
        $xml = $res[0]["Data"];
        $ar = ArrayUtils::XmlToArray($xml);
        $ar = $ar["item"];
        $ar["Name"] = $res[0]["Name"];
        $ar["Url"] = $res[0]["SeoUrl"];
        return $ar;
    }

    public function PublishItem($param) {
        $id = $param["id"];
        $content = new \Objects\Content();
        return $content->PublishItem($id, $this->LangId) ? "true" : "false";
    }

    public function GetTreeCopyDialog() {
        $dialog = new SelectDialog(true, false, false);
        $dialog->ShowUserItemsTab = true;
        $dialog->Id = "copyItem";
        $dialog->SelectFirstTab = true;
        return $dialog->LoadComponent();
    }

    public function GetTreeLinkDialog() {
        $dialogLink = new SelectDialog(true, true, true, true, true, true);
        $dialogLink->Id = "linkDialog";
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }

    public function SaveMailing($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];

        if ($id == 0) {
            $id = $content->CreateMailing($ajaxParametrs["Name"], $_GET["langid"], $_GET["param1"], $privileges, $ajaxParametrs["MailingParametrs"], $ajaxParametrs["Publish"]);
        } else {
            $id = $content->UpdateMailing($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["MailingParametrs"], $ajaxParametrs["Publish"]);
        }
        return $id;
    }

    public function DeteleteMailing($ajaxParametrs) {
        $id = empty($ajaxParametrs["Id"]) ? 0 : $ajaxParametrs["Id"];
        $mailinig = MailingContacts::GetInstance();
        $mailinig->DeleteObject($id);
    }

    public function SendMailing($ajaxParametrs) {
        $id = $ajaxParametrs["ObjectId"];
        $emailid = $ajaxParametrs["Email"];
        $mailingGroupId = $ajaxParametrs["MailingGroup"];
        $from = $ajaxParametrs["MailSender"];
        $mailing = new \Objects\Content();
        $mailing->SendMailing($id, self::$UserGroupId, $this->WebId, $this->LangId, $emailid, $mailingGroupId, $from);
    }

    public function SaveDataSource($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        if ($id == 0) {
            $id = $content->CreateDataSource($ajaxParametrs["Name"], $privileges, $ajaxParametrs["SeoUrl"], $ajaxParametrs["Data"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"]);
        } else {
            $id = $content->UpdateDataSource($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["SeoUrl"], $ajaxParametrs["Data"], $ajaxParametrs["Publish"]);
        }
        return $id;
    }

    public function GetDomainColumns($param) {
        $ui = new \Objects\UserDomains();
        return $ui->GetUserDomainItemById($param["domainId"]);
    }

    public function GetReletedArticle() {
        $dialog = new SelectDialog(true, false, false);
        $dialog->SelectFirstTab = true;
        $dialog->Id = "ReletedArticle";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
    }

    public function GetGalleryList() {
        $dialog = new SelectDialog(false, true, false);
        $dialog->SelectFirstTab = true;
        $dialog->Id = "GalleryItemDialog";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
    }

    public function GetGalleryFromArticle() {
        $dialog = new SelectDialog(true, false, false);
        $dialog->SelectFirstTab = true;
        $dialog->Id = "SelectDialogGalleryArticle";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
    }

    public function GetSelectedObjectName($param) {
        $content = new \Objects\Content();
        return $content->GetNameObject($param["SelectedObject"], $this->LangId);
    }

    public function GetObjectsXml() {
        $dialog = new SelectDialog(true, false, false);
        $dialog->Id = "SelectXml";
        $dialog->SelectFirstTab = true;
        $dialog->CssClass = $dialog->CssClass . " noDatabase";
        $dialogHtml = $dialog->GetComponentHtml();

        return $dialogHtml;
    }

    public function GetDomainIdByUserItemId($param) {
        $content = new \Objects\Content();
        return $content->GetUserItemDomainId($param["ObjectIdConnection"]);
    }

    public function GetDomainItems($param) {
        $domainId = $param["domainValue"];
        $udi = new \Objects\UserDomains();
        $res = $udi->GetUserDomainItemById($domainId);
        return $res;
    }

    public function CallDataSourceImport($param) {
        $content = new \Objects\Content();
        $data = array();
        $rootUrl = $this->GetRoorUrl();
        $data = $content->GetDataSourceDetail($param["ObjectId"], self::$UserGroupId, $this->WebId, $this->LangId);
        $rootUrl = $rootUrl . "xmlimport/";
        $url = $rootUrl . $data[0]["SeoUrl"] . "/";
        $this->CallUrl($url, "?login=system&pswrd=sd15kl20");
    }

    public function CallDataSourceExport($param) {
        $content = new \Objects\Content();
        $data = $content->GetDataSourceDetail($param["ObjectId"], self::$UserGroupId, $this->WebId, $this->LangId);
        $rooturl = $this->GetRoorUrl() . "xmldownload/";
        return $rooturl . $data[0]["SeoUrl"] . ".xml";
    }

    public function GetObjectData($ajaxParametrs) {
        $type = $ajaxParametrs["Type"];
        $id = $ajaxParametrs["Id"];
        $content = new \Objects\Content();
        $data = array();
        switch ($type) {
            case "useritem":
                $data = $content->GetUserItemDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "template":
                $data = $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId, $id);
                break;
            case "css":
                $data = $content->GetCssDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "js":
                $data = $content->GetJsDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "form":
                $data = $content->GetFormDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "mail":
                $data = $content->GetMailDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "mailing":
                $data = $content->GetMailingDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "filefolder":
                $data = $content->GetFileFolderDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "fileupload":
                $data = $content->GetFileFolderDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "datasource":
                $data = $content->GetDataSourceDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
            case "inquery":
                $data = $content->GetInqueryDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
                break;
        }

        return $data;
    }

    public function GenerateXmlItem($param) {
        $content = new \Objects\Content();
        $data = $content->GenerateXmlItem($param["ObjectId"], $this->LangId, self::$UserGroupId, $this->WebId);
        return array("XML" => $data);
    }

    public function SaveIquery($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        if ($id == 0) {
            $id = $content->CreateInquery($ajaxParametrs["Name"], $privileges, $ajaxParametrs["Data"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"]);
        } else
            $id = $content->UpdateInquery($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["Data"], $ajaxParametrs["Publish"]);
        return $id;
    }

    public function AddAlternativeItem($params) {
        $objectId = $params["ObjectId"];
        $userGroup = $params["UserGroup"];
        $alternativeItemId = $params["AlternativeItem"];
        $alternativeItem = new \Objects\Content();
        $alternativeItem->SaveAlternativeItem($objectId, $userGroup, $alternativeItemId);
    }

    public function GetAlternativeItem($param) {
        $content = new \Objects\Content();
        return $content->GetAlternativeItems($param["ObjectId"], $this->LangId);
    }

    public function CheckFile($param) {

        $content = new \Objects\Content();
        $data = array();
        $rootUrl = $this->GetRoorUrl();
        $data = $content->GetDataSourceDetail($param["ObjectId"], self::$UserGroupId, $this->WebId, $this->LangId);
        $rootUrl = $rootUrl . "checkxmlimport/";
        $url = $rootUrl . $data[0]["SeoUrl"] . "/";
        $this->CallUrl($url);
    }

    public function GetFormItemDetail($param) {
        $id = $param["id"];
        $form = new \Utils\Forms();
        return $form->GetFormItemDetail($id);
    }

    public function GetTreeLinkDialogCss() {
        $dialogLink = new SelectDialog(false, false, true, false);
        $dialogLink->Id = "linkDialog";
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }

    public function GetTreeLinkDialogSaveForm() {
        $dialogLink = new SelectDialog(true, false, false, false);
        $dialogLink->Id = "linkDialog";
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }

    public function GetTreeLinkDialogFormSelectFolder() {
        $dialogLink = new SelectDialog(true, false, false);
        $dialogLink->Id = "linkDialog";
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }

    public function GetTreeLinkDialogAddLinkForm() {
        $dialogLink = new SelectDialog(true, false, false);
        $dialogLink->Id = "linkDialog2";
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }

    public function DeleteAlternativeItems($param) {
        $id = $param["id"];
        $contentAlernative = ContentAlternative::GetInstance();
        $contentAlernative->DeleteObject($id, true, false);
    }

    public function SaveTemplate($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        $templateSettings = $ajaxParametrs["TemplateSettings"];
        if ($id == 0)
            $id = $content->CreateTemplate($ajaxParametrs["Name"], $ajaxParametrs["Identificator"], $privileges, $ajaxParametrs["Content"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Domain"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $ajaxParametrs["TemplateHeader"], $templateSettings);
        else
            $id = $content->UpdateTemplate($id, $ajaxParametrs["Name"], $ajaxParametrs["Identificator"], $privileges, $ajaxParametrs["Content"], $ajaxParametrs["Domain"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $ajaxParametrs["TemplateHeader"], $templateSettings);
        return $id;
    }

    public function CreateTree($search = "") {
        return \Utils\TreeUtils::CreateTree(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function DeleteTemplate($ajaxParametrs) {
        $id = $ajaxParametrs["Id"];
        $content = new \Objects\Content();
        return $content->DeleteItem($id) ? "TRUE" : "FALSE";
    }

    public function SaveCss($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();

        $id = $ajaxParametrs["Id"];

        if ($id == 0) {
            $id = $content->CreateCss($ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"]);
        } else
            $id = $content->UpdateCss($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $ajaxParametrs["Publish"]);
        return $id;
    }

    public function GetDomainFromTemplate($ajaxParametrs) {
        $content = new \Objects\Content();
        $templateId = $ajaxParametrs["Id"];
        $domain = new \Objects\UserDomains();
        $identificator = $domain->GetUserDomainByTemplateId($templateId);
        $data = "";
        if (!empty($ajaxParametrs["ObjectId"])) {
            $objectId = $ajaxParametrs["ObjectId"];
            $tmp = $content->GetUserItemDetail($objectId, self::$UserGroupId, $this->WebId, $this->LangId);
            if (!empty($tmp))
                $data = $tmp[0]["Data"];
            if (empty($data)) {
                $lastEditLang = $this->GetLastEditLangVersion(true);
                $tmp = $content->GetUserItemDetail($objectId, self::$UserGroupId, $this->WebId, $lastEditLang);
                if (!empty($tmp))
                    $data = $tmp[0]["Data"];
            }
        }
        $readOnly = false;
        if (!empty($ajaxParametrs["ReadOnly"])) {
            $readOnly = $ajaxParametrs["ReadOnly"] == "true" ? true : false;
        }

        $templateInfo = $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId, $templateId);

        $out["TemplateSettings"] = empty($templateInfo) ? "" : $templateInfo[0]["ContentSettings"];
        $out["Html"] = $this->GetUserDomain($identificator, 0, false, $data, $readOnly);

        return $out;
    }

    public function SaveUserItem($ajaxParametrs) {
        $noChild = $ajaxParametrs["NoChild"] == 1 ? true : false;
        $useTemplateInChild = $ajaxParametrs["UseTemplateInChild"] == "1" || $ajaxParametrs["UseTemplateInChild"] == 1 ? true : false;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        $data = $ajaxParametrs["Parametrs"];
        unset($ajaxParametrs["Parametrs"]);
        if ($id == 0) {
            $id = $content->CreateUserItem($ajaxParametrs["NameObject"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $_GET["langid"], $_GET["param1"], $privileges, $data, false, $ajaxParametrs["GallerySettings"], $ajaxParametrs["Discusion"] == 0 ? 0 : 1, $ajaxParametrs["Discusion"], $ajaxParametrs["FormSettings"], $noChild, $useTemplateInChild, $ajaxParametrs["ChildTemplate"], $ajaxParametrs["CopyDataToChild"], $ajaxParametrs["ActivatePager"], $ajaxParametrs["FirstItemLoadPager"], $ajaxParametrs["NextItemLoadPager"], $ajaxParametrs["InquerySettings"], $ajaxParametrs["NoLoadSubitems"], $ajaxParametrs["SaveToCache"], $ajaxParametrs["Sort"], $ajaxParametrs["SortRule"]);
        } else {
            $id = $content->UpdateUserItem($id, $ajaxParametrs["NameObject"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $privileges, $data, $ajaxParametrs["GallerySettings"], $ajaxParametrs["Discusion"] == 0 ? 0 : 1, $ajaxParametrs["Discusion"], $ajaxParametrs["FormSettings"], $noChild, $useTemplateInChild, $ajaxParametrs["ChildTemplate"], $ajaxParametrs["CopyDataToChild"], $ajaxParametrs["ActivatePager"], $ajaxParametrs["FirstItemLoadPager"], $ajaxParametrs["NextItemLoadPager"], $ajaxParametrs["InquerySettings"], $ajaxParametrs["NoLoadSubitems"], $ajaxParametrs["SaveToCache"], $ajaxParametrs["Sort"], $ajaxParametrs["SortRule"]);
        }
        if ($ajaxParametrs["Publish"]) {
            $canPublish = $content->HasPrivileges($id, \Types\PrivilegesType::CANPUBLISH);
            if ($canPublish) {
                $contentVersion = \Model\ContentVersion::GetInstance();
                $contentVersion->UpdateMaterializedView("FrontendDetail");
            }
        }
        return $id;
    }

    public function SaveForm($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        $data = $ajaxParametrs["Parametrs"];

        unset($ajaxParametrs["Parametrs"]);
        if ($id == 0) {
            $id = $content->CreateForm($ajaxParametrs["Name"], empty($ajaxParametrs["SeoUrl"]) ? "":$ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], empty($ajaxParametrs["Identificator"])?"":$ajaxParametrs["Identificator"], empty($ajaxParametrs["ActiveFrom"])?date('Y-m-d H:i:s'):$ajaxParametrs["ActiveFrom"], empty($ajaxParametrs["ActiveTo"]) ? date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 36500 day")):$ajaxParametrs["ActiveTo"], empty($ajaxParametrs["Template"])? 0:$ajaxParametrs["Template"], $ajaxParametrs["Publish"], $_GET["langid"], $_GET["param1"], $privileges, $data);
        } else {
            $id = $content->UpdateForm($id, $ajaxParametrs["Name"], empty($ajaxParametrs["SeoUrl"]) ? "":$ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], empty($ajaxParametrs["Identificator"])?"":$ajaxParametrs["Identificator"], empty($ajaxParametrs["ActiveFrom"])?date('Y-m-d H:i:s'):$ajaxParametrs["ActiveFrom"], empty($ajaxParametrs["ActiveTo"]) ? date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 36500 day")):$ajaxParametrs["ActiveTo"],  empty($ajaxParametrs["Template"])? 0:$ajaxParametrs["Template"], $ajaxParametrs["Publish"], $privileges, $data);
        }
        return $id;
    }

    public function MoveItem($ajaxParametrs) {
        $sourceId = $ajaxParametrs["sourceId"];
        $destinationId = $ajaxParametrs["destinationId"];
        $contentVersion = new \Objects\Content();
        return $contentVersion->Move($sourceId, $destinationId) ? "TRUE" : "FALSE";
    }

    public function CopyItem($ajaxParametrs) {
        $sourceId = $ajaxParametrs["sourceId"];
        $destinationId = $ajaxParametrs["destinationId"];
        $contentVersion = new \Objects\Content();
        return $contentVersion->Copy($_GET["langid"], $_GET["webid"], $sourceId, $destinationId) ? "TRUE" : "FALSE";
    }

    public function SaveFolderFile($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        
        if ($id == 0) {
            $id = $content->CreateFileFolder($ajaxParametrs["Name"], empty($ajaxParametrs["SeoUrl"]) ?"":$ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"],$_GET["langid"], $_GET["param1"], $ajaxParametrs["NoIncludeSearch"],empty($ajaxParametrs["Identificator"]) ? "" :$ajaxParametrs["Identificator"], empty($ajaxParametrs["ActiveFrom"])? date('Y-m-d H:i:s') :$ajaxParametrs["ActiveFrom"], empty($ajaxParametrs["ActiveTo"]) ? date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 36500 day")) :$ajaxParametrs["ActiveTo"], $privileges);
        } else {
            $id = $content->UpdateFileFolder($id, $ajaxParametrs["Name"], empty($ajaxParametrs["SeoUrl"]) ?"":$ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], empty($ajaxParametrs["Identificator"]) ? "" :$ajaxParametrs["Identificator"], empty($ajaxParametrs["ActiveFrom"])? date('Y-m-d H:i:s') :$ajaxParametrs["ActiveFrom"], empty($ajaxParametrs["ActiveTo"]) ? date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 36500 day")) :$ajaxParametrs["ActiveTo"], $privileges);
        }
        return $id;
    }

    public function GetDomainByIdentificator($ajaxParametrs) {
        $identificator = $ajaxParametrs["Identifcator"];
        $data = "";
        $content = new \Objects\Content();
        if (!empty($ajaxParametrs["ObjectId"])) {
            $objectId = $ajaxParametrs["ObjectId"];
            $tmp = $content->GetFileFolderDetail($objectId, self::$UserGroupId, $this->WebId, $this->LangId);
            if (!empty($tmp))
                $data = $tmp[0]["Data"];
            if (empty($data)) {
                $lastEditLang = $this->GetLastEditLangVersion(true);
                $tmp = $content->GetFileFolderDetail($objectId, self::$UserGroupId, $this->WebId, $lastEditLang);
                if (!empty($tmp))
                    $data = $tmp[0]["Data"];
            }
        }


        return $this->GetUserDomain($identificator, 0, false, $data);
    }

    public function SaveFile($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        $data = $ajaxParametrs["Parametrs"];
        if ($id == 0) {
            $id = $content->CreateFile($ajaxParametrs["Name"], $_GET["langid"], $_GET["param1"], $ajaxParametrs["NoIncludeSearch"], empty($ajaxParametrs["Identificator"]) ? "" :$ajaxParametrs["Identificator"], empty($ajaxParametrs["ActiveFrom"]) ? date('Y-m-d H:i:s'):$ajaxParametrs["ActiveFrom"] , empty($ajaxParametrs["ActiveTo"]) ?date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 36500 day")):$ajaxParametrs["ActiveTo"], $privileges, $data);
        } else {
            $id = $content->UpdateFile($id, $ajaxParametrs["Name"], $ajaxParametrs["NoIncludeSearch"], empty($ajaxParametrs["Identificator"]) ? "" :$ajaxParametrs["Identificator"], empty($ajaxParametrs["ActiveFrom"]) ? date('Y-m-d H:i:s'):$ajaxParametrs["ActiveFrom"] , empty($ajaxParametrs["ActiveTo"]) ?date('Y-m-d',strtotime(date("Y-m-d", time()) . " + 36500 day")):$ajaxParametrs["ActiveTo"], $privileges, $data);
        }
        return $id;
    }

    public function ConnectObject($ajaxParametrs) {
        $conObj = new \Objects\Content();
        $conObj->CreateConnection($ajaxParametrs["ObjectId"], $ajaxParametrs["ObjectIdConnection"], $ajaxParametrs["Mode"], $ajaxParametrs["Data"]);
    }

    public function GetRelatedObject($ajaxParametrs) {
        $conObj = new \Objects\Content();
        return $conObj->GetRelatedObject($ajaxParametrs["ObjectId"], $_GET["langid"], $ajaxParametrs["ObjectType"]);
    }

    public function DisconnectObject($ajaxParametrs) {
        $conObj = ContentConnection::GetInstance();
        $id = $ajaxParametrs["ObjectId"];
        $conObj->DeleteObject($id, true, false);
    }

    public function GetArticleDiscusion($ajaxParametrs) {
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
        return $content->GetArticleDiscusion($id);
    }

    public function BlockDiscusionUser($ajaxParametrs) {
        
        $id = $ajaxParametrs["UserId"];

        self::$User->BlockDiscusionUser($id);
    }

    public function SaveJs($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];

        if ($id == 0) {
            $id = $content->CreateJs($ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"], empty($ajaxParametrs["Sort"]) ? 0 :$ajaxParametrs["Sort"]);
        } else
            $id = $content->UpdateJs($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $ajaxParametrs["Publish"], empty($ajaxParametrs["Sort"]) ? 0 :$ajaxParametrs["Sort"]);
        return $id;
    }

    public function SaveEmail($ajaxParametrs) {
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = new \Objects\Content();
        $id = $ajaxParametrs["Id"];
         $editors = $this->GetHtmlEditors($ajaxParametrs);
         
        if ($id == 0) {
            $id = $content->CreateMail($ajaxParametrs["Name"], $_GET["langid"], $_GET["param1"], $privileges, $editors["EmailText"], $ajaxParametrs["Publish"]);
        } else {
            $id = $content->UpdateMail($id, $ajaxParametrs["Name"], $privileges, $editors["EmailText"], $ajaxParametrs["Publish"]);
        }
        return $id;
    }
    
    
    
    

    public function GetRootId() {
        /** @var 
          \Model\ContentVersion
         */
        $content = new \Objects\Content();
        $folderId = $content->GetIdByIdentificator("langfolder", $_GET["webid"]);
        return $folderId;
    }

    public function DeleteLangVersion($param) {
        /** @var 
          \Model\ContentVersion
         */
        $content = new \Objects\Content();
        $content->DeleteLangVersion($param["objectId"], $_GET["langid"]);
        
    }

    public function UpdateFormStatisticItem($ajaxParametrs) {
        $content = new \Objects\Content();
        $content->UpdateFormStatisticItem($ajaxParametrs["Id"], $ajaxParametrs["ItemId"], $ajaxParametrs["ItemValue"]);
    }

    public function CreateTreeDiscusion($search = "") {
        return \Utils\TreeUtils::CreateTreeDiscusion(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeInqury($search = "") {
        return \Utils\TreeUtils::CreateTreeInqury(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeDataSource($search = "") {
        return \Utils\TreeUtils::CreateTreeDataSource(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeMailing($search = "") {
        return \Utils\TreeUtils::CreateTreeMailing(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeMail($search = "") {
        return \Utils\TreeUtils::CreateTreeMail(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeForms($search = "") {
        return \Utils\TreeUtils::CreateTreeForms(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeJs($search = "") {
        return \Utils\TreeUtils::CreateTreeJs(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

    public function CreateTreeCss($search = "") {
        return \Utils\TreeUtils::CreateTreeCss(self::$User->GetUserGroupId(), $this->LangId, $search);
    }

}
