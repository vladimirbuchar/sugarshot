<?php 
namespace Controller;
use Model\ContentVersion;
use Model\Langs;
use Model\UserGroups;
use Model\ContentSecurity;
use Model\UserDomainsItems;
use Model\UserDomains;
use Utils\ArrayUtils;
use Components\HtmlEditor;
use Model\MailingContacts;
use Model\UserDomainsValues;
use Utils\StringUtils;
use Model\ContentConnection;
use Components\SelectDialog; 
use Model\DiscusionItems;
use Model\ContentAlternative;
use Model\MailingContactsInGroups;
use Types\ContentTypes;

class WebEdit extends AdminController {
    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->CheckWebPrivileges();
        if ($this->IsPostBack || $this->IsGet)
        {
            
            $this->SetViewPermition("Tree", array("system", "Administrators"));
            $this->SetViewPermition("TemplateEditor", array("system", "Administrators"));
            $this->SetViewPermition("Detail", array("system", "Administrators"));
            $this->SetViewPermition("TemplateDetail", array("system", "Administrators"));
            $this->SetViewPermition("CssList", array("system", "Administrators"));
            $this->SetViewPermition("FormsList", array("system", "Administrators"));
            $this->SetViewPermition("MailList", array("system", "Administrators"));
            $this->SetViewPermition("CssEditor", array("system", "Administrators"));
            $this->SetViewPermition("FileFolder", array("system", "Administrators"));
            $this->SetViewPermition("FileUploader", array("system", "Administrators"));
            $this->SetViewPermition("Discusion", array("system", "Administrators"));
            $this->SetViewPermition("JsList", array("system", "Administrators"));
            $this->SetViewPermition("JsEditor", array("system", "Administrators"));
            $this->SetViewPermition("FormEditor", array("system", "Administrators"));
            $this->SetViewPermition("MailEditor", array("system", "Administrators"));
            $this->SetViewPermition("SendMailList", array("system", "Administrators"));
            $this->SetViewPermition("Trash", array("system","Administrators"));
            $this->SetViewPermition("NoPublishItems", array("system","Administrators"));
            $this->SetViewPermition("Mailing", array("system","Administrators"));
            $this->SetViewPermition("MailingDetail", array("system","Administrators"));
            //$this->SetViewPermition("MailingContacts", array("system","Administrators"));
            $this->SetViewPermition("DataSource",  array("system","Administrators"));
            $this->SetViewPermition("DataSourceDetail", array("system","Administrators"));
            $this->SetViewPermition("InquryList", array("system","Administrators"));
            $this->SetViewPermition("InqueryDetail", array("system","Administrators"));
            $this->SetMustBeLang("InquryList");
            $this->SetMustBeWebId("InquryList");
            $this->SetMustBeWebId("Tree");
            $this->SetMustBeLang("Tree");
            $this->SetMustBeWebId("TemplateEditor");
            $this->SetMustBeLang("TemplateEditor");
            $this->SetMustBeWebId("CssList");
            $this->SetMustBeLang(("CssList"));
            $this->SetMustBeWebId("FormsList");
            $this->SetMustBeLang(("FormsList"));
            $this->SetTemplateData("controllerName", $this->ControllerName);
            $this->AddScript("/Scripts/ContentTree.js");
            $this->SetMustBeWebId("JsList");
            $this->SetMustBeLang("JsList");
            $this->SetMustBeLang("MailList");
            $this->SetMustBeWebId("MailList");
            $this->SetMustBeLang("SendMailList");
            $this->SetMustBeWebId("SendMailList");
            $this->SetMustBeLang("Mailing");
            $this->SetMustBeWebId("Mailing");
            $this->SetMustBeLang("NoPublishItems");
            $this->SetMustBeWebId("NoPublishItems");
            $this->SetMustBeLang("Trash");
            $this->SetMustBeWebId("Trash");
            $this->SetMustBeLang("DataSource");
            $this->SetMustBeWebId("DataSource");
            $this->SetViewPermition("DiscusionList", array("system","Administrators"));
            $this->MustBeLangId("DiscusionList");
            $this->MustBeWebId("DiscusionList");
            $this->SetViewPermition("FileManager", array("system","Administrators"));
            $this->SetMustBeLang("FileManager");
            $this->SetMustBeWebId("FileManager");
            
        }
        if (self::$IsAjax)
        {
            $this->SetAjaxFunction("GetAlernativeArticle",array("system","Administrators"));
            $this->SetAjaxFunction("SaveDiscusion",array("system","Administrators"));
            $this->SetAjaxFunction("CreateWebLink",array("system","Administrators"));
            $this->SetAjaxFunction("GetSeoUrlById", array("system","Administrators"));
            $this->SetAjaxFunction("RecoveryItem",array("system","Administrators"));
            $this->SetAjaxFunction("ChangeLangVersion",array("system","Administrators"));
            $this->SetAjaxFunction("ReSendMails", array("system","Administrators"));
            $this->SetAjaxFunction("GetActualTree", array("system","Administrators"));
            $this->SetAjaxFunction("GetArticleUrl", array("system","Administrators"));
            $this->SetAjaxFunction("MoveItemFolder", array("system","Administrators"));
            $this->SetAjaxFunction("GetLinkDetail", array("system","Administrators"));
            $this->SetAjaxFunction("PublishItem", array("system","Administrators"));
            $this->SetAjaxFunction("GetTreeCopyDialog",array("system","Administrators"));
            $this->SetAjaxFunction("GetTreeLinkDialog",array("system","Administrators"));
            $this->SetAjaxFunction("SaveMailing",array("system","Administrators"));
            //$this->SetAjaxFunction("SaveMailinContact", array("system","Administrators"));
            //$this->SetAjaxFunction("GetMailingItemDetail",array("system","Administrators"));
            $this->SetAjaxFunction("DeteleteMailing", array("system","Administrators"));
            $this->SetAjaxFunction("SendMailing", array("system","Administrators"));
            $this->SetAjaxFunction("SaveDataSource", array("system","Administrators"));
            $this->SetAjaxFunction("GetDomainColumns",array("system","Administrators"));
            $this->SetAjaxFunction("GetReletedArticle", array("system","Administrators"));
            $this->SetAjaxFunction("GetReletedUserItemsDialog", array("system","Administrators"));
            $this->SetAjaxFunction("GetGalleryList", array("system","Administrators"));
            $this->SetAjaxFunction("GetGalleryFromArticle", array("system","Administrators"));
            $this->SetAjaxFunction("GetSelectedObjectName", array("system","Administrators"));
            $this->SetAjaxFunction("GetObjectsXml", array("system","Administrators"));
            $this->SetAjaxFunction("GetDomainIdByUserItemId", array("system","Administrators"));
            $this->SetAjaxFunction("GetDomainItems", array("system","Administrators"));
            $this->SetAjaxFunction("CallDataSourceImport", array("system","Administrators"));
            $this->SetAjaxFunction("CallDataSourceExport", array("system","Administrators"));
            $this->SetAjaxFunction("GetObjectData", array("system","Administrators"));
            $this->SetAjaxFunction("GenerateXmlItem", array("system","Administrators"));
            $this->SetAjaxFunction("SaveIquery", array("system","Administrators"));
            $this->SetAjaxFunction("AddAlternativeItem", array("system","Administrators"));
            $this->SetAjaxFunction("GetAlternativeItem", array("system","Administrators"));
            $this->SetAjaxFunction("CheckFile",array("system","Administrators"));
            $this->SetAjaxFunction("GetFormItemDetail", array("system","Administrators"));
            $this->SetAjaxFunction("GetTreeLinkDialogCss", array("system","Administrators"));
            $this->SetAjaxFunction("GetTreeLinkDialogSaveForm", array("system","Administrators"));
            $this->SetAjaxFunction("GetTreeLinkDialogFormSelectFolder", array("system","Administrators"));
            $this->SetAjaxFunction("GetTreeLinkDialogAddLinkForm", array("system","Administrators"));
            $this->SetAjaxFunction("DeleteAlternativeItems",array("system","Administrators"));
            $this->SetAjaxFunction("SaveTemplate", array("system", "Administrators"));
            $this->SetAjaxFunction("CreateTree", array("system", "Administrators"));
            $this->SetAjaxFunction("DeleteTemplate", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveCss", array("system", "Administrators"));
            $this->SetAjaxFunction("GetDomainFromTemplate", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveUserItem", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveForm", array("system", "Administrators"));
            $this->SetAjaxFunction("MoveItem", array("system", "Administrators"));
            $this->SetAjaxFunction("CopyItem", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveFolderFile", array("system", "Administrators"));
            $this->SetAjaxFunction("GetDomainByIdentificator", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveFile", array("system", "Administrators"));
            $this->SetAjaxFunction("ConnectObject", array("system", "Administrators"));
            $this->SetAjaxFunction("GetRelatedObject", array("system", "Administrators"));
            $this->SetAjaxFunction("DisconnectObject", array("system", "Administrators"));
            $this->SetAjaxFunction("GetArticleDiscusion", array("system", "Administrators"));
            $this->SetAjaxFunction("BlockDiscusionUser", array("system", "Administrators"));
            $this->SetAjaxFunction("TestDeletePrivileges", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveJs", array("system", "Administrators"));
            $this->SetAjaxFunction("SaveEmail", array("system", "Administrators"));
            $this->SetAjaxFunction("GetRootId",array("system", "Administrators"));
            $this->SetAjaxFunction("DeleteLangVersion",array("system", "Administrators"));
        }
        
    }
    
    public function CreateWebLink()
    { 
        $ajaxParametrs = $this->PrepareAjaxParametrs(); 
        
            
        $content =  ContentVersion::GetInstance();
        $linkId = empty($ajaxParametrs["LinkId"]) ? 0 :$ajaxParametrs["LinkId"];
        $objectLinkId = empty($ajaxParametrs["ObjectLinkId"]) ? 0 :$ajaxParametrs["ObjectLinkId"];
        $linkInfo = empty($ajaxParametrs["LinkInfo"]) ? array() :$ajaxParametrs["LinkInfo"];
        $privileges = empty($ajaxParametrs["Privileges"]) ? array() :$ajaxParametrs["Privileges"];
        
        $arrayPreparedPrivileges = array();
        for($i= 0; $i< count($privileges);$i++)
        {
            $arrayPreparedPrivileges[$i][0]="canRead";
            $arrayPreparedPrivileges[$i][1]=  StringUtils::RemoveString($privileges[$i][0], "checkbox_");
            $arrayPreparedPrivileges[$i][2] = $privileges[$i][1];
       }
        
        return $content->CreateLink($ajaxParametrs["Type"], $ajaxParametrs["ParentId"], $linkId,$linkInfo,$objectLinkId,$arrayPreparedPrivileges);
    }
    
    public function GetSeoUrlById()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs(); 
        $content =  ContentVersion::GetInstance();
        $data = $content->GetUserItemDetail($ajaxParametrs["Id"],self::$UserGroupId,$this->WebId,$this->LangId);
        $seoUrl = $data[0]["SeoUrl"];
        $seoUrl =  StringUtils::StartWidth($seoUrl, "/") ? $seoUrl:"/".$seoUrl;
        $seoUrl =  StringUtils::EndWith($seoUrl, "/") ? $seoUrl:$seoUrl."/";
        return $seoUrl;
    }
    
    public function Mailing()
    {
        $this->SetStateTitle($this->GetWord("word544"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeMailing());
    }

    public function Tree() {
        
        $this->SetStateTitle($this->GetWord("word135"));
        $contentVersion =  ContentVersion::GetInstance();
        $tree = $contentVersion->GetTree($_GET["langid"]);
        $html = $contentVersion->CreateHtml($tree);
        $this->SetTemplateData("tree", $html);
    }
    
    public function GetTreeCopyDialog()
    {
        $dialog = new SelectDialog(true,false,false);
        $dialog->ShowUserItemsTab = true;
        $dialog->Id ="copyItem";
        $dialog->SelectFirstTab = true;
        return $dialog->LoadComponent();
    }
    public function GetTreeLinkDialog()
    {
        $dialogLink = new SelectDialog(true,true,true,true,true,true);
        $dialogLink->Id = "linkDialog"; 
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }
    
    public function GetTreeLinkDialogCss()
    {
        $dialogLink = new SelectDialog(false,false,true,false);
        $dialogLink->Id = "linkDialog"; 
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }
    public function GetTreeLinkDialogSaveForm()
    {
        $dialogLink = new SelectDialog(true,false,false,false);
        $dialogLink->Id = "linkDialog"; 
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }
    
    public function GetTreeLinkDialogFormSelectFolder()
    {
        $dialogLink = new SelectDialog(true,false, false);
        $dialogLink->Id = "linkDialog"; 
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
    }
        public function GetTreeLinkDialogAddLinkForm()
    {
        $dialogLink = new SelectDialog(true,false, false);
        $dialogLink->Id = "linkDialog2"; 
        $dialogLink->SelectFirstTab = true;
        return $dialogLink->LoadComponent();
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

    public function CreateTreeCss($search ="") {
        $content =  ContentVersion::GetInstance();
        $cssList = $content->GetCssList(self::$User->GetUserGroupId(),$this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }
    
    public function CreateTreeDiscusion($search ="") {
        
        $content =  ContentVersion::GetInstance();
        $cssList = $content->GetDiscusionList(self::$User->GetUserGroupId(),$this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }

    public function CreateTreeForms($search="") {
        $content =  ContentVersion::GetInstance();
        $cssList = $content->GetFormsList(self::$User->GetUserGroupId(),$this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }
    public function CreateTreeMailing($search="") {
        $content =  ContentVersion::GetInstance();
        $cssList = $content->GetMailingList(self::$User->GetUserGroupId(),$this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }

    public function CreateTreeMail($search="") {
        $content =  ContentVersion::GetInstance();
        $cssList = $content->GetMailList(self::$User->GetUserGroupId(), $this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }
    
    public function CreateTreeSendMail()
    {
        $content =  ContentVersion::GetInstance();
        $data = $content->GetSendMailList($this->WebId, $this->LangId);
        
        $header = array();
        $header["EmailText"] = $this->GetWord("word512");
        $header["EmailTo"] = $this->GetWord("word513");
        $header["EmailFrom"] = $this->GetWord("word514");
        $header["Time"] = $this->GetWord("word515");
        $header["IP"] = $this->GetWord("word516");
        return ArrayUtils::XmlToHtmlTable($data,"Data",array(),$header,true,"SendEmail",false,"Id","scrollTable1200");
    }
    public function CreateTreeTrash()
    {
        $content =  ContentVersion::GetInstance();
        $data = $content->GetDeletedObjects($this->WebId, $this->LangId);
        return $data;
    } 
    
            

    public function CreateTreeJs($search="") {
        $content =  ContentVersion::GetInstance();
        $cssList = $content->GetJsList(self::$User->GetUserGroupId(),$this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }

    public function CreateTree($search = "") {
        $content =  ContentVersion::GetInstance();
        $templatesList = $content->GetTemplateList(self::$User->GetUserGroupId(), $this->LangId,false,false,$search,"Name ASC");
        
        if (empty($templatesList))
        {
           $langInfo =  $content->GetTree($this->LangId,-1);
           $langInfo[0]["Name"] = $langInfo[0]["LangName"];
           $templatesList = $langInfo;
           
        }
        
        $html = $content->CreateHtml($templatesList);
        return $html;
    }

    public function FileFolder() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuFileRepository");
        $this->SetUserGroupList();
        $content =  ContentVersion::GetInstance();
        $id = $this->GetObjectId();


        
    if ($content->GetContentType($id) == ContentTypes::$FileUpload) {
        $this->GoToState("WebEdit", "FileUploader", "xadm", $this->WebId, $this->LangId, $id);
        }
        $data = $content->GetFileFolderDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        if (empty($data))
        {
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

    public function GetArticleDiscusion() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;

        $content =  ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        return $content->GetArticleDiscusion($id);
    }

    public function Discusion() {
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuDiscusionList");
        $userGroup = UserGroups::GetInstance();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
        $content =  ContentVersion::GetInstance();
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
        $discusion->DiscusionMode = \Types\DiscusionsMode::$AdminMode;
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
        $this->AddScript("/Scripts/ExternalApi/tinymce/tinymce.min.js");
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuWeb");
        $id = $this->GetObjectId();
        $content =  ContentVersion::GetInstance();
        
        $data = $content->GetUserItemDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        $createLangVersion = false;
        $lastEditLang = $this->GetLastEditLangVersion(false);
        
        if (empty($data))
        {
            
            $data = $content->GetUserItemDetail($id, self::$User->GetUserGroupId(), $this->WebId, $lastEditLang);
            if (!empty($data))
            {
                $createLangVersion = true;
            }
        }
        if (($id > 0 && empty($data)) || ($id == 0 && !$this->CanWriteParent($_GET["parentid"]))) {
            $this->GoToBack();
        }
        
        $mainData = array();
        if (empty($data[0]))
        {
            $nextPosition = $this->GetLastPosition("UserItem", $_GET["parentid"]);
            $mainData = array("Name" => "", "SeoUrl" => "", "Identificator" => "", "ActiveTo" => "", "ActiveFrom" => "", "AvailableOverSeoUrl" => 1, "NoIncludeSearch" => 0, "TemplateId" => "", "Data" => "", "ButtonSendForm" => "", "SendAdminEmail" => "","NoChild"=>"","UseTemplateInChild"=>"","ChildTemplate"=>"","CopyDataToChild"=>"","ActivatePager"=>0,"FirstItemLoadPager"=>"","NextItemLoadPager"=>"","NoLoadSubItems"=>0,"DiscusionId"=>0,"SaveToCache"=>1,"Sort"=>$nextPosition,"SortRule"=>"Position");
        }
        else {
            $mainData = $data[0];
        }
        if ($id == 0)
        {
            $parentDetail = $content->GetUserItemDetail($_GET["parentid"], self::$UserGroupId, $this->WebId, $this->LangId);
            if (!empty($parentDetail))
            {
                $parentDetailRow = $parentDetail[0];
                if ($parentDetailRow["UseTemplateInChild"] == 1)
                {
                    if (empty($parentDetailRow["ChildTemplateId"]))
                        $mainData["TemplateId"] = $parentDetailRow["TemplateId"];
                    else
                        $mainData["TemplateId"] = $parentDetailRow["ChildTemplateId"];
                }
                if ($parentDetailRow["CopyDataToChild"] == 1)
                {
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
        $this->SetTemplateData("UseTemplateInChild", $mainData["UseTemplateInChild"]== 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("CopyDataToChild", $mainData["CopyDataToChild"]== 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("NoLoadSubitems", $mainData["NoLoadSubItems"]== 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("ActivatePager", $mainData["ActivatePager"]== 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("SaveToCache", $mainData["SaveToCache"]== 1 ? 'checked= "checked"' : "");
        $this->SetTemplateData("FirstItemLoadPager", $mainData["FirstItemLoadPager"]);
        $this->SetTemplateData("NextItemLoadPager", $mainData["NextItemLoadPager"]);
        $this->SetTemplateData("Data", str_replace("\n", "", $mainData["Data"]));
        $this->SetTemplateData("GalleryId", !empty($mainData["GalleryId"]) ? $mainData["GalleryId"] : 0);
        $this->SetTemplateData("DiscusionSettings", !empty($mainData["DiscusionSettings"]) ? $mainData["DiscusionSettings"] : 0);
        $this->SetTemplateData("GallerySettings", !empty($mainData["GallerySettings"]) ? $mainData["GallerySettings"] : 0);
        $this->SetTemplateData("DiscusionId", empty($mainData["DiscusionId"])? 0: $mainData["DiscusionId"]);
        
        $this->SetTemplateData("SortRulePosition", $mainData["SortRule"] =="Postion" ? "selected =\"selected\"": "");
        $this->SetTemplateData("SortRuleName", $mainData["SortRule"] =="Name" ? "selected =\"selected\"": "");
        $this->SetTemplateData("SortRuleDate", $mainData["SortRule"] =="Date" ? "selected =\"selected\"": "");
        
        

        
        if ($createLangVersion)
        {
            $this->AutoCreateTemplate($mainData["TemplateId"], $lastEditLang);
            $this->CreateLangParent($mainData["ParentId"], $lastEditLang);
            $this->CreateConnectionFormLang($mainData["FormId"],$lastEditLang);
            $this->CreateConnectionGalleryLang($id,$lastEditLang);
        }
        $this->SetLangList($content,$id);
        $this->SetFormList(empty($mainData["FormId"]) ? 0: $mainData["FormId"]);
        $this->SetInqueryList(empty($mainData["Inquery"])? 0:$mainData["Inquery"]);
        $this->SetDiscusionList(empty($mainData["DiscusionId"])? 0: $mainData["DiscusionId"]);
        $this->SetHistoryList($id);
        $this->SetUserGroupList();
        $this->UserGroupList($data);
        //$this->SetDiscusion(empty($mainData["DiscusionSettings"])? 0: $mainData["DiscusionSettings"]);
        $this->SetGallerySettigns(empty($mainData["GallerySettings"]) ? 0:$mainData["GallerySettings"]);
        $this->SetTemplateList(empty($mainData["TemplateId"])?0:$mainData["TemplateId"],empty($mainData["ChildTemplateId"]) ? 0: $mainData["ChildTemplateId"]);
    }
    public function GetReletedArticle()
    {
        $dialog = new SelectDialog(true, false, false);
        $dialog->SelectFirstTab = true;
        $dialog->Id = "ReletedArticle";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
    }
    
    public function GetAlernativeArticle()
    {
        $dialog = new SelectDialog(true, false, false);
        $dialog->SelectFirstTab = true;
        $dialog->Id = "AlternativeArticle";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
    }
    
    public function GetObjectsXml()
    {
        $dialog = new SelectDialog(true, false, false);
        $dialog->Id = "SelectXml";
        $dialog->SelectFirstTab =true;
        $dialog->CssClass = $dialog->CssClass." noDatabase";
        $dialogHtml = $dialog->GetComponentHtml();
        
        return $dialogHtml;
    }   
    
    public function GetGalleryList()
    {
        $dialog = new SelectDialog(false, true, false);
        $dialog->SelectFirstTab = true; 
        $dialog->Id = "GalleryItemDialog";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
    }
    
    public function GetGalleryFromArticle()
    {
        $dialog = new SelectDialog(true, false, false);
        $dialog->SelectFirstTab = true; 
        $dialog->Id = "SelectDialogGalleryArticle";
        $dialogHtml = $dialog->LoadComponent();
        return $dialogHtml;
        /*$dialog3 = new SelectDialog(true, false, false);
        $dialog3->CssClass = "SelectDialogGalleryArticle";
        $dialog3->Id =  StringUtils::GenerateRandomString();
        $dialog3->SelectFirstTab = true;
        $dialogHtml3 = $dialog3->RenderHtml();
        return $dialogHtml3;*/
        
    }
    public function FormEditor() {
        $this->ExitQuestion = true;
        
        $this->SetStateTitle($this->GetWord("word236"));
        
        $this->SetLeftMenu("contentMenu", "contentMenuFormsList");
        $this->SetUserGroupList();
        $content =  ContentVersion::GetInstance();
        $id = $this->GetObjectId();
        
       
        $createLangVersion = false;
        $lastEditLang = $this->GetLastEditLangVersion();
        $data = $content->GetFormDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        if (empty($data))
        {
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
        $mailList = $content->GetMailList(self::$UserGroupId,$this->LangId, true,"","Name ASC");
        $this->SetTemplateData("MailListAdmin", $mailList);
        $this->SetTemplateData("MailListUser", $mailList);
        $templateListPDF = $content->GetTemplateList(self::$UserGroupId, $this->LangId, true,false,"","Name ASC");
        $this->SetTemplateData("TemplatePDF", $templateListPDF);
        $this->SetTemplateList($mainData["TemplateId"], 0);
        
        if ($createLangVersion)
        {
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
        $form = new \Kernel\Forms();
        $templateId = $mainData["TemplateId"];
        $htmlFormStatistic = $form->GetFormStatistic($id,$templateId);
        $this->SetTemplateData("FormStatistic", $htmlFormStatistic);
        $this->SetLangList($content, $id);
        
        $this->SetHistoryList($id);
        
        
    }

    public function MailEditor() {
        $this->ExitQuestion = true;
        $this->AddScript("/Scripts/ExternalApi/tinymce/tinymce.min.js");
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuMailList");
        $userGroup = UserGroups::GetInstance();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
        $content =  ContentVersion::GetInstance();
        $id = $this->GetObjectId();
        $data = $content->GetMailDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        if (empty($data))
        {
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
        $htmlEditor->HtmlEditorId="EmailText";
        $htmlEditor->Html = $data;
        $this->SetTemplateData("HtmlEditor", $htmlEditor->LoadComponent()); 
        
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
    }

    public function CssEditor() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word288"));
        $this->AddStyle("/Styles/show-hint.css");
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
       $this->AddScript("/Scripts/ExternalApi/css-hint.js");
        $this->SetLeftMenu("contentMenu", "contentMenuCss"); 
        $id =  $this->GetObjectId();
        $content =  ContentVersion::GetInstance();
        $data = $content->GetCssDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
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
        $this->SetLangList($content,$id);
        $this->SetHistoryList($id);
    }

    public function JsEditor() {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word496"));
        $this->SetLeftMenu("contentMenu", "contentMenuJs");
        $this->AddStyle("/Styles/codemirror.css");
        $this->AddStyle("/Styles/show-hint.css");
        $this->AddScript("/Scripts/ExternalApi/codemirror.js");
        $this->AddScript("/Scripts/ExternalApi/edit/matchbrackets.js");
        $this->AddScript("/Scripts/ExternalApi/comment/continuecomment.js");
        $this->AddScript("/Scripts/ExternalApi/comment/comment.js");
        $this->AddScript("/Scripts/ExternalApi/javascript.js");
        $this->AddScript("/Scripts/ExternalApi/javascript-hint.js");
        $id =  $this->GetObjectId();
        $content =  ContentVersion::GetInstance();
        $data = $content->GetJsDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        if (empty($data))
        {
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
        $this->AddStyle("/Styles/codemirror.css");
        $this->AddStyle("/Styles/show-hint.css");
        $this->AddScript("/Scripts/ExternalApi/codemirror.js");
        $this->AddScript("/Scripts/ExternalApi/show-hint.js");
        $this->AddScript("/Scripts/ExternalApi/xml-hint.js");
        $this->AddScript("/Scripts/ExternalApi/html-hint.js");
        $this->AddScript("/Scripts/ExternalApi/xml/xml.js");
        $this->AddScript("/Scripts/ExternalApi/javascript/javascript.js");
        $this->AddScript("/Scripts/ExternalApi/css/css.js");
        $this->AddScript("/Scripts/ExternalApi/htmlmixed/htmlmixed.js");
        $this->SetStateTitle($this->GetWord("word269"));
        $this->SetLeftMenu("contentMenu", "contentMenuTemplate");

        $id = $this->GetObjectId();
        $content =  ContentVersion::GetInstance();
        $data = $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId,$id, $this->GetVersionId());
        if (empty($data))
        {
            $data = $content->GetTemplateDetail(self::$User->GetUserGroupId(), $this->WebId, $this->LangId, $id);
        }
        $createLangVersion = false;
        $lastEditLang = $this->GetLastEditLangVersion();
        if (empty($data))
        {
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
            $ar = array("Name" => "", "Identificator" => "", "Data" => "", "Header" => "","ContentSettings" => "");
        else {
            $ar = (array) $data[0];

            $ar["Data"] = htmlentities($ar["Data"]);
            $domainId = $ar["DomainId"];
            $templateId = $ar["TemplateId"];
        }
        if ($createLangVersion)
        {
            $this->AutoCreateTemplate($templateId, $lastEditLang);
        }
        $this->SetTemplateDataArray($ar);
        $this->UserGroupList($data);
        $this->SetTemplateList($templateId, 0);
        
        $userDomains = UserDomains::GetInstance();
        $domainList = $userDomains->SelectByCondition();
        $domainList = $this->ReplaceHtmlWord($domainList);
        $domainList = ArrayUtils::SortArray($domainList,"DomainName",SORT_ASC);
        foreach ($domainList as $row) {
            $row["Selected"] = "";
            if ($row["Id"] == $domainId) {
                $row["Selected"] = 'selected = \"selected\"';
            }
        }
        $this->SetTemplateData("DomainList", $domainList);
        $this->SetLangList($content,$id);
        $this->SetHistoryList($id);
    }

    public function SaveTemplate($ajaxParametrs) {
        if (empty($ajaxParametrs))
            return;
        
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content =  ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        $templateSettings = $ajaxParametrs["TemplateSettings"];
        if ($id == 0)
            $id = $content->CreateTemplate($ajaxParametrs["Name"], $ajaxParametrs["Identificator"], $privileges, $ajaxParametrs["Content"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Domain"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $ajaxParametrs["TemplateHeader"],$templateSettings);
        else
            $id = $content->UpdateTemplate($id, $ajaxParametrs["Name"], $ajaxParametrs["Identificator"], $privileges, $ajaxParametrs["Content"], $ajaxParametrs["Domain"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $ajaxParametrs["TemplateHeader"],$templateSettings);
        return $id;
    }

    public function SaveCss() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content =  ContentVersion::GetInstance();

        $id = $ajaxParametrs["Id"];

        if ($id == 0) {
            $id = $content->CreateCss($ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"]);
        } else
            $id = $content->UpdateCss($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $ajaxParametrs["Publish"]);
        return $id;
    }

    public function SaveJs() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content =  ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];

        if ($id == 0) {
            $id = $content->CreateJs($ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"], $ajaxParametrs["Sort"]);
        } else
            $id = $content->UpdateJs($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["CssCode"], $ajaxParametrs["Publish"], $ajaxParametrs["Sort"]);
        return $id;
    }

    public function DeleteTemplate() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $content =  ContentVersion::GetInstance();
        return $content->DeleteItem($id) ? "TRUE" : "FALSE";
    }
    
    public function RecoveryItem()
    {
        $ajaxParametrs = array();
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        $id = $ajaxParametrs["Id"];
        $content =  ContentVersion::GetInstance();
        $content->RecoveryItem($id);
    }
            

    private function PreparePrivilegesList($data, $securityValue, $groupId, $securityType) {
            
        $ug = UserGroups::GetInstance();
        $groupList = $ug->GetUserGroups(array("system"));
        if (empty($data))
        {
            
            $web = \Model\Webs::GetInstance();
            $web->GetObjectById($this->WebId,true);
            $xml = $web->WebPrivileges;
            $ar = ArrayUtils::XmlToArray($xml);
            $data = $ar["item"];
            //$data = ArrayUtils::ValueAsKey($data, "UserGroup");
            //print_r($data);
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
                        $row[$rowname] = $drow["Value"] == "true"   ? TRUE : FALSE;
                   } 
                }
                
                
            }
            //print_r($groupList);
            
            //$data = ArrayUtils::RenameColumn($data, "UserGroup", "Id");
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
                if (!empty($drow[$groupId]))
                {
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

    public function SaveUserItem() {
        $ajaxParametrs = array();
        
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $noChild = $ajaxParametrs["NoChild"] == 1 ? true: false;
        $useTemplateInChild = $ajaxParametrs["UseTemplateInChild"] == "1" || $ajaxParametrs["UseTemplateInChild"] == 1 ? true: false;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content =  ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        $data = $ajaxParametrs["Parametrs"];
        unset($ajaxParametrs["Parametrs"]);
        if ($id == 0) {
            $id = $content->CreateUserItem($ajaxParametrs["NameObject"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $_GET["langid"], $_GET["param1"], $privileges, $data,false, $ajaxParametrs["GallerySettings"], $ajaxParametrs["Discusion"] == 0 ? 0: 1, $ajaxParametrs["Discusion"], $ajaxParametrs["FormSettings"],$noChild,$useTemplateInChild,$ajaxParametrs["ChildTemplate"],$ajaxParametrs["CopyDataToChild"],$ajaxParametrs["ActivatePager"],$ajaxParametrs["FirstItemLoadPager"],$ajaxParametrs["NextItemLoadPager"],$ajaxParametrs["InquerySettings"],$ajaxParametrs["NoLoadSubitems"],$ajaxParametrs["SaveToCache"],$ajaxParametrs["Sort"],$ajaxParametrs["SortRule"]);
        } else {
            $id = $content->UpdateUserItem($id, $ajaxParametrs["NameObject"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $privileges, $data,$ajaxParametrs["GallerySettings"], $ajaxParametrs["Discusion"] == 0 ? 0: 1, $ajaxParametrs["Discusion"], $ajaxParametrs["FormSettings"],$noChild,$useTemplateInChild,$ajaxParametrs["ChildTemplate"],$ajaxParametrs["CopyDataToChild"],$ajaxParametrs["ActivatePager"],$ajaxParametrs["FirstItemLoadPager"],$ajaxParametrs["NextItemLoadPager"],$ajaxParametrs["InquerySettings"],$ajaxParametrs["NoLoadSubitems"],$ajaxParametrs["SaveToCache"],$ajaxParametrs["Sort"],$ajaxParametrs["SortRule"]);
        }
        return $id;
    }

    public function SaveForm() {

        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content =  ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        $data = $ajaxParametrs["Parametrs"];

        unset($ajaxParametrs["Parametrs"]);
        if ($id == 0) {
            $id = $content->CreateForm($ajaxParametrs["Name"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $_GET["langid"], $_GET["param1"], $privileges, $data);
        } else {
            $id = $content->UpdateForm($id, $ajaxParametrs["Name"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $ajaxParametrs["Template"], $ajaxParametrs["Publish"], $privileges, $data);
        }
        return $id;
    }

    public function SaveEmail() {

        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content =  ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];

        if ($id == 0) {
            $id = $content->CreateMail($ajaxParametrs["Name"], $_GET["langid"], $_GET["param1"], $privileges, $ajaxParametrs["EmailText"],$ajaxParametrs["Publish"]);
        } else {
            $id = $content->UpdateMail($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["EmailText"],$ajaxParametrs["Publish"]);
        }
        return $id;
    }

    public function GetDomainFromTemplate() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        
        if (empty($ajaxParametrs))
            return;
        $content =  ContentVersion::GetInstance();
        
        $templateId = $ajaxParametrs["Id"];
        $domain = UserDomainsItems::GetInstance();
        $identificator = $domain->GetUserDomainByTemplateId($templateId);
        $data = "";
        if (!empty($ajaxParametrs["ObjectId"]))
        {
            $objectId  = $ajaxParametrs["ObjectId"];
            $tmp = $content->GetUserItemDetail($objectId, self::$UserGroupId, $this->WebId, $this->LangId);
            if (!empty($tmp))
                $data = $tmp[0]["Data"];
            if (empty($data))
            {
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
        
        $out["TemplateSettings"]= empty($templateInfo) ?"" : $templateInfo[0]["ContentSettings"];
        $out["Html"] =  $this->GetUserDomain($identificator, 0, false, $data, $readOnly);
        
        return $out;
    }

    public function GetDomainByIdentificator() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);

        if (empty($ajaxParametrs))
            return;
        $identificator = $ajaxParametrs["Identifcator"];
        $data = "";
        $content =  ContentVersion::GetInstance();
        if (!empty($ajaxParametrs["ObjectId"]))
        {
            $objectId  = $ajaxParametrs["ObjectId"];
            $tmp = $content->GetFileFolderDetail($objectId, self::$UserGroupId, $this->WebId, $this->LangId);
            if (!empty($tmp))
                $data = $tmp[0]["Data"];
            if (empty($data))
            {
                $lastEditLang = $this->GetLastEditLangVersion(true);
                $tmp = $content->GetFileFolderDetail($objectId, self::$UserGroupId, $this->WebId, $lastEditLang);
                if (!empty($tmp))
                    $data = $tmp[0]["Data"];
            }
        }
        
        
        return $this->GetUserDomain($identificator, 0, false, $data);
    }

    public function MoveItem() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);

        if (empty($ajaxParametrs))
            return;
        

        $sourceId = $ajaxParametrs["sourceId"];

        $destinationId = $ajaxParametrs["destinationId"];
        $contentVersion =  ContentVersion::GetInstance();
        return $contentVersion->Move($sourceId, $destinationId) ? "TRUE" : "FALSE";
    }

    public function CopyItem() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);

        if (empty($ajaxParametrs))
            return;


        $sourceId = $ajaxParametrs["sourceId"];

        $destinationId = $ajaxParametrs["destinationId"];
        $contentVersion =  ContentVersion::GetInstance();
        return $contentVersion->Copy($_GET["langid"], $_GET["webid"], $sourceId, $destinationId) ? "TRUE" : "FALSE";
    }

    public function FileUploader() {
        $this->ExitQuestion = true;
        $isNew = false;
        $this->SetLeftMenu("contentMenu","contentMenuFileRepository");
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetUserGroupList();
        $content =  ContentVersion::GetInstance();
        $id = $this->GetObjectId();    
        $data = $content->GetFileFolderDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        if (empty($data))
        {
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

    public function SaveFolderFile() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        if ($id == 0) {
            $id = $content->CreateFileFolder($ajaxParametrs["Name"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $_GET["langid"], $_GET["param1"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $privileges);
        } else {
            $id = $content->UpdateFileFolder($id, $ajaxParametrs["Name"], $ajaxParametrs["SeoUrl"], $ajaxParametrs["AvailableOverSeoUrl"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $privileges);
        }
        return $id;
    }

    public function SaveFile() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        $data = $ajaxParametrs["Parametrs"];
        if ($id == 0) {
            $id = $content->CreateFile($ajaxParametrs["Name"], $_GET["langid"], $_GET["param1"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $privileges, $data);
        } else {
            $id = $content->UpdateFile($id, $ajaxParametrs["Name"], $ajaxParametrs["NoIncludeSearch"], $ajaxParametrs["Identificator"], $ajaxParametrs["ActiveFrom"], $ajaxParametrs["ActiveTo"], $privileges, $data);
        }
        return $id;
    }

    public function ConnectObject() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $conObj =  ContentConnection::GetInstance();
        $conObj->CreateConnection($ajaxParametrs["ObjectId"], $ajaxParametrs["ObjectIdConnection"], $ajaxParametrs["Mode"], $ajaxParametrs["Data"]);
    }

    public function GetRelatedObject() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $conObj = ContentConnection::GetInstance();
        return $conObj->GetRelatedObject($ajaxParametrs["ObjectId"], $_GET["langid"], $ajaxParametrs["ObjectType"]);
    }

    public function DisconnectObject() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);

        if (empty($ajaxParametrs))
            return;
        $conObj = ContentConnection::GetInstance();
        $id = $ajaxParametrs["ObjectId"];
        $conObj->DeleteObject($id, true,false);
    }

    

    

    

    

    

    public function BlockDiscusionUser() {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["UserId"];

        self::$User->BlockDiscusionUser($id);
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
        $contentSecurity =  ContentSecurity::GetInstance();
        
        return $contentSecurity->CanPrivileges($parentId, self::$UserGroupId, "canWrite");
    }
    public function ChangeLangVersion()
    {
        self::$SessionManager->SetSessionValue("lastEditLang", $this->LangId);
    }
    private function GetLastEditLangVersion($clear = true)
    {
        $lang = 0;
        if (!self::$SessionManager->IsEmpty("lastEditLang"))
        {
            $lang = self::$SessionManager->GetSessionValue("lastEditLang");
            if ($clear)
            {
                self::$SessionManager->UnsetKey("lastEditLang");
            }
        }
        return $lang;
    }
    private function SetLangList($content,$id)
    {
        $lang = Langs::GetInstance();
        $langList = $lang->Select();
        
        foreach ($langList as $row)
        {
            $exist = $content->ItemExistsInLang($id,$row["Id"]);
            $row["Selected"] = $row["Id"] == $this->LangId ? 'selected = \"selected\"' :"";
            if($exist)
            {
                $row["LangName"] = $row["LangName"]." - ".$this->GetWord("word504");
            }
            else 
            {
                $row["LangName"] = $row["LangName"]." - ".$this->GetWord("word505");
            }
        }
        $this->SetTemplateData("ItemLangList", $langList);
    }
    private function AutoCreateTemplate($templateId,$sourceLangId)
    {
        if ($templateId == 0)
            return;
        $content = ContentVersion::GetInstance();
        if (!$content->ItemExistsInLang($templateId, $this->LangId))
        {
            $templateData = $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $sourceLangId, $templateId);
            $row = $templateData[0];
            $content->CreateVersion($templateId, $row["Name"], $row["IsActive"], self::$UserId, "", $row["TemplateId"], false, $this->LangId, $row["Data"], $row["Header"], "", "", true,"");
            if ($row["TemplateId"] > 0)    
            {
                $this->AutoCreateTemplate($row["TemplateId"], $sourceLangId);
            }
        }
    }
    
    private function CreateLangParent($id,$sourceLang)
    {
        if ($id == 0)
            return;
        $content = ContentVersion::GetInstance();
        if (!$content->ItemExistsInLang($id, $this->LangId))
        {
            $userItem = $content->GetUserItemDetail($id, self::$UserGroupId, $this->WebId, $sourceLang);
            $row = $userItem[0];
            $content->CreateVersion($id, $row["Name"], true, self::$UserId, $row["SeoUrl"], $row["TemplateId"], $row["AvailableOverSeoUrl"], $this->LangId, $row["Data"], "", $row["ActiveFrom"], $row["ActiveTo"], true, "");
            if ($row["ParentId"] > 1)
            {
                $this->CreateLangParent($row["ParentId"], $sourceLang);
            }
        }
    }
    
    private function CreateConnectionFormLang($id,$sourceLang)
    {
        if ($id == 0)
            return;
        $content = ContentVersion::GetInstance();
        if (!$content->ItemExistsInLang($id, $this->LangId))
        {
            $userItem = $content->GetFormDetail($id, self::$UserGroupId, $this->WebId, $sourceLang);
            $row = $userItem[0];
            $content->CreateVersion($id, $row["Name"], $row["IsActive"], self::$UserId, $row["SeoUrl"], $row["TemplateId"], $row["AvailableOverSeoUrl"], $this->LangId, $row["Data"], "", $row["ActiveFrom"], $row["ActiveTo"], true, "");
        }        
    }
    
    private function CreateConnectionGalleryLang($id,$sourceLang)
    {
        $content = ContentConnection::GetInstance();
        $data = $content->GetRelatedObject($id, $sourceLang);
        //print_r($data);
        
    }
    public function ReSendMails()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        $mailsid = $ajaxParametrs["Mails"];
        $mail = new Mail();
        foreach ($mailsid as $mailid)
        {
            if (!empty($mailid))
                $mail->SendEmailById($mailid);
        }
    }
    
    public function GetActualTree()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $type = $ajaxParametrs["Type"];
        
        $search = "";
        if (!empty($ajaxParametrs["Search"]))
        {
            $search =$ajaxParametrs["Search"];
        }
        
        
        $html = "";
        switch ($type)
        {
            case "useritem":
                $contentVersion = ContentVersion::GetInstance();
                $tree = $contentVersion->GetTree($this->LangId,0,$search);
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
                $contentVersion =  ContentVersion::GetInstance();
                $tree = $contentVersion->GetFileTree($this->LangId,0,$search);
                $html = $contentVersion->CreateHtml($tree);
                break;
            
        }
        return $html;
    }
    
    public function GetArticleUrl()
    {
        
        $id = $_POST["params"];
        $lang = Langs::GetInstance();
        $lang->GetObjectById($this->LangId,true);
        $content = ContentVersion::GetInstance();
        $detail = $content->GetUserItemDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        return $lang->RootUrl."preview/".$detail[0]["SeoUrl"]."/";        
    }
    public function MoveItemFolder()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        /** @var \Model\ContentVersion */
        $content = ContentVersion::GetInstance();
        $mode = $ajaxParametrs["Mode"];
        $id = $ajaxParametrs["Id"];
        $contentType = $ajaxParametrs["ContentType"];
        $content->SetPosition($id, $mode,$contentType);
    }
    
    public function GetLinkDetail()
    {
        $id = $_GET["params"];
        $content = ContentVersion::GetInstance();
        $res =  $content->GetLinkDetail($id,$this->WebId,$this->LangId);
        $xml = $res[0]["Data"];
        $ar = ArrayUtils::XmlToArray($xml);
        $ar = $ar["item"];
        $ar["Name"] = $res[0]["Name"];
        $ar["Url"] = $res[0]["SeoUrl"];
        return $ar;
        
    }
    
    private function GetVersionId()
    {
        return empty($_GET["versionId"]) ? 0 : $_GET["versionId"];
    }
    
    public function NoPublishItems()
    {
        $this->SetStateTitle($this->GetWord("word539"));
        $content = ContentVersion::GetInstance();
        $items = $content->GetNoPublishItems($this->LangId);
        $this->SetTemplateData("items", $items);
    }
    public function PublishItem()
    {
        $id = $_POST["params"];
        $content = ContentVersion::GetInstance();
        return $content->PublishItem($id,$this->LangId)? "true":"false";
    }
    public function MailingDetail()
    { 
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word236"));
        $this->SetLeftMenu("contentMenu", "contentMenuMailing");
        $this->SetUserGroupList();
        $content = ContentVersion::GetInstance();
        $id = $this->GetObjectId();
        
        $data = $content->GetMailingDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        if (empty($data))
        {
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
        $mailList = $content->GetMailList(self::$UserGroupId, $this->LangId, true,"","Name ASC");
        $this->SetTemplateData("MailList", $mailList);
        $ud = UserDomains::GetInstance();
        $domainDetail = $ud->GetDomainInfo("Mailinggroups");
        $udv = UserDomainsValues::GetInstance();
        $mailingGroups = $udv->GetDomainValueList($domainDetail["Id"]);
        $mailingGroups = ArrayUtils::SortArray($mailingGroups, "MailingGroupName", SORT_ASC);   
        $this->SetTemplateData("MailingGroup", $mailingGroups);
        
    }
    public function SaveMailing()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];

        if ($id == 0) {
            $id = $content->CreateMailing($ajaxParametrs["Name"], $_GET["langid"], $_GET["param1"], $privileges, $ajaxParametrs["MailingParametrs"],$ajaxParametrs["Publish"]);
        } else {
            $id = $content->UpdateMailing($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["MailingParametrs"],$ajaxParametrs["Publish"]);
        }
        return $id;
        
    }
    
    /*public function MailingContacts()
    {
        $this->SetStateTitle($this->GetWord("word551"));
        $this->SetLeftMenu("contentMenu", "contentMenuMailing");
        $mailinig = new  MailingContacts();
        $contacts = $mailinig->GetMailingList($this->WebId);
        $this->SetTemplateData("contacts", $contacts);
        $ud = new UserDomains();
        $info = $ud->GetDomainInfo("Mailinggroups");
        $udv = new UserDomainsValues();
        $values = $udv->GetDominValueList($info["Id"], false);
        $this->SetTemplateData("MailingGroups", $values);
    }*/
    
    /*public function GetMailingItemDetail()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $out = array();
        $mailinig = new  MailingContacts();
        $detail = $mailinig->GetMailingDetail($ajaxParametrs["Id"]);
        $malingGroups = $mailinig->GetUserMailingGroups($ajaxParametrs["Id"]);
        $out["Detail"] = $detail[0];
        $out["MailingGroups"] = $malingGroups;
        return $out;
    }*/
    
    /*public function SaveMailinContact()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = empty($ajaxParametrs["Id"]) ? 0:$ajaxParametrs["Id"];
        $mailinig = new  MailingContacts();
        
        
        if ($id == 0)
        {
          $id =   $mailinig->AddContact($ajaxParametrs["Email"]);
        }
        else 
        {
           $id = $mailinig->UpdateContact($id,$ajaxParametrs["Email"]);
        }
        $mg = new MailingContactsInGroups();
        
        $mg->AddContactToMailingGroup($id, $ajaxParametrs["MailingGroups"]);   
    }*/
    
    
    public function DeteleteMailing()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = empty($ajaxParametrs["Id"]) ? 0:$ajaxParametrs["Id"];
        $mailinig = MailingContacts::GetInstance();
        $mailinig->DeleteObject($id);
    }
    
    public function SendMailing()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["ObjectId"];
        $emailid = $ajaxParametrs["Email"];
        $mailingGroupId = $ajaxParametrs["MailingGroup"];
        $from = $ajaxParametrs["MailSender"];
        $mailing = ContentVersion::GetInstance();
        $mailing->SendMailing($id,self::$UserGroupId,$this->WebId,$this->LangId,$emailid,$mailingGroupId,$from);
        
    }
    public function DataSource()
    {
        $this->SetStateTitle($this->GetWord("word565"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeDataSource());
        
    }
    public function CreateTreeDataSource($search ="") {
        $content = new ContentVersion();
        $cssList = $content->GetDataSourceList(self::$User->GetUserGroupId(),$this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }
    public function DataSourceDetail()
    {
        $this->ExitQuestion =true;
        $this->SetStateTitle($this->GetWord("word566"));
        $this->SetLeftMenu("contentMenu", "contentMenuDatasource");
        $this->AddStyle("/Styles/codemirror.css");
        $this->AddStyle("/Styles/show-hint.css");
        $this->AddScript("/Scripts/ExternalApi/codemirror.js");
        $this->AddScript("/Scripts/ExternalApi/show-hint.js");
        $this->AddScript("/Scripts/ExternalApi/xml-hint.js");
        $this->AddScript("/Scripts/ExternalApi/html-hint.js");
        $this->AddScript("/Scripts/ExternalApi/xml/xml.js");
        $this->AddScript("/Scripts/ExternalApi/javascript/javascript.js");
        $this->AddScript("/Scripts/ExternalApi/css/css.js");
        $this->AddScript("/Scripts/ExternalApi/htmlmixed/htmlmixed.js");
        $templateData = array();
        $id = $this->GetObjectId();
        $content = ContentVersion::GetInstance();
        $data = $content->GetDataSourceDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
        if (empty($data))
        {
            $data = $content->GetDataSourceDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->GetLastEditLangVersion());
        }
        $domainId = 0;
        $templateId = 0;
        if ($id > 0 && empty($data)) {
            $this->GoToBack();
        }
        
        $ar = array();
        if (empty($data))
            $ar = array("Name" => "", "Data" => "", "Sort" => "","SeoUrl"=>"","LastVisited"=>"");
        else {
            $ar = (array) $data[0];
            $domainId = $ar["DomainId"];
            $templateId = $ar["TemplateId"];
        }
        $this->UserGroupList($data);
        $ar["Data"] =  StringUtils::RemoveString($ar["Data"], "\n");
        $ar["Data"] =  StringUtils::RemoveString($ar["Data"], "\r");
        
        $this->SetTemplateDataArray($ar);
        $this->SetLangList($content, $id);
        $this->SetHistoryList($id);
        $userDomains = UserDomains::GetInstance();
        $domainList = $userDomains->SelectByCondition("EditValue = 1");
        $domainList = ArrayUtils::SortArray($domainList,"DomainName",SORT_ASC);
        $this->SetTemplateData("DomainList", $domainList);
        
    }
        public function SaveDataSource() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
        {
            return;
        }
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        
        
        if ($id == 0) {
            $id = $content->CreateDataSource($ajaxParametrs["Name"], $privileges, $ajaxParametrs["SeoUrl"],$ajaxParametrs["Data"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"]);
        } else
        {
            $id = $content->UpdateDataSource($id, $ajaxParametrs["Name"], $privileges,$ajaxParametrs["SeoUrl"], $ajaxParametrs["Data"], $ajaxParametrs["Publish"]);
        }
        return $id;
    }
    
    public function GetDomainColumns()
    {
        $ui = UserDomainsItems::GetInstance();
        return $ui->GetUserDomainItemById($_GET["params"]);
    }
    public function GetSelectedObjectName()
    {
        $content = ContentVersion::GetInstance();
        return $content->GetNameObject($_POST["params"], $this->LangId);
    }
    
    public function GetDomainIdByUserItemId()
    {
        $content = ContentVersion::GetInstance();
        return $content->GetUserItemDomainId($_POST["params"]);
    }
    
    public function GetDomainItems()
    {
        $domainId = $_GET["params"];
        $udi = UserDomainsItems::GetInstance();
        $res = $udi->GetUserDomainItemById($domainId);
        return $res;
    }
    public function CallDataSourceImport()
    {
        $content = ContentVersion::GetInstance();
        $data = array();
        $rootUrl = $this->GetRoorUrl();
        $data = $content->GetDataSourceDetail($_POST["params"], self::$UserGroupId, $this->WebId, $this->LangId);
        $rootUrl = $rootUrl."xmlimport/";
        $url = $rootUrl.$data[0]["SeoUrl"]."/";
        $this->CallUrl($url,"?login=system&pswrd=sd15kl20");
    }
    
    public function CheckFile()
    {
        
        $content = ContentVersion::GetInstance();
        $data = array();
        $rootUrl = $this->GetRoorUrl();
        $data = $content->GetDataSourceDetail($_POST["params"], self::$UserGroupId, $this->WebId, $this->LangId);
        $rootUrl = $rootUrl."checkxmlimport/";
        $url = $rootUrl.$data[0]["SeoUrl"]."/";
        $this->CallUrl($url);
    }
    public function CallDataSourceExport()
    {
        $content = ContentVersion::GetInstance();
        $data = $content->GetDataSourceDetail($_POST["params"], self::$UserGroupId, $this->WebId, $this->LangId);
        $rooturl = $this->GetRoorUrl()."xmldownload/";
        return $rooturl.$data[0]["SeoUrl"].".xml";
    }
    
    public function GetObjectData()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $type = $ajaxParametrs["Type"];
        $id = $ajaxParametrs["Id"];
        
        $content = ContentVersion::GetInstance();
        $data = array();
        switch ($type)
        {
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
    public function GenerateXmlItem()
    {
        $content = ContentVersion::GetInstance();
        $data = $content->GenerateXmlItem($_POST["params"],$this->LangId,self::$UserGroupId,$this->WebId);
        return $data;   
    }
    
    public function InquryList()
    {
        $this->SetStateTitle($this->GetWord("word613"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeInqury());
    }
    
    public function CreateTreeInqury($search ="") {
        $content = ContentVersion::GetInstance();
        $cssList = $content->GetInquryList(self::$User->GetUserGroupId(),$this->LangId,false,$search);
        $html = $content->CreateHtml($cssList);
        return $html;
    }
    
    public function InqueryDetail()
    {
        $this->ExitQuestion = true;
        $this->SetStateTitle($this->GetWord("word615"));
        $this->SetLeftMenu("contentMenu", "contentMenuInqury");
        $id =  $this->GetObjectId(); 
        $content = ContentVersion::GetInstance();
        $data = $content->GetInqueryDetail($id, self::$User->GetUserGroupId(), $this->WebId, $this->LangId,$this->GetVersionId());
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
        $this->SetLangList($content,$id);
        $this->SetHistoryList($id);
        $form = new \Kernel\Forms();
        $htmlStatistic = $form->GenerateSurveyStatistic($id);
        $this->SetTemplateData("Statistic", $htmlStatistic);
    }
    
    public function SaveIquery()
    {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"]; 
        unset($ajaxParametrs["Privileges"]);
        $content = ContentVersion::GetInstance();
        $id = $ajaxParametrs["Id"];
        if ($id == 0) {
            $id = $content->CreateInquery($ajaxParametrs["Name"], $privileges, $ajaxParametrs["Data"], $_GET["param1"], $_GET["langid"], $ajaxParametrs["Publish"]);
        } else
            $id = $content->UpdateInquery($id, $ajaxParametrs["Name"], $privileges, $ajaxParametrs["Data"], $ajaxParametrs["Publish"]);
        return $id;
    }
    
    public function AddAlternativeItem()
    {
        $params = $this->PrepareAjaxParametrs();
        if (empty($params)) return;
        $objectId = $params["ObjectId"];
        $userGroup = $params["UserGroup"];
        $alternativeItemId = $params["AlternativeItem"];
        $alternativeItem =  ContentAlternative::GetInstance();
        $alternativeItem->SaveAlternativeItem($objectId, $userGroup, $alternativeItemId);
    }
    
   public function GetAlternativeItem()
   {
       $content = ContentVersion::GetInstance();
       return $content->GetAlternativeItems($_GET["params"],$this->LangId);
   }
   
   public function GetFormItemDetail()
   {
       $id = $_GET["params"];
       $form = new \Kernel\Forms();
       return $form->GetFormItemDetail($id);
   }
   
   public function DeleteAlternativeItems()
   {
       $id = $_GET["params"];
       $contentAlernative = ContentAlternative::GetInstance();
       $contentAlernative->DeleteObject($id,true,false);
    }
    
/// funkce pro contnet
    private function SetUserGroupList()
    {
        $userGroup = UserGroups::GetInstance();
        $userGroupList = $userGroup->GetUserGroups(array("system"));
        $this->SetTemplateData("GroupList", $userGroupList);
    }
    private function GetObjectId()
    {
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
    private function SetHistoryList($id)
    {
        $content = ContentVersion::GetInstance();
        $history = $content->GetObjectHistoryList($id, $this->WebId, $this->LangId);
        $this->SetTemplateData("HistoryList", $history);
    }
    private function SetInqueryList($inqueryId )
    {
        $content = ContentVersion::GetInstance();
        $inguery = $content->GetInquryList(self::$UserGroupId,  $this->LangId, true,"","Name collate utf8_czech_ci");
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
    
    private function SetDiscusionList($connId)
    {
        $content = ContentVersion::GetInstance();
        $inguery = $content->GetDiscusionList(self::$UserGroupId,  $this->LangId, true,"","Name collate utf8_czech_ci");
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
    private function SetFormList($formId)
    {
        $content = ContentVersion::GetInstance();
        $formList = $content->GetFormsList(self::$UserGroupId,  $this->LangId, true,"","Name collate utf8_czech_ci");
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
    private function UserGroupList($data)
    { 
        $userGroupList = $this->PreparePrivilegesList($data, "SSValue", "SSGroupId", "SSSecurityType");
        
        $this->SetPrivileges($userGroupList);
        $this->SetTemplateData("GroupList", $userGroupList);
    }
    /*private function SetDiscusion($discusionSettings)
    {
        for ($i = 0; $i <= 3; $i++) {
            $selectGallerySetting = "";
            if ($i == (!empty($discusionSettings) ? $discusionSettings : 0)) {
                $selectGallerySetting = "selected = 'selected'";
            }
            $this->SetTemplateData("discusionSettings" . $i, $selectGallerySetting);
        }
    }*/
    private function SetGallerySettigns($gallerySettings)
    {
        for ($i = 0; $i <= 3; $i++) {
            $selectGallerySetting = "";
            if ($i == (!empty($gallerySettings) ? $gallerySettings : 0)) {
                $selectGallerySetting = "selected = 'selected'";
            }
            $this->SetTemplateData("mediaGallerySettings" . $i, $selectGallerySetting);
        }
    }
    private function SetTemplateList($templateId,$childTemplateId)
    {
        $content = ContentVersion::GetInstance();
        $templateList = $content->GetTemplateList(self::$UserGroupId,$this->LangId, true, false,"","Name collate utf8_czech_ci");
        foreach ($templateList as $row) {
            $row["Selected"] = "";
            $row["SelectedChild"] = "";
            if (!empty($templateId)) {
                if ($this->ParseInt(($row["Id"])) === $this->ParseInt(($templateId))) {
                    $row["Selected"] = 'selected = "selected"';
                 
                }

            }
            if (!empty($childTemplateId))
            {
                $row["SelectedChild"] ="";
                if ($this->ParseInt(($row["Id"])) === $this->ParseInt(($childTemplateId))) 
                {
                    $row["SelectedChild"] = 'selected = "selected"';
                }
            }
        }
        $this->SetTemplateData("TemplateList", $templateList);
    }
    
    public function DiscusionList()
    {
        $this->SetStateTitle($this->GetWord("word251"));
        $this->SetTemplateData("url", "/ajax/WebEdit/CreateTree/JSON/$this->WebId/$this->LangId/");
        $this->SetTemplateData("tree", $this->CreateTreeDiscusion());
    }
    
    public function SaveDiscusion()
    {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs))
            return;
        $privileges = $ajaxParametrs["Privileges"];
        unset($ajaxParametrs["Privileges"]);
        $content = ContentVersion::GetInstance();
        $id = $ajaxParametrs["ObjectId"];
        if ($id == 0)
        {
            return $content->CreateDiscusion($ajaxParametrs["NameObject"],$privileges,$_GET["param1"], $_GET["langid"]);
        }
        else 
        {
            return $content->UpdateDiscusion($id,$ajaxParametrs["NameObject"],$privileges);
        }
    }
    
    public function FileManager()
    {
        $this->SetStateTitle($this->GetWord("word102"));
        $contentVersion =  ContentVersion::GetInstance();
        $tree = $contentVersion->GetFileTree($_GET["langid"]);
        $html = $contentVersion->CreateHtml($tree);
        $this->SetTemplateData("tree", $html);
        
    }
    
    public function GetRootId()
    {
        /** @var 
          \Model\ContentVersion
         */ 
        $content =  ContentVersion::GetInstance();
        $folderId = $content->GetIdByIdentificator("langfolder",$_GET["webid"]);
        return $folderId;
        
    }
    
    public function DeleteLangVersion()
    {
        /** @var 
          \Model\ContentVersion
         */ 
       
        $content =  ContentVersion::GetInstance();
        $content->DeleteLangVersion($_GET["params"],$_GET["langid"]);
        
    }
    
    private function GetLastPosition($contentType,$parentId)
    {
        /** @var \Model\Content */
        $content = \Model\Content::GetInstance(); 
        return $content->GetMaxValue("Sort","ContentType = '$contentType' AND ParentId = $parentId AND Sort <> 99999  AND Deleted = 0")+1;
        
    }
            
}