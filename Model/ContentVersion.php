<?php

namespace Model;

use Utils\ArrayUtils;
use Utils\StringUtils;
use Types\ContentTypes;
use HtmlComponents\HtmlTable;
use HtmlComponents\HtmlTableTd;
use HtmlComponents\HtmlTableTh;
use HtmlComponents\HtmlTableTr;
use HtmlComponents\Link;
use HtmlComponents\FontAwesome;
use Types\RuleType;
use Types\PrivilegesType;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
use Types\xWebExceptions;
use Types\LinkType;
use Utils\Mail;
use Utils\Utils;
use Kernel;
use Kernel\Image;

class ContentVersion extends DatabaseTable {

    public $ContentId;
    public $Name;
    public $IsActive;
    public $IsLast;
    public $Author;
    public $SeoUrl;
    public $Template;
    public $AvailableOverSeoUrl;
    public $Data;
    public $Header;
    public $ActiveTo;
    public $ActiveFrom;
    public $Date;
    public $PublishUser;
    public $ContentSettings;
    //private static $_instance = null;

    public function __construct() {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentVersion";
        $this->MultiLang = true;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ContentId","Name","IsActive","IsLast","Author","SeoUrl","Template","AvailableOverSeoUrl","Data","Header","ActiveTo","ActiveFrom","Date","PublishUser","ContentSettings"));
        $this->SetDefaultSelectColumns();
    }
    

    private function IsLink($id) {
        $content = Content::GetInstance();
        $content->GetObjectById($id, true);
        return $content->ContentType == ContentTypes::$ExternalLink || $content->ContentType == ContentTypes::$Link || $content->ContentType == ContentTypes::$JavascriptAction ? true : false;
    }

    private function IsLinkByType($type) {
        return $type == ContentTypes::$ExternalLink || $type == ContentTypes::$Link || $type == ContentTypes::$JavascriptAction ? true : false;
    }

    private function CanHaveChild($id) {
        $content = Content::GetInstance();
        $content->GetObjectById($id, true);
        if ($content->ContentType == ContentTypes::$Css || $content->ContentType == ContentTypes::$Javascript || $content->ContentType == ContentTypes::$Form ||
                $content->ContentType == ContentTypes::$Mail || $content->ContentType == ContentTypes::$Mailing || $content->ContentType == ContentTypes::$DataSource || $content->ContentType == ContentTypes::$JavascriptAction || $content->ContentType == ContentTypes::$Template
        )
            return false;
        if ($content->ContentType == ContentTypes::$FormStatistic)
            return true;
        return $content->NoChild ? false : true;
    }

    public function GetParent($id) {
        $content = Content::GetInstance();
        $content->GetObjectById($id, true);
        return $content->ParentId;
    }

    public function CreateContentItem($name, $isActive, $seoUrl, $template, $contentType, $AvailableOverSeoUrl, $lang, $parentid, $noIncludeSearch = true, $identificator = "", $privileges = array(), $data = "", $domainId = 0, $templateId = 0, $header = "", $activeFrom = "", $activeTo = "", $gallerySettings = 0, $discusionSettings = 0, $connectDiscusion = 0, $sort = 99999, $formId = 0, $testPrivileges = true, $dataArray = array(), $noChild = false, $useTemplateInChild = false, $childTemplate = 0, $copyDataToChild = false, $ActivatePager = false, $FirstItemLoadPager = 0, $NextItemLoadPager = 0, $inquery = 0, $noLoadSubItems = 0, $settings = "",$caching = false,$sortRule="") {
        try{
            
        dibi::begin();
        if ($this->IsLink($parentid) || (!$this->CanHaveChild($parentid) && ($contentType != ContentTypes::$FormStatistic && $contentType != ContentTypes::$SurveyAnswer) )) {
            
            $parentid = $this->GetParent($parentid);
        }
        


        if (!$this->HasPrivileges($parentid, PrivilegesType::$CanWrite) && $testPrivileges) {
            dibi::rollback();
            return 0;
        }
        
        /** @var  \Model\Content */
        $content = Content::GetInstance();
        $user = Users::GetInstance();
        $userId = $user->GetUserId();
        $content->ContentType = $contentType;
        $content->Sort = $sort;
        $content->ParentId = $parentid;
        $content->NoIncludeSearch = $noIncludeSearch;
        $content->Identificator = $identificator;
        $content->DomainId = $domainId;
        $content->TemplateId = $templateId;
        $content->GallerySettings = $gallerySettings;
        $content->DiscusionSettings = $discusionSettings;
        $content->FormId = $formId;
        $content->NoChild = $noChild;
        $content->NoLoadSubItems = $noLoadSubItems;
        $content->UseTemplateInChild = !$noChild ? $useTemplateInChild : false;
        $content->ChildTemplateId = !$noChild ? $childTemplate : 0;
        $content->CopyDataToChild = !$noChild ? $copyDataToChild : false;
        $content->ActivatePager = $ActivatePager;
        $content->FirstItemLoadPager = $FirstItemLoadPager;
        $content->NextItemLoadPager = $NextItemLoadPager;
        $content->Owner = $userId;
        $content->Inquery = $inquery;
        
        $content->DiscusionId = $connectDiscusion;
        $content->SaveToCache = $caching;
        $content->SortRule=  $sortRule;
        $contentId = $content->SaveObject($content);
        

        if ($contentId == 0) {

            dibi::rollback();
            return 0;
        }
        
        if (!$this->IsLinkByType($contentType)) {
            if (!$this->ChangePrivileges($privileges)) {
                $privileges = $this->GetParentPrivileges($parentid);
            }
            $security = $this->Security($privileges, $contentId);
            if (!$security) {
                dibi::rollback();
                return 0;
            }
        } else {
            $this->Security($privileges, $contentId, true);
        }
        

        $versionId = $this->CreateVersion($contentId, $name, $isActive, $userId, $seoUrl, $template, $AvailableOverSeoUrl, $lang, $data, $header, $activeFrom, $activeTo, $testPrivileges, $contentType, $settings);
        if ($versionId == 0) {
            dibi::rollback();
            return 0;
        }
        



        if (!$this->SaveData($dataArray, $contentId, $lang)) {
            dibi::rollback();
            return 0;
        }

        dibi::commit();
        return $contentId;
        }
        catch (Exception $e)
        {
            echo $e;die();
        }
    }

    public function Search($groupId, $langId, $searchString) {
        if (empty($searchString))
            return array();
        return dibi::query("SELECT DISTINCT SearchValue, Name  FROM  SEARCHVIEW WHERE SSGroupId =%i AND LangId = %i AND  (SearchValue LIKE %~like~ OR Name LIKE %~like~) GROUP BY Id", $groupId, $langId, $searchString, $searchString)->fetchAll();
    }

    private function SaveData($data, $contentId, $langId = 0) {
        try {
            
            if (!empty($data)) {
                
                if ($langId == 0) {
                    $langId = empty($_GET["langid"]) ? $this->GetLangIdByWebUrl() : $_GET["langid"];
                }
                $contentData =  ContentData::GetInstance();
                $contentData->DeleteByCondition("ContentId = $contentId AND LangId = $langId", true,false);
                $contentData->ContentId = $contentId;
                $contentData->LangId = $langId;
                
                if (is_array($data)) {
                    
                    foreach ($data as $row) {
                        $contentData->Value = html_entity_decode($row[1]);
                        $contentData->ValueNoHtml = strip_tags(html_entity_decode($row[1]));
                        $contentData->ItemName = $row[0];
                        $contentData->SaveObject();
                    }
                }
            }
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }

    public function GetParentPrivileges($parent) {
        $res = dibi::query("SELECT SecurityType,GroupId,Value FROM ContentSecurity WHERE ObjectId = %i AND Deleted = 0", $parent)->fetchAll();
        $privileges = array();
        if (!$this->IsFolder($parent)) {
            $i = 0;
            foreach ($res as $row) {
                $privileges[$i][0] = $row["SecurityType"];
                $privileges[$i][1] = $row["GroupId"];
                $privileges[$i][2] = $row["Value"] == "1" ? "true" : "";
                $i++;
            }
        } else {
            $web = \Model\Webs::GetInstance();
            $web->GetObjectById(empty($_GET["webid"]) ? 0 : $_GET["webid"], true);
            $xml = $web->WebPrivileges;
            $resXml = ArrayUtils::XmlToArray($xml);
            $i = 0;
            foreach ($resXml["item"] as $row) {
                $privileges[$i][0] = $row["PrivilegesName"];
                $privileges[$i][1] = $row["UserGroup"];
                $privileges[$i][2] = $row["Value"] == "true" ? "true" : "";
                $i++;
            }
        }
        return $privileges;
    }

    private function ChangePrivileges($privileges) {
        if (!empty($privileges)) {
            foreach ($privileges as $row) {
                if ($row[2] == "true") {
                    return true;
                }
            }
        }
        return false;
    }

    private function HasChild($id) {
        $content = Content::GetInstance();
        $res = $content->SelectByCondition("ParentId = $id");
        return empty($res) ? false : true;
    }

    public function UpdateContentItem($contentId, $name, $isActive, $seoUrl, $template, $AvailableOverSeoUrl, $noIncludeSearch = true, $identificator = "", $privileges = array(), $data = "", $domainId = 0, $templateId = 0, $header = "", $activeFrom = "", $activeTo = "", $gallerySettings = 0, $discusionSettings = 0, $connectDiscusion = 0, $sort = 99999, $formId = 0, $dataArray = array(), $noChild = false, $useTemplateInChild = false, $childTemplate = 0, $copyDataToChild = false, $ActivatePager = false, $FirstItemLoadPager = 0, $NextItemLoadPager = 0, $inquery = 0, $noLoadSubItems = 0, $settings = "",$caching = false,$sortRule="") {
        dibi::begin();
        if (!$this->HasPrivileges($contentId, PrivilegesType::$CanWrite)) {
            dibi::rollback();
            return $contentId;
        }

        $content = Content::GetInstance();
        $user = Users::GetInstance();
        $userId = $user->GetUserId();
        $content->GetObjectById($contentId, true);

        $content->DomainId = $domainId;
        $content->Sort = $sort;
        $content->TemplateId = $templateId;
        $content->NoIncludeSearch = $noIncludeSearch;
        $content->Identificator = $identificator;
        $content->GallerySettings = $gallerySettings;
        $content->DiscusionSettings = $discusionSettings;
        $content->FormId = $formId;
        $content->ActivatePager = $ActivatePager;
        $content->FirstItemLoadPager = $FirstItemLoadPager;
        $content->NextItemLoadPager = $NextItemLoadPager;
        $content->Inquery = $inquery;
        $content->NoLoadSubItems = $noLoadSubItems;
        $content->SortRule = $sortRule;
        
        /*
          $content->ChildTemplateId = $childTemplate;
          $content->CopyDataToChild = $copyDataToChild;* */
        if ($noChild && $this->HasChild($contentId)) {
            $noChild = false;
        }
        $content->NoChild = $noChild;
        $content->UseTemplateInChild = !$noChild ? $useTemplateInChild : false;
        $content->ChildTemplateId = !$noChild ? $childTemplate : 0;
        $content->CopyDataToChild = !$noChild ? $copyDataToChild : false;
        $content->DiscusionId = $connectDiscusion;
        $content->SaveToCache =$caching;
        $contentId = $content->SaveObject($content);
        if ($contentId == 0) {
            dibi::rollback();
            return 0;
        }


        if ($this->HasPrivileges($contentId, PrivilegesType::$CanChangePrivileges)) {
            $isOkSecurity = $this->Security($privileges, $contentId);
            if (!$isOkSecurity) {
                dibi::rollback();
                return $contentId;
            }
        }
        $versionId = $this->CreateVersion($contentId, $name, $isActive, $userId, $seoUrl, $template, $AvailableOverSeoUrl, 0, $data, $header, $activeFrom, $activeTo, true, "", $settings);
        if ($versionId == 0) {
            dibi::rollback();
            return $contentId;
        }

        if (!$this->SaveData($dataArray, $contentId)) {
            dibi::rollback();
            return $contentId;
        }
        if(!$this->UpdateItemInOtherLang($contentId,$templateId,$dataArray)) {
            dibi::rollback();
            return $contentId;
        }
        
        

        dibi::commit();
        return $contentId;
    }
    
    private function UpdateItemInOtherLang($contentId,$templateId,$dataArray)
    {
        
        $udi = UserDomainsItems::GetInstance();
        $domainIden = $udi->GetUserDomainByTemplateId($templateId);
        $values = $udi->GetUserDomainItemByIdentificator($domainIden);
        $multiLangValue = array_filter($values,function($row){ 
            if ($row["ValueForAllLangues"] == 1)
            {
                return $row;
            }
        }
        );
        if(empty($multiLangValue))
            return true;
        
        $multiLangValue = ArrayUtils::ValueAsKey($multiLangValue, "Identificator");
        $lang = Langs::GetInstance();
        $langList = $lang->Select();
        $udpateData = array();
        $newDataArray = array();
        foreach ($dataArray as $row)
        {
            $columnName = $row[0];
            $value = $row[1];
            $newDataArray[$columnName] = $value;
        }
         $dataArray = $newDataArray;
         foreach ($dataArray as $columnName => $value)
         {
            
            if (!empty($multiLangValue[$columnName]))
                $udpateData[$columnName] = $value;
            
         }
         
         // hack for checkbox
         $checkboxes = array_filter($multiLangValue,function($row){
             if($row["Type"] == "checkbox")
             {
                 return $row;
             }
         });
         
         foreach  ($checkboxes as $row)
         {
             $id = $row["Id"]; 
             $values = $row["ValueList"];
             $dataAr = ArrayUtils::XmlToArray($values,"SimpleXMLElement",LIBXML_NOCDATA);
             $dataAr = $dataAr["item"];
             foreach ($dataAr as $item)
             {
                 $checkboxId = "checkbox_".$item["itemValue"]."_".$id;
                 if(!empty($dataArray[$checkboxId]))
                 {
                     $udpateData[$checkboxId]= $dataArray[$checkboxId];
                 }
                 else 
                 {
                     $udpateData[$checkboxId] = 0;
                 }
             }   
         }
         
         
         
         foreach ($langList as $row)
         {
            $exist = $this->ItemExistsInLang($contentId,$row["Id"]);
            if ($row["Id"] == $_GET["langid"])
                continue;
            if($exist)
            {
                $res = $this->SelectByCondition("ContentId = $contentId AND IsLast = 1 AND LangId = ".$row["Id"],"","Data");
                $xml =$res[0]["Data"];
                $dataAr = ArrayUtils::XmlToArray($xml,"SimpleXMLElement",LIBXML_NOCDATA);
                $saveXml = "<items>";
                foreach ($udpateData as $key => $value)
                {
                    $exists = \Dibi::query("SELECT ContentId FROM ContentData WHERE ContentId = %i AND LangId =%i AND ItemName = %s",$contentId, $row["Id"], $key)->fetchAll();
                    if(empty($exists))
                    {
                        \Dibi::query("INSERT INTO ContentData SET Value = %s, ValueNoHtml =%s , ContentId = %i , LangId =%i ,ItemName = %s",$value, strip_tags(html_entity_decode($value)),$contentId, $row["Id"], $key);
                    }
                    else 
                    {
                        \Dibi::query("UPDATE ContentData SET Value = %s, ValueNoHtml =%s WHERE ContentId = %i AND LangId =%i AND ItemName = %s",$value, strip_tags(html_entity_decode($value)),$contentId, $row["Id"], $key);
                    }
                    $dataAr[$key] = $value;
                }
                foreach ($dataAr as $key => $value)
                {
                    $saveXml .= "<$key><![CDATA[".$value."]]></$key>";
                }
                $saveXml .= "</items>";
                \Dibi::query("UPDATE ContentVersion SET Data = %s WHERE ContentId = %i AND LangId =%i AND IsLast = 1",$saveXml, $contentId, $row["Id"]);
                \Dibi::query("UPDATE ContentVersion SET Data = %s WHERE ContentId = %i AND LangId =%i AND IsActive = 1",$saveXml, $contentId, $row["Id"]);
            }
            
        }
        return true;
        
        
        
    }

    public function CreateTemplate($name, $identificator, $privileges = array(), $data = "", $parentId = 0, $lang = 0, $domainId = 0, $templateId = 0, $publish = false, $header = "", $settings = "") {
        $settings = $this->PrepareXmlFromArray($settings);
        return $this->CreateContentItem($name, $publish, "", 0, ContentTypes::$Template, FALSE, $lang, $parentId, true, $identificator, $privileges, $data, $domainId, $templateId, $header, "", "", 0, 0, 0, 99999, 0, true, array(), false, false, 0, false, false, 0, 0, 0, 0, $settings);
    }

    public function CreateCss($name, $privileges = array(), $data = "", $parentId = 0, $lang = 0, $publish = false) {
        return $this->CreateContentItem($name, $publish, "", 0, ContentTypes::$Css, FALSE, $lang, $parentId, true, "", $privileges, $data, 0, 0, "");
    }

    public function UpdateJs($id, $name, $privileges = array(), $data = "", $publish = false, $sort = 99999) {
        return $this->UpdateContentItem($id, $name, $publish, "", 0, true, true, "", $privileges, $data, 0, 0, "", "", "", 0, 0, 0, $sort);
    }

    public function CreateInquery($name, $privileges = array(), $data = array(), $parentId = 0, $lang = 0, $publish = false) {
        $data = $this->PrepareXmlFromArray($data);
        return $this->CreateContentItem($name, $publish, "", 0, ContentTypes::$Inquery, FALSE, $lang, $parentId, true, "", $privileges, $data, 0, 0, "");
    }

    public function UpdateDataSource($id, $name, $privileges = array(), $seoUrl = "", $data = "", $publish = false, $sort = 99999, $dataIsPrepared = false) {
        $tmpData = $data;
        if (!$dataIsPrepared)
            $data = $this->PrepareXmlFromArray($data);
        return $this->UpdateContentItem($id, $name, $publish, $seoUrl, 0, true, true, "", $privileges, $data, 0, 0, "", "", "", 0, 0, 0, $sort, 0, $tmpData);
    }

    public function CreateJs($name, $privileges = array(), $data = "", $parentId = 0, $lang = 0, $publish = false, $sort = 99999) {
        return $this->CreateContentItem($name, $publish, "", 0, ContentTypes::$Javascript, FALSE, $lang, $parentId, true, "", $privileges, $data, 0, 0, "", "", "", 0, 0, 0, $sort);
    }

    public function CreateDataSource($name, $privileges = array(), $seoUrl = "", $data = "", $parentId = 0, $lang = 0, $publish = false, $sort = 99999, $dataIsPrepared = false) {
        $tmpData = $data;
        if (!$dataIsPrepared)
            $data = $this->PrepareXmlFromArray($data);
        return $this->CreateContentItem($name, $publish, $seoUrl, 0, ContentTypes::$DataSource, FALSE, $lang, $parentId, true, "", $privileges, $data, 0, 0, "", "", "", 0, 0, 0, $sort, 0, $tmpData);
    }

    public function UpdateCss($id, $name, $privileges = array(), $data = "", $publish = false) {
        return $this->UpdateContentItem($id, $name, $publish, "", 0, true, true, "", $privileges, $data, 0, 0, "");
    }

    public function UpdateInquery($id, $name, $privileges = array(), $data = array(), $publish = false) {
        $data = $this->PrepareXmlFromArray($data);
        return $this->UpdateContentItem($id, $name, $publish, "", 0, true, true, "", $privileges, $data, 0, 0, "");
    }

    public function UpdateTemplate($id, $name, $identificator, $privileges = array(), $data = "", $domainId = 0, $templateId = 0, $publish = false, $header = "", $settings = "") {
        $settings = $this->PrepareXmlFromArray($settings);
        return $this->UpdateContentItem($id, $name, $publish, "", 0, true, true, $identificator, $privileges, $data, $domainId, $templateId, $header, "", "", 0, 0, 0, 99999, 0, array(), false, false, 0, false, false, 0, 0, 0, 0, $settings);
    }

    private function SetValidateUserItem() {
        $this->SetValidateRule("SeoUrl", RuleType::$NoEmpty);
        $this->SetValidateRule("SeoUrl", RuleType::$SeoString);
        $this->SetValidateRule("SeoUrl", RuleType::$Unique);
    }

    public function CreateUserItem($name, $seoUrl, $availableOverSeoUrl, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $template, $isActive, $lang, $parentid, $privileges, $data, $dataIsPrepared = false, $gallerySettings = 0, $discusionSettings = 0, $connectDiscusion = 0, $formId = 0, $noChild = false, $useTemplateInChild = false, $childTemplate = 0, $copyDataToChild = false, $ActivatePager = false, $FirstItemLoadPager = 0, $NextItemLoadPager = 0, $inquery = 0, $noLoadSubItems = 0,$caching = false,$sort = 999999,$sortRule="") {
        $this->SetValidateUserItem();
        $tmpData = $data;
        if (!$dataIsPrepared)
            $data = $this->PrepareXmlFromArray($data);
        return $this->CreateContentItem($name, $isActive, $seoUrl, 0, ContentTypes::$UserItem, $availableOverSeoUrl, $lang, $parentid, $noIncludeSearch, $identificator, $privileges, $data, 0, $template, "", $activeFrom, $activeTo, $gallerySettings, $discusionSettings, $connectDiscusion, $sort, $formId, true, $tmpData, $noChild, $useTemplateInChild, $childTemplate, $copyDataToChild, $ActivatePager, $FirstItemLoadPager, $NextItemLoadPager, $inquery, $noLoadSubItems,"",$caching);
    }

    public function CreateLink($linkType, $parentId, $objectId, $externalLinkInfo, $objectLinkId = 0, $privileges = array()) {

        if ($objectLinkId > 0) {
            $this->DeleteItem($objectLinkId);
        }
        $linkInfo = array();
        $linkInfo[0][0] = "LinkType";
        $linkInfo[0][1] = $linkType;
        $arrayXml = array();
        $arrayXml[0]["LinkType"] = $linkType;

        if ($linkType == LinkType::$Document || $linkType == LinkType::$Repository || $linkType == LinkType::$Form) {

            $linkInfo[1][0] = "ObjectId";
            $linkInfo[1][1] = $objectId;
            $arrayXml[0]["ObjectId"] = $objectId;
            $user = Users::GetInstance();
            $data = array();
            //echo $objectId;die();
            if ($linkType == LinkType::$Document)
                $data = $this->GetUserItemDetail($objectId, $user->GetUserGroupId(), $_GET["webid"], $_GET["langid"]);
            else if ($linkType == LinkType::$Repository)
                $data = $this->GetFileFolderDetail($objectId, $user->GetUserGroupId(), $_GET["webid"], $_GET["langid"]);
            else if ($linkType == LinkType::$Form)
                $data = $this->GetFormDetail($objectId, $user->GetUserGroupId(), $_GET["webid"], $_GET["langid"]);
            $linkInfoXml = ArrayUtils::ArrayToXml($arrayXml);
            $row = $data[0];
            return $this->CreateContentItem($row["Name"], true, "link-" . $row["Name"], 0, ContentTypes::$Link, false, $_GET["langid"], $parentId, true, "", $privileges, $linkInfoXml, 0, 0, "", "", "", 0, 0, 0, 99999, 0, true, $linkInfo);
        }
        else if ($linkType == LinkType::$Link) {
            $linkInfoXml = ArrayUtils::ArrayToXml($arrayXml);
            $url = $externalLinkInfo[1];
            return $this->CreateContentItem($externalLinkInfo[0], true, $url, 0, ContentTypes::$ExternalLink, false, $_GET["langid"], $parentId, true, "", $privileges, $linkInfoXml, 0, 0, "", "", "", 0, 0, 0, 99999, 0, true, $linkInfo);
        } else if ($linkType == LinkType::$CssLink) {
            $linkInfoXml = ArrayUtils::ArrayToXml($arrayXml);
            $url = $externalLinkInfo[1];
            return $this->CreateContentItem($externalLinkInfo[0], true, $url, 0, ContentTypes::$CssExternalLink, false, $_GET["langid"], $parentId, true, "", $privileges, $linkInfoXml, 0, 0, "", "", "", 0, 0, 0, 99999, 0, true, $linkInfo);
        } else if ($linkType == LinkType::$JsLink) {
            $linkInfoXml = ArrayUtils::ArrayToXml($arrayXml);
            $url = $externalLinkInfo[1];
            return $this->CreateContentItem($externalLinkInfo[0], true, $url, 0, ContentTypes::$JsExternalLink, false, $_GET["langid"], $parentId, true, "", $privileges, $linkInfoXml, 0, 0, "", "", "", 0, 0, 0, 99999, 0, true, $linkInfo);
        } else if ($linkType == LinkType::$Javascript) {
            $linkInfoXml = ArrayUtils::ArrayToXml($arrayXml);
            $url = $externalLinkInfo[1];
            return $this->CreateContentItem($externalLinkInfo[0], true, $url, 0, ContentTypes::$JavascriptAction, false, $_GET["langid"], $parentId, true, "", $privileges, $linkInfoXml, 0, 0, "", "", "", 0, 0, 0, 99999, 0, true, $linkInfo);
        }
    }

    public function CreateForm($name, $seoUrl, $availableOverSeoUrl, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $template, $isActive, $lang, $parentid, $privileges, $data) {
        $this->SetValidateUserItem();
        $data = $this->PrepareXmlFromArray($data);
        return $this->CreateContentItem($name, $isActive, $seoUrl, 0, ContentTypes::$Form, $availableOverSeoUrl, $lang, $parentid, $noIncludeSearch, $identificator, $privileges, $data, 0, $template, "", $activeFrom, $activeTo, 0, 0, 0);
    }

    public function CreateMail($name, $lang, $parentid, $privileges, $data, $active) {
        return $this->CreateContentItem($name, $active, "", 0, ContentTypes::$Mail, false, $lang, $parentid, true, "", $privileges, $data, 0, 0, "", "", "", 0, 0, 0, 99999, 0);
    }

    public function CreateMailing($name, $lang, $parentid, $privileges, $data, $active) {
        $data = $this->PrepareXmlFromArray($data);
        return $this->CreateContentItem($name, $active, "", 0, ContentTypes::$Mailing, false, $lang, $parentid, true, "", $privileges, $data, 0, 0, "", "", "", 0, 0, 0, 99999, 0);
    }

    public function CreateSendMail($lang, $parentid, $data, $sourceId, $webId, $groupId, $emailFrom, $emailTo) {
        $sourceData = $this->GetMailDetail($sourceId, $groupId, $webId, $lang);
        if (empty($sourceData))
            return "";
        $mailTemplate = $sourceData[0]["Data"];
        $html = $this->PrepareHtml($data, $mailTemplate);
        $dataAr = array();
        $dataAr["EmailText"] = $html;
        $dataAr["EmailTo"] = $emailTo;
        $dataAr["EmailFrom"] = $emailFrom;
        $dataAr["Time"] = Utils::Now();
        $dataAr["IP"] = Utils::GetIp();
        $data = $this->PrepareXmlFromArray($dataAr, "keyvalue");
        $name = $sourceData[0]["Name"];
        $id = $this->CreateContentItem($name, true, $name . "-" . StringUtils::GenerateRandomString(), 0, ContentTypes::$SendMail, false, $lang, $parentid, true, "", array(), $data, 0, 0, "", "", "", 0, 0, 0, 99999, 0, FALSE);
        $out["Html"] = $html;
        $out["Name"] = $name;
        $out["MailId"] = $id;
        return $out;
    }

    public function CreateResendEmail($lang, $parentid, $data, $emailFrom, $emailTo, $name, $html) {
        $dataAr = array();
        $dataAr["EmailText"] = $html;
        $dataAr["EmailTo"] = $emailTo;
        $dataAr["EmailFrom"] = $emailFrom;
        $dataAr["Time"] = Utils::Now();
        $dataAr["IP"] = Utils::GetIp();
        $data = $this->PrepareXmlFromArray($dataAr, "keyvalue");
        return $this->CreateContentItem($name, true, $name . "-" . StringUtils::GenerateRandomString(), 0, ContentTypes::$SendMail, false, $lang, $parentid, true, "", array(), $data, 0, 0, "", "", "", 0, 0, 0, 99999, 0, FALSE);
    }

    public function UpdateMail($contentId, $name, $privileges, $data, $active) {
        return $this->UpdateContentItem($contentId, $name, $active, "", 0, false, true, "", $privileges, $data);
    }

    public function UpdateMailing($contentId, $name, $privileges, $data, $active) {
        $data = $this->PrepareXmlFromArray($data);
        return $this->UpdateContentItem($contentId, $name, $active, "", 0, false, true, "", $privileges, $data);
    }

    public function UpdateForm($contentId, $name, $seoUrl, $availableOverSeoUrl, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $template, $isActive, $privileges, $data) {
        $this->SetValidateUserItem();
        $data = $this->PrepareXmlFromArray($data);
        return $this->UpdateContentItem($contentId, $name, $isActive, $seoUrl, 0, $availableOverSeoUrl, $noIncludeSearch, $identificator, $privileges, $data, 0, $template, "", $activeFrom, $activeTo);
    }

    public function CreateFileFolder($name, $seoUrl, $AvailableOverSeoUrl, $lang, $parentid, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $privileges) {
        return $this->CreateContentItem($name, true, $seoUrl, 0, ContentTypes::$FileFolder, $AvailableOverSeoUrl, $lang, $parentid, $noIncludeSearch, $identificator, $privileges, "", 0, 0, "", $activeFrom, $activeTo);
    }

    public function CreateFile($name, $lang, $parentid, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $privileges, $data) {
        $data = $this->PrepareXmlFromArray($data);
        $id = $this->CreateContentItem($name, true, "", 0, ContentTypes::$FileUpload, true, $lang, $parentid, $noIncludeSearch, $identificator, $privileges, $data, 0, 0, "", $activeFrom, $activeTo);
        $this->GetFileType($id, $data,$lang);
        return $id;
    }

    private function GetFileType($id, $data,$lang) {
        $content = Content::GetInstance();
        $content->GetObjectById($id, true);
        
        $data = str_replace("<items><FileUpload><![CDATA[", "", $data);
        $data = str_replace("]]></FileUpload></items>", "", $data);
        $data = strtolower($data);

        if (strpos($data, ".jpg") !== false || strpos($data, ".gif") !== false || strpos($data, ".png") !== false || strpos($data, ".jpeg") !== false) {
            
            $content->UploadedFileType = "image";
            $img = new Image();
            $web = \Model\Webs::GetInstance();
            $web->GetObjectById(empty($_GET["webid"]) ? 0 : $_GET["webid"], true);
            $xml = "<items>";
            $xml .= "<FileUpload>";
            $xml .= "<![CDATA[" . $data . "]]>";
            $xml .= "</FileUpload>";

            $actualFile = ROOT_PATH . $data;
                
            $newFileNameB = $img->CreateFileName($actualFile, "_b");
            
            $img->Resizer($actualFile, $newFileNameB, $web->BigWidth, $web->BigHeight);
            $newFileNameB = str_replace(ROOT_PATH, "", $newFileNameB);
            $xml .= "<FileUpload_big>";
            $xml .= "<![CDATA[" . $newFileNameB . "]]>";
            $xml .= "</FileUpload_big>";

            $newFileNameM = $img->CreateFileName($actualFile, "_m");
            $img->Resizer($actualFile, $newFileNameM, $web->MediumWidth, $web->MediumHeight);
            $newFileNameM = str_replace(ROOT_PATH, "", $newFileNameM);
            $xml .= "<FileUpload_medium>";
            $xml .= "<![CDATA[" . $newFileNameM . "]]>";
            $xml .= "</FileUpload_medium>";

            $newFileNameS = $img->CreateFileName($actualFile, "_s");
            $img->Resizer($actualFile, $newFileNameS, $web->SmallWidth, $web->SmallHeight);
            $newFileNameS = str_replace(ROOT_PATH, "", $newFileNameS);
            $xml .= "<FileUpload_small>";
            $xml .= "<![CDATA[" . $newFileNameS . "]]>";
            $xml .= "</FileUpload_small>";
            $xml .= "</items>";
                
            $dataVersion = $this->SelectByCondition("ContentId = $id AND IsLast = 1 AND LangId = $lang");
            if (!empty($dataVersion)) {
                //echo $dataVersion[0]["Id"]."<br>";
                $contentVersion = ContentVersion::GetInstance();
                $contentVersion->GetObjectById($dataVersion[0]["Id"], true);
                $contentVersion->Data = $xml;
                $contentVersion->SaveObject($contentVersion);
            }
        } else if (strpos($data, ".mp4") !== false) {
            $content->UploadedFileType = "video";
        } else if (strpos($data, ".mp3") !== false || strpos($data, ".ogg") !== false) {
            $content->UploadedFileType = "audio";
        } else {
            $content->UploadedFileType = "otherfile";
        }


        $content->SaveObject($content);
    }

    public function UpdateFile($contentId, $name, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $privileges, $data) {
        $data = $this->PrepareXmlFromArray($data);
        $id = $this->UpdateContentItem($contentId, $name, true, "", 0, true, $noIncludeSearch, $identificator, $privileges, $data, 0, 0, "", $activeFrom, $activeTo);
        $this->GetFileType($contentId, $data,$_GET["langid"]);
        return $id;
    }

    public function UpdateFileFolder($contentId, $name, $seoUrl, $AvailableOverSeoUrl, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $privileges) {
        return $this->UpdateContentItem($contentId, $name, true, $seoUrl, 0, $AvailableOverSeoUrl, $noIncludeSearch, $identificator, $privileges, "", 0, 0, "", $activeFrom, $activeTo);
    }

    public function UpdateUserItem($contentId, $name, $seoUrl, $availableOverSeoUrl, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $template, $isActive, $privileges, $data, $gallerySettings = 0, $discusionSettings = 0, $connectDiscusion = 0, $formId = 0, $noChild = false, $useTemplateInChild = false, $childTemplate = 0, $copyDataToChild = false, $ActivatePager = false, $FirstItemLoadPager = 0, $NextItemLoadPager = 0, $inquery = 0, $noLoadSubItems = 0,$caching = false,$sort = 99999,$sortRule) {
        $this->SetValidateUserItem();
        $dataTmp = $data;
        $data = $this->PrepareXmlFromArray($data);
        return $this->UpdateContentItem($contentId, $name, $isActive, $seoUrl, 0, $availableOverSeoUrl, $noIncludeSearch, $identificator, $privileges, $data, 0, $template, "", $activeFrom, $activeTo, $gallerySettings, $discusionSettings, $connectDiscusion, $sort, $formId, $dataTmp, $noChild, $useTemplateInChild, $childTemplate, $copyDataToChild, $ActivatePager, $FirstItemLoadPager, $NextItemLoadPager, $inquery, $noLoadSubItems,"",$caching,$sortRule);
    }

    public function CreateFormStatisticItem($lang, $parentid, $data) {
        $user = Users::GetInstance();
        $ar1 = array();
        $ar1[0] = "UserIp";
        $ar1[1] = Utils::GetIp();
        $data[] = $ar1;
        $ar1[0] = "UserId";
        $ar1[1] = $user->GetUserId();
        $data[] = $ar1;
        $ar1[0] = "UserName";
        $ar1[1] = $user->GetUserName();
        $data[] = $ar1;
        $ar1[0] = "Date";
        $ar1[1] = Utils::Now();
        $data[] = $ar1;
        $data = $this->PrepareXmlFromArray($data);

        
        

        return $this->CreateContentItem("formstatistic-" . StringUtils::GenerateRandomString(), true, "", 0, ContentTypes::$FormStatistic, false, $lang, $parentid, true, "", array(), $data, 0, 0, "", "", "", 0, 0, 0, 99999, 0, false);
    }

    public function GetFormStatistic($formId, $langId, $webId) {
        return dibi::query("SELECT  Id, Data FROM FORMSTATISTIC WHERE ParentId= %i ", $formId)->fetchAll();
    }

    public function GetFromStatisticDetail($id, $langId) {
        return dibi::query("SELECT Data FROM FORMSTATISTIC WHERE   Id =%i  ", $id)->fetchAll();
    }

    public function PrepareHtml($array, $html) {
        for ($i = 0; $i < count($array); $i++) {
            $key = $array[$i][0];
            $value = $array[$i][1];
            $html = str_replace("{" . $key . "}", $value, $html);
        }
        return $html;
    }

    private function PrepareXmlFromArray($array, $mode = "standard") {
        $xml = "";
        if (!empty($array)) {
            $xml .= "<items>";
            if ($mode == "standard") {
                for ($i = 0; $i < count($array); $i++) {
                    if (!empty($array[$i][1])) {
                        $id = $array[$i][0];
                        if (StringUtils::EndWith($id, "__ishtmleditor__")) {
                            $id = StringUtils::RemoveString($id, "__ishtmleditor__");
                            $id = StringUtils::RemoveLastChar($id, 5);
                        }
                        if ($id =="DataItems")
                            $xml .= "<" . $id . ">" . $array[$i][1] . "</" . $id . ">";
                        else
                            $xml .= "<" . $id . "><![CDATA[" . $array[$i][1] . "]]></" . $id . ">";
                    }
                }
            } else if ($mode == "keyvalue") {
                foreach ($array as $key => $value) {
                    if (!empty($key)) {
                        if (StringUtils::EndWith($key, "__ishtmleditor__")) {
                            $key = StringUtils::RemoveString($id, "__ishtmleditor__");
                            $id = StringUtils::RemoveLastChar($key, 5);
                        }
                        $xml .= "<" . $key . "><![CDATA[" . $value . "]]></" . $key . ">";
                    }
                }
            }
            $xml .= "</items>";
            return $xml;
        }
    }

    private function DeactiveAllVersion($contentId, $lang) {
        if ($lang == 0)
            dibi::query("UPDATE ContentVersion SET IsActive = 0 WHERE ContentId = %i", $contentId);
        else
            dibi::query("UPDATE ContentVersion SET IsActive = 0 WHERE ContentId = %i AND LangId = %i", $contentId, $lang);
    }

    private function Security($privileges, $contentId, $setDefault = true) {
        try {
            $security =  ContentSecurity::GetInstance();
            $user = UserGroups::GetInstance();
            $systemgroup = $user->GetUserGroupByIdeticator("system");
            $security->DeleteByCondition("ObjectId = $contentId", true, false);

            $types = array();
            if (!empty($privileges)) {
                for ($i = 0; $i < count($privileges); $i++) {
                    $security->ObjectId = $contentId;
                    $security->SecurityType = $privileges[$i][0];
                    $types[] = $privileges[$i][0];
                    $security->GroupId = $privileges[$i][1];
                    $security->Value = $privileges[$i][2] == "true" ? true : false;
                    $security->SaveObject($security);
                }
            }
            if ($setDefault) {
                $privilegesDefault = $this->SetDefaultPrivileges($systemgroup);
                for ($i = 0; $i < count($privilegesDefault); $i++) {
                    $security->ObjectId = $contentId;
                    $security->SecurityType = $privilegesDefault[$i][0];
                    $types[] = $privilegesDefault[$i][0];
                    $security->GroupId = $privilegesDefault[$i][1];
                    $security->Value = $privilegesDefault[$i][2] == "true" ? true : false;
                    $security->SaveObject($security);
                }
            }
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function SetDefaultPrivileges($systemGroup) {

        $systemId = $systemGroup->Id;
        $priviles[0][0] = "canRead";
        $priviles[0][1] = $systemId;
        $priviles[0][2] = true;
        $priviles[1][0] = "canWrite";
        $priviles[1][1] = $systemId;
        $priviles[1][2] = true;
        $priviles[2][0] = "canDelete";
        $priviles[2][1] = $systemId;
        $priviles[2][2] = true;
        $priviles[3][0] = "canPublish";
        $priviles[3][1] = $systemId;
        $priviles[3][2] = true;

        $priviles[4][0] = "canChangePrivileges";
        $priviles[4][1] = $systemId;
        $priviles[4][2] = true;
        return $priviles;
    }

    public function CreateVersion($contentId, $name, $isActive, $userId, $seoUrl, $template, $AvailableOverSeoUrl, $lang, $data, $header, $activeFrom, $activeTo, $testPrivileges = true, $contentType = "", $settings = "") {
        try {

            if ($lang == 0 && !empty($_GET["langid"])) {
                $lang = $_GET["langid"];
            }

            /* $lastId = $this->GetLastVersionId($contentId, $lang);
              if ($lastId >0)
              {
              $this->CopyObject($lastId);
              } */
             
            $canPublish = $this->HasPrivileges($contentId, PrivilegesType::$CanPublish);
            if ($lang == 0) {
                dibi::query("UPDATE ContentVersion SET IsLast = 0 WHERE ContentId = %i ", $contentId);
            } else {
                dibi::query("UPDATE ContentVersion SET IsLast = 0 WHERE ContentId = %i AND LangId =%i ", $contentId, $lang);
            }
            $this->ContentId = $contentId;
            $this->Name = $name;
            $this->PublishUser = 0;
            
            $isActive = $isActive == "true" ? true : false;
            if ($isActive && ($canPublish || !$testPrivileges)) {
                $this->DeactiveAllVersion($contentId, $lang);
                $this->IsActive = TRUE;
                $this->PublishUser = $userId;
            } else {
                $this->IsActive = FALSE;
            }
            
            $this->IsLast = TRUE;
            $this->Author = $userId;
            
            $this->SeoUrl = ($contentType == ContentTypes::$ExternalLink || $contentType == ContentTypes::$CssExternalLink || $contentType == ContentTypes::$JsExternalLink || $contentType == ContentTypes::$JavascriptAction ) ? $seoUrl : $this->ValidateSeoUrl($seoUrl, $name, $contentId, $lang);
            
            $this->Template = $template;
            $this->Header = $header;
            $this->AvailableOverSeoUrl = $AvailableOverSeoUrl;
            $this->Date = date('Y-m-d H:i:s');
            if ($lang > 0) {
                $this->LangId = $lang;
            }
            

            $this->Data = $data;
            $this->ActiveFrom = $activeFrom;
            $this->ActiveTo = $activeTo;
            $this->ContentSettings = $settings;
            $vesionId = $this->SaveObject($this);
            if ($vesionId == 0) {
                //throw xWebExceptions::$SaveVersionError;
            }
            return $vesionId;
        } catch (Exception $ex) {
            Files::WriteLogFile($ex);
            return 0;
        }
    }

    /*private function GetLastVersionId($contentId, $langId) {
        $res = array();
        if ($langId == 0)
            $res = $this->SelectByCondition("ContentId = $contentId AND IsLast = 1");
        else
            $res = $this->SelectByCondition("ContentId = $contentId AND IsLast = 1 AND LangId = $langId");
        if (empty($res))
            return 0;
        return $res[0]["Id"];
    }*/

    private function ValidateSeoUrl($seoUrl, $name, $id, $lang = 0) {
        $seoUrl = trim($seoUrl);
        if (empty($seoUrl))
            $seoUrl = $name;
        if (empty($seoUrl))
            $seoUrl = $id;
        $seoUrl = StringUtils::SeoString($seoUrl);
        
        $exists = $this->ItemExists("SeoUrl", $seoUrl, $id, "ContentId", $lang);
        
        if ($exists || strlen($seoUrl)<=3) {
            $seoUrl = $seoUrl . "-" . $id;
            return $this->ValidateSeoUrl($seoUrl, $name, $id);
        }
        
        return $seoUrl;
    }

    public function GetTemplateList($groupId, $langId, $onlyTemplate = false, $onlyWidthDomain = false, $search = "", $sort = "") {

        if (!empty($sort))
            $sort = "ORDER BY $sort";
        if ($search == "") {
            if (!$onlyTemplate) {
                if (!$onlyWidthDomain) {
                    $res = dibi::query("SELECT * FROM TEMPLATESLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1 $sort", $langId, $groupId)->fetchAll();
                } else
                    $res = dibi::query("SELECT * FROM TEMPLATESLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1 AND DomainId >0 $sort", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId", "");
            }
            if (!$onlyWidthDomain)
                return dibi::query("SELECT * FROM TEMPLATESLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1 AND ContentType = 'Template'  $sort ", $langId, $groupId)->fetchAll();
            else
                return dibi::query("SELECT * FROM TEMPLATESLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1 AND ContentType = 'Template' AND  DomainId >0 $sort", $langId, $groupId)->fetchAll();
        }
        else {
            $res = dibi::query("SELECT * FROM TEMPLATESLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1 AND (Name LIKE %~like~ OR ContentType = 'LangFolder') $sort", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId", "");
        }
    }

    public function GetCssList($groupId, $langId, $onlyCss = false, $search = "") {
        
        
        if ($search == "") {
            if (!$onlyCss) {
                $res = dibi::query("SELECT Id,ParentId,Name FROM CSSLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  ", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT Id,ParentId,Name FROM CSSLIST WHERE  LangId = %i AND (ContentType =  'Css' OR ContentType = 'CssExternalLink')  AND GroupId = %i AND IsLast = 1", $langId, $groupId)->fetchAll();
        } else {
            $res = dibi::query("SELECT Id,ParentId,Name FROM CSSLIST WHERE  LangId = %i   AND (Name LIKE %~like~ OR ContentType = 'LangFolder') AND GroupId = %i AND IsLast = 1", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetDiscusionList($groupId, $langId, $onlyCss = false, $search = "") {
        if ($search == "") {
            if (!$onlyCss) {
                $res = dibi::query("SELECT * FROM DISCUSIONLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  ", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT * FROM DISCUSIONLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1 AND (ContentType = 'Discusion')", $langId, $groupId)->fetchAll();
        } else {
            $res = dibi::query("SELECT * FROM DISCUSIONLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  AND (Name LIKE %~like~ OR ContentType = 'LangFolder')", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetDataSourceList($groupId, $langId, $onlyCss = false, $search = "") {
        if ($search == "") {
            if (!$onlyCss) {
                $res = dibi::query("SELECT * FROM DATASOURCELIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  ", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT * FROM DATASOURCELIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1 AND (ContentType = 'DataSource')", $langId, $groupId)->fetchAll();
        } else {
            $res = dibi::query("SELECT * FROM DATASOURCELIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  AND (Name LIKE %~like~ OR ContentType = 'LangFolder')", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetInquryList($groupId, $langId, $onlyCss = false, $search = "", $sort = "") {
        if (!empty($sort))
            $sort = " ORDER BY $sort";
        if ($search == "") {
            if (!$onlyCss) {
                $res = dibi::query("SELECT * FROM INQUERYLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  $sort", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT * FROM INQUERYLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1 AND (ContentType = 'Inquery') $sort", $langId, $groupId)->fetchAll();
        } else {
            $res = dibi::query("SELECT * FROM INQUERYLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  AND (Name LIKE %~like~ OR ContentType = 'LangFolder') $sort", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetFormsList($groupId, $langId, $onlyForm = false, $search = "", $sort = "") {
        if (!empty($sort))
            $sort = "ORDER BY $sort";
        if ($search == "") {
            if (!$onlyForm) {
                $res = dibi::query("SELECT * FROM FORMLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1 $sort ", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT *, 0 AS selected FROM FORMLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1  AND ContentType = 'Form' $sort", $langId, $groupId)->fetchAll();
        } else {
            $res = dibi::query("SELECT * FROM FORMLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1 AND (Name LIKE %~like~ OR ContentType = 'LangFolder')  $sort", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetMailingList($groupId, $langId, $onlyForm = false, $search = "") {
        if ($search == "") {
            if (!$onlyForm) {
                $res = dibi::query("SELECT * FROM MAILINGLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1  ", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT *, 0 AS selected FROM MAILINGLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1  AND ContentType = %s", $langId, $groupId, ContentTypes::$Mailing)->fetchAll();
        } else {
            $res = dibi::query("SELECT * FROM MAILINGLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1 AND (Name LIKE %~like~ OR ContentType = 'LangFolder')  ", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetMailList($groupId, $langId, $onlyMail = false, $search = "", $sort = "") {
        if (!empty($sort))
            $sort = "ORDER BY $sort";
        if ($search == "") {
            if (!$onlyMail) {
                $res = dibi::query("SELECT * FROM MAILLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1 $sort ", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT * FROM MAILLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1  AND ContentType = 'Mail' $sort", $langId, $groupId)->fetchAll();
        } else {
            $res = dibi::query("SELECT * FROM MAILLIST WHERE LangId = %i AND GroupId = %i AND IsLast = 1 AND (Name LIKE %~like~ OR ContentType = 'LangFolder' $sort) ", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetSendMailList($webId, $langId) {
        return dibi::query("SELECT * FROM SENDMAILLIST WHERE WebId = %i AND LangId = %i  AND IsLast = 1 ", $webId, $langId)->fetchAll();
    }

    public function GetSendMailDetail($webId, $langId, $mailid) {
        return dibi::query("SELECT * FROM SENDMAILLIST WHERE WebId = %i AND LangId = %i  AND IsLast = 1 AND Id =%i ", $webId, $langId, $mailid)->fetchAll();
    }

    public function GetDeletedObjects($webId, $langId) {
        return dibi::query("SELECT * FROM CONTENTTREE WHERE WebId = %i AND LangId = %i  AND Deleted = 1 ", $webId, $langId)->fetchAll();
    }

    public function GetJsList($groupId, $langId, $onlyJs = false, $search = "") {
        if ($search == "") {
            if (!$onlyJs) {
                $res = dibi::query("SELECT * FROM JSLIST WHERE  LangId = %i AND GroupId = %i AND IsLast = 1  ", $langId, $groupId)->fetchAll();
                return $this->CreateTree($res, "Id", "ParentId");
            }
            return dibi::query("SELECT DISTINCT Id,Name,ParentId FROM JSLIST WHERE  LangId = %i  AND (ContentType = 'Javascript'  OR ContentType = 'JsExternalLink') AND GroupId = %i AND IsLast = 1", $langId, $groupId)->fetchAll();
        } else {
            $res = dibi::query("SELECT Id,Name,ParentId FROM JSLIST WHERE  LangId = %i AND (Name LIKE %~like~ OR ContentType = 'LangFolder') AND GroupId = %i AND IsLast = 1  ", $langId, $groupId, $search)->fetchAll();
            return $this->CreateTree($res, "Id", "ParentId");
        }
    }

    public function GetFileList($groupId, $webId, $langId) {
        
    }
//
    public function GetTemplateDetail($groupId, $webId, $langId, $contentId, $versionId = 0) {
        if ($versionId == 0) {
            $res = dibi::query("SELECT ContentSettings,Header,DomainId,TemplateId,Id,NoIncludeSearch,Identificator,Name,WebId,LangId,IsLast,IsActive,Data,VersionId,GroupId,SecurityType,SecurityValue,SSGroupId,SSSecurityType,SSValue FROM TEMPLATEDETAIL WHERE Id = %i AND  IsLast = 1 AND LangId = %i AND GroupId = %i ", $contentId, $langId, $groupId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT ContentSettings,Header,DomainId,TemplateId,Id,NoIncludeSearch,Identificator,Name,WebId,LangId,IsLast,IsActive,Data,VersionId,GroupId,SecurityType,SecurityValue,SSGroupId,SSSecurityType,SSValue FROM TEMPLATEDETAIL WHERE Id = %i  AND  LangId = %i AND GroupId = %i AND  VersionId = %i", $contentId, $langId, $groupId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetCssDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM CSSDETAIL WHERE Id = %i AND  IsLast = 1 AND WebId = %i AND LangId = %i AND GroupId = %i ", $contentId, $webId, $langId, $groupId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM CSSDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND VersionId =%i", $webId, $langId, $groupId, $contentId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetInqueryDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($versionId== 0) {
            $res = dibi::query("SELECT * FROM INQUERYDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND IsLast = 1", $webId, $langId, $groupId, $contentId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM INQUERYDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND VersionId =%i", $webId, $langId, $groupId, $contentId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetJsDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM JSDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND IsLast = 1", $webId, $langId, $groupId, $contentId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM JSDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND VersionId = %i", $webId, $langId, $groupId, $contentId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetDataSourceDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM DATASOURCEDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND IsLast = 1", $webId, $langId, $groupId, $contentId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM DATASOURCEDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND VersionId = %i", $webId, $langId, $groupId, $contentId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetUserItemDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0, $active = false) {
        if ($contentId == 0 )
            return array();
        if ($active) {
            $res = dibi::query("SELECT * FROM USERITEMDETAIL WHERE  Id = %i AND IsActive =1  AND GroupId = %i AND LangId = %i  ", $contentId, $groupId, $langId)->fetchAll();
            return $res;
        }

        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM USERITEMDETAIL WHERE  Id = %i AND LangId = %i AND GroupId = %i AND IsLast =1", $contentId, $langId, $groupId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM USERITEMDETAIL WHERE Id = %i AND LangId = %i AND GroupId = %i AND  VersionId =%i", $contentId, $langId, $groupId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetFormDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM FORMDETAIL WHERE Id = %i AND IsLast =1 AND  WebId = %i AND LangId = %i AND GroupId = %i ", $contentId, $webId, $langId, $groupId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM FORMDETAIL WHERE Id = %i AND WebId = %i AND LangId = %i AND GroupId = %i AND  VersionId =%i ", $contentId, $webId, $langId, $groupId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetMailDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM MAILDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND IsLast =1", $webId, $langId, $groupId, $contentId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM MAILDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND VersionId =%i", $webId, $langId, $groupId, $contentId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetMailingDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM MAILINGDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND IsLast =1", $webId, $langId, $groupId, $contentId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM MAILINGDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND VersionId =%i", $webId, $langId, $groupId, $contentId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetDiscusionDetail($contentId, $groupId = 0, $webId = 0, $langId = 0) {
        $res = dibi::query("SELECT * FROM DISCUSIONDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i", $webId, $langId, $groupId, $contentId)->fetchAll();
        return $res;
    }

    public function GetFileFolderDetail($contentId, $groupId = 0, $webId = 0, $langId = 0, $versionId = 0) {
        if ($webId == 0) {
            $res = dibi::query("SELECT * FROM FILEFOLDERDETAIL WHERE   LangId = %i AND GroupId = %i AND Id = %i AND IsLast = 1", $langId, $groupId, $contentId)->fetchAll();
            return $res;
        }
        if ($versionId == 0) {
            $res = dibi::query("SELECT * FROM FILEFOLDERDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND IsLast = 1", $webId, $langId, $groupId, $contentId)->fetchAll();
            return $res;
        } else {
            $res = dibi::query("SELECT * FROM FILEFOLDERDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Id = %i AND VersionId = %i", $webId, $langId, $groupId, $contentId, $versionId)->fetchAll();
            return $res;
        }
    }

    public function GetFrontendCss($contentId, $langId) {
        $user = Users::GetInstance();
        $groupId = $user->GetUserGroupId();
        $res = dibi::query("SELECT * FROM  FRONTENEDCSS WHERE Id = %i  AND GroupId = %i AND LangId = %i", $contentId, $groupId, $langId)->fetchAll();
        return $res[0]["data"];
    }

    public function GetFrontendJs($contentId, $langId = 0) {

        $user = Users::GetInstance();
        $groupId = $user->GetUserGroupId();
        $res = dibi::query("SELECT * FROM FRONTENEDJS WHERE Id = %i AND GroupId = %i AND  LangId = %i", $contentId, $groupId, $langId)->fetchAll();

        return $res[0]["data"];
    }

    public function GetFrontendXml($seoUrl) {
            
        $xml = simplexml_load_string($this->XmlDetail($seoUrl));
        $outXml = "";
        
        $xmlStart = trim($xml->DatasourceXmlStart);
        $xmlEnd = trim($xml->DatasourceXmlEnd);
        $xmlItemStart = trim($xml->DatasourceXmlItemStart);
        $xmlItemEnd = trim($xml->DatasourceXmlItemEnd);
        $xmlItem = trim($xml->DatasourceXmlItem);
        $xmlUserItem = trim($xml->SelectedObject);
        $variantsStart = trim($xml->DatasourceXmlSubItemStart);
        $variantsEnd = trim($xml->DatasourceXmlSubItemEnd);
        $variantItemStart = trim($xml->DatasourceXmlSubItemItemStart);
        $variantItemEnd = trim($xml->DatasourceXmlSubItemItemEnd);
        $variantItem = trim($xml->DatasourceXmlSubItem);
        $exportCondition =    trim($xml->ExportConditions);   
        $exportColumnCondition = trim($xml->ExportColumnConditions);   
        if (trim($xml->DatasourceType) == "XmlExport") {
            $outXml .= $xmlStart;
            $domain = \Model\UserDomainsValues::GetInstance();
            $values = $domain->GetDomainValueList(trim($xml->Domain));
            $domainItems = \Model\UserDomainsItems::GetInstance();
            $items = $domainItems->GetUserDomainItemById(trim($xml->Domain));
            $items = ArrayUtils::ValueAsKey($items, "Identificator");
            foreach ($values as $row) {
                $xmlTmp = $xmlItem;
                $outXml .= $xmlItemStart;
                foreach ($row as $key => $val) {
                    if (!empty($items[$key])) {
                        if ($items[$key]["AddCDATA"] == 1) {
                            $val = "<![CDATA[" . $val . "]]>";
                        }
                    }
                    $xmlTmp = str_replace("{" . $key . "}",  $val, $xmlTmp);
                }

                $outXml .= $xmlTmp;

                $outXml .= $xmlItemEnd;
            }
            $outXml .= $xmlEnd;
            return $outXml;
        } else if (trim($xml->DatasourceType) == "XmlExportUserItem") {
            $users = Users::GetInstance();
            $langId = $this->GetLangIdByWebUrl();
            $data = $this->LoadFrontend($xmlUserItem, $users->GetUserGroupId(), $langId, $this->GetActualWeb(),0,"",true,false,false,"","",true,$exportCondition,$exportColumnCondition);
            $data = ArrayUtils::GetChildToRoot($data, "Child");
            $data = ArrayUtils::GetDataXmlValueToRow($data);
            $detail = $this->GetUserItemDetail($xmlUserItem, $users->GetUserGroupId(), 0, $langId);
            $templateId = empty($detail[0]["ChildTemplateId"]) ? $detail[0]["TemplateId"] : $detail[0]["ChildTemplateId"];
            $domainItems = \Model\UserDomainsItems::GetInstance();
            $identificator = $domainItems->GetUserDomainByTemplateId($templateId);
            $items = $domainItems->GetUserDomainItems($identificator);
            $items = ArrayUtils::ValueAsKey($items, "Identificator");
            
            
            
                
                
                

            $outXml .= $xmlStart;
            foreach ($data as $row) {
                $xmlTmp = $xmlItem;
                $outXml .= $xmlItemStart;
                foreach ($row as $key => $value) {
                    if ($key == "Data") {
                        $xml = simplexml_load_string($value);
                        
                        if (!empty($xml)) {
                            
                            foreach ($xml as $xkey => $xvalue) {
                                if (!empty($items[$xkey])) {
                                    if ($items[$xkey]["AddCDATA"] == 1) {
                                        $xvalue = "<![CDATA[" . $xvalue . "]]>";
                                    }
                                }
                                $xmlTmp = str_replace("{" . $xkey . "}", $xvalue, $xmlTmp);
                            }
                        }
                        break;
                    }
                    else if ($key == "SeoUrl") {
                        $value = SERVER_NAME_LANG . $value . "/";
                    }
                    else 
                            {
                                $value = "<![CDATA[" . $value . "]]>";
                            }
                    $xmlTmp = str_replace("{" . $key . "}", $value, $xmlTmp);
                }
                if (!empty($row["Child"])) {
                    $xmlTmp .= $variantsStart;
                    foreach ($row["Child"] as $rowChild) {
                        $xmlTmpChild = $variantItemStart . "\n" . $variantItem . "\n" . $variantItemEnd;
                        foreach ($rowChild as $keychild => $valuechild) {
                            if ($keychild == "Data") {
                                $xmlchild = simplexml_load_string($valuechild);
                                if (!empty($xmlchild)) {
                                    foreach ($xmlchild as $xkey => $xvalue) {
                                        if (!empty($items[$xkey])) {
                                            if ($items[$xkey]["AddCDATA"] == 1) {
                                                $xvalue = "<![CDATA[" . $xvalue . "]]>";
                                            }
                                        }
                                        $xmlTmp = str_replace("{" . $xkey . "}",  $xvalue, $xmlTmp);
                                    }
                                }
                                break;
                            }
                            else if ($keychild == "SeoUrl") {
                                $valuechild = SERVER_NAME_LANG . $valuechild . "/";
                            }
                            else 
                            {
                                $valuechild = "<![CDATA[" . $valuechild . "]]>";
                            }
                            
                            $xmlTmpChild = str_replace("{" . $keychild . "}", $valuechild, $xmlTmpChild);
                        }
                        $xmlTmp .= $xmlTmpChild;
                    }
                    $xmlTmp .= $variantsEnd;
                }
                $outXml .= $xmlTmp;
                $outXml .= $xmlItemEnd;
            }
            $outXml .= $xmlEnd;
            $outXml = preg_replace('({[A-Za-z0-9\-]*})', "", $outXml);
            $outXml = \Kernel\Page::CompressString($outXml);
            $outXml = str_replace("><", ">\n<", $outXml);
            $outXml = preg_replace('/^[ \t]*[\r\n]+/m', '', $outXml);

            return $outXml;
        }
    }

    private function GenerateVariant() {
        
    }

    public function CheckXml($seoUrl) {
        return;
        $xml = simplexml_load_string($this->XmlDetail($seoUrl));
        $xmlStart = trim($xml->DatasourceXmlStart);
        $xmlEnd = trim($xml->DatasourceXmlEnd);
        $xmlItemStart = trim($xml->DatasourceXmlItemStart);
        $xmlItemEnd = trim($xml->DatasourceXmlItemEnd);
        $xmlItem = trim($xml->DatasourceXmlItem);
        $xmlUrl = trim($xml->DatasourceXmlUrl);
        $domain = trim($xml->Domain);
        $mode = "validate";
        $testColumn = trim($xml->ColumnTest);
        $testColumnUserItem = trim($xml->ColumnTestUserImport);
        $xmlUserItem = trim($xml->SelectedObject);
        $xmlContent = file_get_contents($xmlUrl);
        $searchItemName = "";
        $useTemplateId = 0;
        if (trim($xml->DatasourceType) == "XmlImport") {
            //$data = ArrayUtils::XmlToArray($xmlContent, $class_name, $options);
            $data = simplexml_load_string($xmlContent);
            $this->ImportXmlData($data, $domain, $mode, $testColumn);
        } else if (trim($xml->DatasourceType) == "XmlImportUserItem") {
            
        }
    }

    public function XmlImport($seoUrl) {

        $id = $this->GetIdBySeoUrl($seoUrl);

        $this->SetLastVistedObject($id);
        $xml = simplexml_load_string($this->XmlDetail($seoUrl));
        $xmlStart = trim($xml->DatasourceXmlStart);
        $xmlEnd = trim($xml->DatasourceXmlEnd);
        $xmlItemStart = trim($xml->DatasourceXmlItemStart);
        $xmlItemEnd = trim($xml->DatasourceXmlItemEnd);
        $xmlItem = trim($xml->DatasourceXmlItem);
        $xmlUrl = trim($xml->DatasourceXmlUrl);
        $domain = trim($xml->Domain);
        $mode = trim($xml->ImportMode);
        $testColumn = trim($xml->ColumnTest);
        $testColumnUserItem = trim($xml->ColumnTestUserImport);
        $xmlUserItem = trim($xml->SelectedObject);
        $xmlContent = file_get_contents($xmlUrl);
        $searchItemName = "";
        $useTemplateId = 0;


        if (trim($xml->DatasourceType) == "XmlImport") {
            $data = ArrayUtils::XmlToArray($xmlContent ,"SimpleXMLElement",LIBXML_NOCDATA);
            $this->ImportXmlData($data, $domain, $mode, $testColumn);
        } else if (trim($xml->DatasourceType) == "XmlImportUserItem") {
            if ($mode == "DeleteInsert") {
                $mode = "Insert";
                $content = Content::GetInstance();
                $content->DeleteByCondition("ParentId = $xmlUserItem");
            }
            $users = Users::GetInstance();
            $langId = $this->GetLangIdByWebUrl();
            $dataItem = $this->GetUserItemDetail($xmlUserItem, $users->GetUserGroupId(), 0, $langId);
            $useTemplateId = empty($dataItem[0]["ChildTemplateId"]) ? $dataItem[0]["TemplateId"] : $dataItem[0]["ChildTemplateId"];
            $domain = \Model\UserDomainsItems::GetInstance();
            $domainIdentificator = $domain->GetUserDomainByTemplateId($useTemplateId);



            $items = $domain->GetUserDomainItems($domainIdentificator);
            $items = ArrayUtils::ValueAsKey($items, "Identificator");

            foreach ($items as $row) {
                if ($row["Id"] == $testColumnUserItem) {
                    $searchItemName = $row["Identificator"];
                    break;
                }
            }

            $xmlData = simplexml_load_string($xmlContent);
            $ar = array();
            $y = 0;
            foreach ($xmlData as $row) {
                $rowAdd = array();
                foreach ($row as $key => $value) {
                    $value = $value . " ";
                    $rowAdd[$key] = trim($value);
                }
                $ar[$y] = $rowAdd;
                $y++;
            }

            foreach ($ar as $row) {
                $valueTest = "";
                $name = empty($row["Name"]) ? "" : $row["Name"];
                $seo = empty($row["SeoUrl"]) ? "" : $row["SeoUrl"];
                $availableOverSeoUrl = empty($row["AvailableOverSeoUrl"]) ? true : $row["AvailableOverSeoUrl"];
                $noIncludeSearch = empty($row["NoIncludeSearch"]) ? false : $row["NoIncludeSearch"];
                $identificator = empty($row["Identificator"]) ? "" : $row["Identificator"];
                $activeFrom = empty($row["ActiveFrom"]) ? false : $row["ActiveFrom"];
                $activeTo = empty($row["ActiveTo"]) ? false : $row["ActiveTo"];
                $template = empty($row["TemplateId"]) ? $dataItem[0]["TemplateId"] : $row["TemplateId"];
                $dataSave = array();
                $rowPos = 0;
                $id = 0;
                foreach ($row as $key => $value) {
                    if (!empty($items[$key])) {
                        $rowSave[0] = $key;
                        $rowSave[1] = $value;
                        $dataSave[$rowPos] = $rowSave;
                        $rowPos++;
                        if (trim($key) == trim($searchItemName)) {

                            $test = dibi::query("SELECT Data.ContentId FROM ContentData AS Data 
                                                JOIN Content AS UserItem ON Data.ContentId = UserItem.Id  AND UserItem.TemplateId = %i AND UserItem.Deleted = 0
                                                WHERE  Data.ItemName = %s AND Value=%s", $useTemplateId, $key, $value)->fetchAll();

                            $id = empty($test) ? 0 : $test[0]["ContentId"];
                        }
                    } else {
                        $res = array();
                        if ($testColumnUserItem == -1 && $key == "Id") {

                            $res = dibi::query("SELECT * FROM USERITEMDETAIL WHERE LangId = %i AND GroupId = %i AND Id = %i AND IsLast =1", $langId, $users->GetUserGroupId(), $value)->fetchAll();
                        } else if ($testColumnUserItem == -2 && $key == "SeoUrl") {

                            $res = dibi::query("SELECT * FROM USERITEMDETAIL WHERE LangId = %i AND GroupId = %i AND SeoUrl = %s AND IsLast =1", $langId, $users->GetUserGroupId(), $value)->fetchAll();
                        } else if ($testColumnUserItem == -3 && $key == "Name") {

                            $res = dibi::query("SELECT * FROM USERITEMDETAIL WHERE LangId = %i AND GroupId = %i AND Name = %s AND IsLast =1", $langId, $users->GetUserGroupId(), $value)->fetchAll();
                        } else if ($testColumnUserItem == -4 && $key == "Identificator") {

                            $res = dibi::query("SELECT * FROM USERITEMDETAIL WHERE LangId = %i AND GroupId = %i AND Identificator = %s AND IsLast =1", $langId, $users->GetUserGroupId(), $value)->fetchAll();
                        }
                        if (!empty($res)) {
                            $id = $res[0]["Id"];
                            $seo = empty($seo) ? $res[0]["SeoUrl"] : $seo;
                        }
                    }
                }

                if ($mode == "Insert" && $id > 0)
                    continue;
                if ($mode == "Update" && $id == 0)
                    continue;

                if ($id == 0) {

                    $this->CreateUserItem($name, $seo, $availableOverSeoUrl, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $template, true, $langId, $xmlUserItem, array(), $dataSave);
                } else {
                    $this->UpdateUserItem($id, $name, $seo, $availableOverSeoUrl, $noIncludeSearch, $identificator, $activeFrom, $activeTo, $template, true, array(), $dataSave);
                }
            }
        }
    }

    private function ImportXmlData($xml, $domain, $mode, $testColumn) {

        /* echo $mode;
          die(); */
        if ($mode == "")
            return;
        $ud = \Model\UserDomainsItems::GetInstance();
        $userDomain = $ud->GetUserDomainItemById($domain);
        $valueTest = "";
        $values = \Model\UserDomainsValues::GetInstance();
        if ($mode == "DeleteInsert") {
            $values->DeleteAllValues($domain);
            $mode = "Insert";
        }
        $importColumns = array();
        foreach ($userDomain as $row) {
            $xpath = $row["XmlSettings"];
            if (!empty($xpath))
            {
                $importColumns[] = $xpath;
            }
        }
        print_r($xml);die();
        
        
        $prepareArray = array();
        $usedKeys = array();
        $maxItems = 0;

        foreach ($userDomain as $row) {
            $xpath = $row["XmlSettings"];
            $result = $xml[$xpath];
            $key = $row["Identificator"];
            if ($row["Id"] == $testColumn) {
                $valueTest = $row["Identificator"];
            }
            $usedKeys[] = $key;
            $addArray = array();

            while (list(, $node) = each($result)) {
                $addArray[] = trim($node);
            }
            if (count($addArray) > $maxItems)
                $maxItems = count($addArray);
            $prepareArray[$key] = $addArray;
        }



        for ($i = 0; $i < $maxItems; $i++) {
            $saveData = array();
            for ($y = 0; $y < count($usedKeys); $y++) {
                $key = $usedKeys[$y];
                $value = $prepareArray[$key][$i];
                $saveData[$key] = $value;
            }
            $objectId = $values->GetObjectId($domain, $testColumn, $saveData[$valueTest]);
            if ($objectId > 0 && $mode == "Insert")
                continue;
            if ($objectId == 0 && $mode == "Update")
                continue;

            $saveData["Id"] = $objectId;
            $saveData["DomainIdentificator"] = $row["DomainIdentificator"];

            
            $values->SaveUserDomainData($saveData);
        }
    }

    private function XmlDetail($seoUrl) {
        $user = Users::GetInstance();
        $groupId = $user->GetUserGroupId();
        $langId = $this->GetLangIdByWebUrl();
        $res = dibi::query("SELECT * FROM FRONTENDXML WHERE SeoUrl = %s AND GroupId = %i AND  LangId = %i", $seoUrl, $groupId, $langId)->fetchAll();
        return $xmlstring = $res[0]["data"];
    }

    public function  LoadFrontendFromIdentificator($identificator, $usergroup, $langId, $webId) {
        $contentId = $this->GetIdByIdentificator($identificator, $webId);
        return $this->LoadFrontend($contentId, $usergroup, $langId, $webId);
    }

    public function LoadFrontend($contentId, $usergroup, $langId, $webId, $limitChild = 0, $sort = "", $subItems = false, $ignoreActiveUrl = false, $addParent = false, $acceptItems = "", $ignoredId = "",$ignoreAlternativeItems = IGNORE_ALTERNATIVE_CONTENT,$where = "",$whereColumn = "") {
        if (!$ignoreAlternativeItems)
        {
            $alternativeTest = $this->GetAlternativeItems($contentId, $langId, $usergroup);
            if (!empty($alternativeTest)) {
                $identificator = $alternativeTest[0]["Identificator"];
                if (empty($identificator)) {
                    $res = $this->LoadFrontend($alternativeTest[0]["AlternativeContentId"], $usergroup, $langId, $webId, $limitChild, $sort, $subItems, $ignoreActiveUrl, $addParent, $acceptItems, $ignoredId,$ignoreAlternativeItems,$where ,$whereColumn );
                    return $res;
                }
            }
        }
        $columns = "";
        
        $limit = $limitChild == 0 ? "" : "LIMIT 0,$limitChild";

        if (!empty($sort)) {
            $sort = trim($sort);
            if (StringUtils::StartWidth($sort, "##")) {
                $sort = StringUtils::RemoveString($sort, "##");
                $sortType = StringUtils::GetLastWord($sort);
                $column = trim(StringUtils::RemoveLastWord($sort));
                $columns .= ", GROUP_CONCAT(if(ItemName = '$column', value, NULL)) AS '$column'";
                $sort = empty($sort) ? "" : "ORDER BY `" . $column . "` " . $sortType;
            } else {
                $sort = "ORDER BY $sort";
            }
        }
       
        $ignoreQuery = "";
        $acceptQuery = "";
        $acceptTable = "";
        if ($ignoreActiveUrl) {
            $ignoreQuery .= " AND child.Id <> $contentId ";
        }
        if (!empty($ignoredId)) {
            $ar = explode(",", $ignoredId);
            for ($y = 0; $y < count($ar); $y++) {
                $ignoreQuery .= " AND child.Id <> $ar[$y] ";
            }
        }
        
        if (!empty($acceptItems)) {

            $ar = explode(",", $acceptItems);
            for ($y = 0; $y < count($ar); $y++) {

                if (empty($acceptQuery))
                    $acceptQuery .= "  FRONTENDTEMPLATES.Identificator  =  '$ar[$y]' ";
                else
                    $acceptQuery .= " OR  FRONTENDTEMPLATES.Identificator  =  '$ar[$y]' ";
            }
            $acceptTable = "  WHERE ($acceptQuery) ";
        }
        
        if (!empty($whereColumn))
        {
            $ar = explode(",", $whereColumn);
            foreach ($ar as $column)
            {
              $columnsWhere .= ", GROUP_CONCAT(if(ItemName = '$column', value, NULL)) AS '$column'";
                
            }
            $columns .= $columnsWhere;
            if(!empty($where))
            {
                if (empty($acceptTable))
                    $acceptTable = " WHERE (". $where.")";
                else 
                    $acceptTable = " AND (". $where.")";
            }
        }
        
         if (!empty($columns)) {
            $columns = "LEFT JOIN (           
                            SELECT
                                ContentData.ContentId
                                $columns
                                FROM ContentData
                                WHERE ContentData.LangId = $langId
                                GROUP BY ContentId
                               ) AS ContentTable
                            ON child.Id = ContentTable.ContentId  ";
        }
        $loadByParents = "";
        if (empty($where))
        {
            $loadByParents = "AND parents.Id = $contentId";
            
        }
       
       

        
        $res = dibi::query("SELECT DISTINCT child.Date,child.Sort,child.TemplateId,child.Id,child.Name,child.SeoUrl,child.Data,child.Header	,parents.Identificator AS parentIdentificator FROM `FrontendDetail_materialized` AS parents "
                        . " INNER JOIN FrontendDetail_materialized AS child ON parents.Id = child.ParentId  $loadByParents AND (child.GroupId =%i OR (child.ContentType='link' ))  AND child.LangId = %i $ignoreQuery $columns  "
                        . "LEFT JOIN  FRONTENDTEMPLATES ON child.TemplateId =  FRONTENDTEMPLATES.Id   AND FRONTENDTEMPLATES.LangId = %i AND FRONTENDTEMPLATES.GroupId = %i  "
                        . " $acceptTable  $sort $limit", $usergroup, $langId, $langId, $usergroup)->fetchAll();
       
        
        if (empty($res) && !$subItems && !$ignoreActiveUrl) {
                $res = dibi::query("SELECT Date,Sort,TemplateId,Id, GroupId,WebId,LangId,Name,SeoUrl,Data,Header FROM FrontendDetail_materialized WHERE  Id  = %i AND  (GroupId =%i OR (ContentType='link' ))AND LangId = %i", $contentId, $usergroup, $langId)->fetchAll();
            return $res;
        }
        if ($addParent) {
            $resParent = dibi::query("SELECT Date,Sort,TemplateId,Id, GroupId,WebId,LangId,Name,SeoUrl,Data,Header FROM FrontendDetail_materialized WHERE Id  = %i AND (GroupId =%i OR (ContentType='link' ))  AND LangId = %i", $contentId, $usergroup, $langId)->fetchAll();
            $res = array_merge($resParent, $res);
        }

        if ($subItems) {
            if (!empty($res)) {
                foreach ($res as $row) {
                        
                    $childs = $this->LoadFrontend($row["Id"], $usergroup, $langId, $webId, $limitChild, $sort, $subItems, $ignoreActiveUrl, $addParent, $acceptItems, $ignoredId,$ignoreAlternativeItems);
                    if (!empty($childs))
                        $row["Child"] = $childs;
                }
            }
        }

        return $res;
    }

    private function GetIdList($id, $usergroup, $langId, $subItems = false) {
        $resChild = dibi::query("SELECT DISTINCT child.Id FROM `FrontendDetail_materialized` AS parents "
                        . "INNER JOIN FrontendDetail_materialized AS child ON parents.Id = child.ParentId WHERE parents.Id = %i AND (child.GroupId =%i OR (child.ContentType='link' AND child.GroupId IS NULL))  AND child.LangId = %i", $id, $usergroup, $langId)->fetchAll();
        $out = $resChild;

        if ($subItems && !empty($resChild)) {
            foreach ($resChild as $row) {
                $child = $this->GetIdList($row["Id"], $usergroup, $langId, $subItems);
                $out = array_merge($out, $child);
            }
        }
        return $out;
    }

    public function LoadFrontendFromId($id, $usergroup, $langId, $webId, $limitChild = 0, $sort = "", $subItems = false) {
        return $this->LoadFrontend($id, $usergroup, $langId, $webId, $limitChild, $sort, $subItems);
    }

    public function GetTree($langId, $parentId = 0, $search = "", $userId = 0) {

        $langFolderInfo = dibi::query("SELECT CONTENTTREE.Id,  Langs.LangName,CONTENTTREE.ParentId FROM CONTENTTREE 
                                        LEFT JOIN Langs ON CONTENTTREE.LangId =Langs.Id
                WHERE LangId = %i AND ContentType= 'langfolder' ", $langId)->fetchAll();
        
        if ($parentId == -1) {
            return $langFolderInfo;
        }
        $langContent = array();
        if ($search == "") {

            if ($userId == 0)
                $langContent = dibi::query("SELECT * FROM CONTENTTREE WHERE LangId = %i AND (ContentType= 'UserItem' OR ContentType= 'Link' OR ContentType= 'ExternalLink' OR ContentType= 'JavascriptAction') AND Deleted = 0", $langId)->fetchAll();
            else
                $langContent = dibi::query("SELECT * FROM CONTENTTREE WHERE Owner = %i AND LangId = %i AND (ContentType= 'UserItem' OR ContentType= 'Link' OR ContentType= 'ExternalLink' OR ContentType= 'JavascriptAction') AND Deleted = 0", $userId, $langId)->fetchAll();

            //print_r($langContent);    
            if ($parentId == 0) {
                $out = array();
                $out[0]["Id"] = $langFolderInfo[0]["Id"];
                $out[0]["Name"] = $langFolderInfo[0]["LangName"];
                $out[0]["child"] = $this->CreateTree($langContent, "Id", "ParentId", $langFolderInfo[0]["Id"]);
                return $out;
            } else if ($parentId > 0) {
                return $this->GetChild($langContent, $parentId, "ParentId", "Id");
            }
        } else {
            $langContent = dibi::query("SELECT * FROM CONTENTTREE WHERE LangId = %i AND (ContentType= 'UserItem' OR ContentType= 'Link' OR ContentType= 'ExternalLink' OR ContentType= 'JavascriptAction') AND Deleted = 0 AND CONTENTTREE.Name LIKE %~like~", $langId, $search)->fetchAll();

            $out[0]["Id"] = $langFolderInfo[0]["Id"];
            $out[0]["Name"] = $langFolderInfo[0]["LangName"];
            $out[0]["child"] = $langContent;
            //Wprint_r($out);
            return $out;
        }
    }

    public function GetFileTree($langId, $parentId = 0, $search = "") {

        $langFolderInfo = dibi::query("SELECT CONTENTTREE.Id,  Langs.LangName,CONTENTTREE.ParentId FROM CONTENTTREE 
                                        LEFT JOIN Langs ON CONTENTTREE.LangId =Langs.Id
                WHERE LangId = %i AND ContentType= 'langfolder' ", $langId)->fetchAll();
        $langContent = array();
        if ($search == "") {
            $langContent = dibi::query("SELECT * FROM CONTENTTREE  WHERE LangId = %i AND (ContentType =  'FileFolder' OR ContentType =  'FileUpload')  AND Deleted = 0", $langId)->fetchAll();

            if ($parentId == 0) {
                $outArray = array();
                $out[0]["Id"] = $langFolderInfo[0]["Id"];
                $out[0]["Name"] = $langFolderInfo[0]["LangName"];
                $out[0]["child"] = $this->CreateTree($langContent, "Id", "ParentId", $langFolderInfo[0]["Id"]);
                return $out;
            } else if ($parentId > 0) {
                return $this->GetChild($langContent, $parentId, "ParentId", "Id");
            }
        } else {
            $langContent = dibi::query("SELECT * FROM CONTENTTREE  WHERE LangId = %i AND (ContentType =  'FileFolder' OR ContentType =  'FileUpload')  AND Deleted = 0 AND Name LIKE %~like~  ORDER BY `CONTENTTREE`.`Id`  DESC", $langId, $search)->fetchAll();

            $out[0]["Id"] = $langFolderInfo[0]["Id"];
            $out[0]["Name"] = $langFolderInfo[0]["LangName"];
            $out[0]["child"] = $langContent;

            return $out;
        }
    }

    /*public function GetRepositoryByWebId($id) {
        
    }

    public function CreateWebRepository($webId) {
        $name = "webrepository_" . $webId;
        if (!$this->ItemExists("Name", $name)) {
            $this->CreateContentItem($name, true, "", 0, ContentTypes::$Repository, false, $lang, $parentid, $noIncludeSearch);
        }
    }*/

    private function CreateTree($inData, $idColumn, $parentIdColumn, $idP = "", $noConnectedItems = false) {
        
        $outData = array();
        $i = 0;
        foreach ($inData as $row) {
            if (($row["ContentType"] == ContentTypes::$LangFolder || $row["ContentType"] == ContentTypes::$UserItem || $row["ContentType"] == ContentTypes::$FileFolder || $row["ContentType"] == ContentTypes::$FileUpload) && ($row[$parentIdColumn] == $idP || empty($idP))) {
                $outData[$i] = $row;
                $id = $row[$idColumn];
                $outData[$i]["child"] = $this->GetChild($inData, $id, $parentIdColumn, $idColumn);
                $i++;
            }
            
        }
        return $outData;
    }

    private function GetChild($inData, $id, $parentIdColumn, $idColumn) {
        $outData = array();
        $i = 0;

        foreach ($inData as $row) {
            if ($row[$parentIdColumn] == $id) {
                $outData[$i] = $row;
                $idTmp = $row[$idColumn];
                if ($row["ContentType"] == ContentTypes::$LangFolder || $row["ContentType"] == ContentTypes::$UserItem || $row["ContentType"] == ContentTypes::$FileFolder || $row["ContentType"] == ContentTypes::$FileUpload) {
                    $outData[$i]["child"] = $this->GetChild($inData, $idTmp, $parentIdColumn, $idColumn);
                }
                $i++;
            }
        }
        return $outData;
    }

    private function ChildCheckPrivileges($inData, $id, $parentIdColumn, $idColumn, $privilegesName) {
        foreach ($inData as $row) {
            if ($row[$parentIdColumn] == $id) {
                $idTmp = $row[$idColumn];
                if ($row["ContentType"] == ContentTypes::$LangFolder || $row["ContentType"] == ContentTypes::$UserItem || $row["ContentType"] == ContentTypes::$FileFolder || $row["ContentType"] == ContentTypes::$FileUpload) {

                    return $this->ChildCheckPrivileges($inData, $idTmp, $parentIdColumn, $idColumn, $privilegesName);
                }
            }
        }
        return TRUE;
    }

    public function DeleteItem($id) {

        if ($this->IsFolder($id)) {

            return FALSE;
        }
        if (!$this->HasPrivileges($id, PrivilegesType::$CanDelete, true)) {

            return FALSE;
        }

        $content = Content::GetInstance();
        $security = ContentSecurity::GetInstance();
        $content->DeleteObject($id, false, false);
        $this->DeleteByCondition("ContentId = $id", false, false);
        $security->DeleteByCondition("ObjectId = $id", false, false);
        $langId = $_GET["langid"];
        $childs = $this->GetTree($langId, $id);
        if (!empty($childs)) {
            foreach ($childs as $child) {
                $this->DeleteItem($child["Id"]);
            }
        }
        return TRUE;
    }

    public function RecoveryItem($id) {
        dibi::query("UPDATE Content SET Deleted = 0 WHERE Id = %i", $id);
        dibi::query("UPDATE ContentVersion SET Deleted = 0 WHERE ContentId = %i", $id);
        dibi::query("UPDATE ContentSecurity SET Deleted = 0 WHERE ObjectId = %i", $id);
    }

    public function HasPrivileges($id, $privilegesName, $checkTree = false, $groupId = 0) {
        try{
        $user = Users::GetInstance();
        if ($this->IsLink($id))
            return true;
        if ($groupId == 0) {
            $groupId = $user->GetUserGroupId();
        }
        if ($this->IsFolder($id)) {
            $web = \Model\Webs::GetInstance();
            $web->GetObjectById($_GET["webid"], true);
            $xml = $web->WebPrivileges;
            $ar = ArrayUtils::XmlToArray($xml);
            if ($user->IsSystemUser())
                return true;
            foreach ($ar["item"] as $row) {
                if ($row["UserGroup"] == $groupId && $row["PrivilegesName"] == $privilegesName && $row["Value"] == "true")
                    return true;
            }
            return false;
        }


        if (PrivilegesType::$CanPublish == $privilegesName) {
            
        }
        if (PrivilegesType::$CanChangePrivileges == $privilegesName) {
            
        }

        $res = dibi::query("SELECT Value FROM ContentSecurity WHERE SecurityType = %s AND ObjectId =%i AND GroupId = %i AND Deleted = 0 ORDER BY Id DESC", $privilegesName, $id, $groupId)->fetchAll();


        if (empty($res)) {
            return false;
        }
        foreach ($res as $row) {
            if ($row["Value"] == 0)
                return false;
        }
        if ($checkTree) {
            $res = dibi::query("select Id from CONTENTTREE WHERE ParentId =%i AND Deleted= 0", $id)->fetchAll();
            $out = true;
            foreach ($res as $row) {
                $privileges = $this->HasPrivileges($row["Id"], $privilegesName, $checkTree, $groupId);
                if (!$privileges) {

                    $out = false;
                    break;
                }
            }
            return $out;
        }

        return true;
        }
        catch (Exception $e)
        
        {
            echo $e;die();
        }
    }

    public function IsFolder($contentId) {
        if ($contentId == 0)
            return true;
        $content = Content::GetInstance();
        $content->GetObjectById($contentId, true);
        if ($content->ContentType == ContentTypes::$LangFolder)
            return true;
        return false;
    }

    public function GetContentType($contentId) {
        $content = Content::GetInstance();
        $content->GetObjectById($contentId, true);
        return $content->ContentType;
    }

    public function LoadTemplateByIdentificator($identificator, $groupId, $langId, $webId) {
        
        
        $res = dibi::query("SELECT TemplateId, Header, data,ContentType,Id,ParentId,Name FROM FRONTENDTEMPLATES WHERE  Identificator =%s AND GroupId = %i  AND  LangId = %i  ",$identificator, $groupId, $langId)->fetchAll();
        return $this->GetFirstRow($res);
    }

    public function LoadTemplateById($id, $groupId, $langId, $webId) {
        $res = dibi::query("SELECT TemplateId, Header, data,ContentType,Id,ParentId,Name FROM FRONTENDTEMPLATES WHERE Id =%i AND GroupId = %i AND  LangId = %i ", $id, $groupId, $langId)->fetchAll();
        return $this->GetFirstRow($res);
    }

    public function OnCreateTable() {
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name = "ContentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";

        $colContentType->Length = 255;
        $colContentType->Name = "Name";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 1;
        $colContentType->Name = "IsActive";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = true;
        $colContentType->IsNull = false;
        $colContentType->Length = 1;
        $colContentType->Name = "IsLast";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name = "Author";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        //PublishUser
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name = "PublishUser";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Length = 255;
        $colContentType->Name = "SeoUrl";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Length = 9;
        $colContentType->Name = "Template";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Length = 1;
        $colContentType->Name = "AvailableOverSeoUrl";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "Data";
        $colContentType->Type = "LONGTEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "Header";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "ActiveFrom";
        $colContentType->Type = "DATETIME";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "ActiveTo";
        $colContentType->Type = "DATETIME";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

     

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "Date";
        $colContentType->Type = "DATETIME";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "ContentSettings";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
    }

    public function InsertDefaultData() {
        
    }

    public function CreateHtml($tree, $isRoot = true, $parentId = "", $showChild = false, $blockMove = false, $setSelectId = true, $dialogId = "") {
        $html = "";

        $table = new HtmlTable();
        $position = 1;
        foreach ($tree as $row) {

            $tr = new HtmlTableTr();
            if (!empty($parentId)) {
                $tr->CssClass = "treegrid-" . $row["Id"] . " treegrid-parent-" . $parentId;
            } else {
                $tr->CssClass = "treegrid-" . $row["Id"];
            }
            $tr->Id = $row["Id"];
            $td = new HtmlTableTd();
            $td->Html = "<span class='itemName'>" . $row["Name"] . "</span>";
            $tr->SetChild($td);
            if ($isRoot) {
                $tr->CssClass .= " RootItem ";
                if ($setSelectId)
                    $tr->OnClick = "ItemClick('root',this,true,'$dialogId');";
                else
                    $tr->OnClick = "ItemClick('root',this,false,'$dialogId');";
                $tr->AddAtrribut("contentType", "root");
                $td = new HtmlTableTd();
                $td->Html = "&nbsp;";
                $tr->SetChild($td);
            } else {
                if (empty($row["Sort"]))
                    $row["Sort"] = 0;
                if ($row["Sort"] == "0") {
                    $row["Sort"] = $position;
                    $this->SavePosition($row["Id"], $position);
                } else {
                    $position = $row["Sort"];
                }
                $position++;
                $tr->CssClass .= " ChildItem ";
                if ($setSelectId)
                    $tr->OnClick = "SetChildClick(true);ItemClick('child',this,true,'$dialogId');";
                else
                    $tr->OnClick = "SetChildClick(true);ItemClick('child',this,false,'$dialogId');";
                $tr->OnDoubleClick = "ItemDoubleClick();return false;";
                $tr->AddAtrribut("contentType", $row["ContentType"]);
                $tdMoveUp = new HtmlTableTd();
                $aMoveUp = new Link();
                $awesomeUp = new FontAwesome();
                $awesomeUp->Style = "font-size:35px;";
                $awesomeUp->SetIcon("angle-up");
                $awesomeUp->OnClick = "MoveUp('" . $row["Id"] . "','" . $row["ContentType"] . "');return false;";
                $tdMoveUp->SetChild($awesomeUp);
                $awesomeDown = new FontAwesome();
                $awesomeDown->Style = "font-size:35px;";
                $awesomeDown->SetIcon("angle-down");
                $awesomeDown->OnClick = "MoveDown('" . $row["Id"] . "','" . $row["ContentType"] . "');return false;";
                $tdMoveUp->SetChild($awesomeDown);
                if (!$blockMove) {
                    $tr->SetChild($tdMoveUp);
                }
            }



            $html .= $tr->RenderHtml($tr);
            if (!empty($row["child"])) {
                $html .= $this->CreateHtml($row["child"], false, $row["Id"], $showChild, $blockMove, $setSelectId, $dialogId);
            }
        }
        return $html;
    }

    public function Move($sourceId, $destinationId) {
        if (!$this->HasPrivileges($sourceId, PrivilegesType::$CanRead, true) || !$this->HasPrivileges($destinationId, PrivilegesType::$CanWrite))
            return FALSE;
        $content = Content::GetInstance();
        if ($this->IsLink($destinationId))
            return FALSE;
        $content->MoveItem($sourceId, $destinationId, "ParentId");
        return TRUE;
    }

    public function Copy($langId, $webId, $sourceId, $destinationId, $copyChild = true) {
        if (!$this->HasPrivileges($sourceId, PrivilegesType::$CanRead, true) || !$this->HasPrivileges($destinationId, PrivilegesType::$CanWrite, true)) {
            return FALSE;
        }
        if ($this->IsLink($destinationId))
            return FALSE;

        $user = Users::GetInstance();
        $groupId = $user->GetUserGroupId();
        $rootCopy = $this->GetUserItemDetail($sourceId, $groupId, $webId, $langId);
        if (!empty($rootCopy)) {
            $copyData = $this->PrepareCopy($rootCopy);
            $connectionObjects = dibi::query("SELECT * FROM CONNECTIONOBJECTS WHERE ObjectId = %i AND  (LangId = %i OR LangId =0 ) ", $sourceId, $langId)->fetchAll();
            $data = $copyData["Data"];
            $destinationId = $this->CreateUserItem($copyData["Name"], "", $copyData["AvailableOverSeoUrl"] == 1 || $copyData["AvailableOverSeoUrl"] == "1" ? true : false, $copyData["NoIncludeSearch"] == 1 || $copyData["NoIncludeSearch"] == "1" ? true : false, "", $copyData["ActiveFrom"], $copyData["ActiveTo"], $copyData["TemplateId"], false, $langId, $destinationId, array(), $data, true, $copyData["GallerySettings"], 3, $copyData["DiscusionId"], $copyData["FormId"], false);
            $contentConnection = ContentConnection::GetInstance();
            foreach ($connectionObjects as $obj) {
                $contentConnection->CreateConnection($destinationId, $obj["ObjectIdConnected"], $obj["ConnectedType"], $obj["SettingConnection"]);
            }
            if ($copyChild) {
                $childs = $this->GetTree($langId, $sourceId);
                if (!empty($childs)) {
                    foreach ($childs as $child) {
                        return $this->Copy($langId, $webId, $child["Id"], $destinationId, $copyChild);
                    }
                }
            }
        }
        return TRUE;
    }

    private function PrepareCopy($copyData) {
        $out = array();
        $out = $copyData[0];
        $privileges = array();
        $i = 0;
        foreach ($copyData as $row) {
            $privileges[$i][0] = $row["SSSecurityType"];
            $privileges[$i][1] = $row["SSGroupId"];
            $privileges[$i][2] = $row["SSValue"] == 1 || $row["SSValue"] == "1" ? true : false;
            $i++;
        }
        $out["privileges"] = $privileges;
        return $out;
    }

    public function GetIdByIdentificator($identificator, $webid = 0) {

        if (empty($identificator))
            return 0;
        $content = Content::GetInstance();
        $data = array();
        if ($webid == 0) {
            $data = $content->SelectByCondition("Identificator = '$identificator' AND Deleted= 0");
        } else {
            $data = dibi::query("SELECT Content.Id FROM Content 
                    LEFT JOIN ContentVersion ON   ContentVersion.ContentId = Content.Id 
                    WHERE Identificator = '$identificator' AND Content.Deleted= 0 AND ContentVersion.WebId = $webid"
                    )->fetchAll();
            //$data = $content->SelectByCondition("Identificator = '$identificator' AND Deleted= 0");
        }
        if (empty($data))
            return 0;
        return $data[0]["Id"];
    }

    public function GetIdGalleryBySeoUrl($seourl, $langid, $webid) {
        $out = dibi::query(
                        "SELECT Content.GallerySettings,Content.GalleryId,Content.Id  FROM Content 
                    JOIN ContentVersion ON Content.Id = ContentVersion.ContentId AND ContentVersion.SeoUrl = %s AND ContentVersion.LangId = %i AND ContentVersion.WebId = %i AND ContentVersion.Deleted = 0 AND ContentVersion.IsActive = 1
                ", $seourl, $langid, $webid)->fetchAll();

        if ($out[0]["GallerySettings"] == 3) {
            $testId = $out[0]["GalleryId"];
            for ($i = 0;;) {
                $out2 = dibi::query(
                                "SELECT Content.GallerySettings,Content.GalleryId,Content.Id FROM Content 
                    JOIN ContentVersion ON Content.Id = ContentVersion.ContentId AND Content.Id = %i AND ContentVersion.LangId = %i AND ContentVersion.WebId = %i AND ContentVersion.Deleted = 0 AND ContentVersion.IsActive = 1
                ", $testId, $langid, $webid)->fetchAll();
                if ($out2[0]["GallerySettings"] == 3) {
                    $testId = $out2[0]["GalleryId"];
                    continue;
                }
                return $out2[0]["Id"];
                break;
            }
        }
        return $out[0]["Id"];
    }

    public function GetParentBySeoUrl($usergroup, $webId, $langId, $seoUrl, $level = 0, $idetificator = "") {
        $res = dibi::query("SELECT Id, ParentId FROM FrontendDetail_materialized WHERE SeoUrl = %s AND  GroupId =%i AND WebId = %i AND LangId = %i  AND AvailableOverSeoUrl = 1 ", $seoUrl, $usergroup, $webId, $langId)->fetchAll();
        $parentId = $res[0]["ParentId"];
        if ($this->IsFolder($parentId) || $level == 1)
            return $res[0]["Id"];
        $step = 1;
        $lastParentId = $parentId;
        for (;;) {
            $parentId = $this->GetParent($parentId);
            if ($this->IsFolder($parentId) || $step == $level) {
                return $lastParentId;
            }
            $step++;
            $lastParentId = $parentId;
        }
    }

    public function GetFormIdBySeoUrl($seourl, $langid, $webid) {
        $out = dibi::query(
                        "SELECT Content.FormId FROM Content 
                    JOIN ContentVersion ON Content.Id = ContentVersion.ContentId AND ContentVersion.SeoUrl = %s AND ContentVersion.LangId = %i AND ContentVersion.WebId = %i AND ContentVersion.Deleted = 0 AND ContentVersion.IsActive = 1
                ", $seourl, $langid, $webid)->fetchAll();
        if (empty($out))
            return 0;
        return $out[0]["FormId"];
    }

    public function GetSurveyId($seourl, $langid, $webid) {
        $out = dibi::query(
                        "SELECT Content.Inquery FROM Content 
                    JOIN ContentVersion ON Content.Id = ContentVersion.ContentId AND ContentVersion.SeoUrl = %s AND ContentVersion.LangId = %i AND ContentVersion.WebId = %i AND ContentVersion.Deleted = 0 AND ContentVersion.IsActive = 1
                ", $seourl, $langid, $webid)->fetchAll();
        if (empty($out))
            return 0;
        return $out[0]["Inquery"];
    }

    public function GetDiscusionIdBySeoUrl($seourl, $langid, $webid) {
        $out = dibi::query(
                        "SELECT Content.ContentType,Content.Id,Content.DiscusionId FROM Content 
                    JOIN ContentVersion ON Content.Id = ContentVersion.ContentId AND ContentVersion.SeoUrl = %s AND ContentVersion.LangId = %i AND ContentVersion.WebId = %i AND ContentVersion.Deleted = 0 AND ContentVersion.IsActive = 1
                ", $seourl, $langid, $webid)->fetchAll();
        if (empty($out))
            return 0;
        if ($out[0]["ContentType"] == ContentTypes::$Discusion)
            return $out[0]["Id"];
        return $out[0]["DiscusionId"];
    }

    public function GetTempateDetailByIdentificator($identificator, $groupId, $langId, $webId) {
        $res = dibi::query("SELECT * FROM TEMPLATEDETAIL WHERE WebId = %i AND LangId = %i AND GroupId = %i AND Identificator = %s", $webId, $langId, $groupId, $identificator)->fetchAll();
        return $res;
    }

    public function GetLangRoot($langid = 0) {
        if ($langid == 0)
            $langid = $_GET["langid"];
        $langInfo = $this->GetTree($langid, -1);
        if (empty($langInfo))
            return 0;
        return $langInfo[0]["Id"];
    }

    public function GetArticleDiscusion($id) {
        $content = Content::GetInstance();
        $content->GetObjectById($id, true);
        return $content->DiscusionId;
    }

    private function PrepareWhereFomId($idList) {
        if (empty($idList))
            return "";
        $where = "";
        foreach ($idList as $row) {
            if (!empty($where))
                $where = $where . " OR ";
            $where = $where . " child.Id = " . $row["Id"];
        }
        return $where;
    }

    public function GetArticleBySeoUrl($seoUrl, $usergroup, $langId, $webId, $preview = false, $subItems = false, $where = "", $colums = "", $sort = "", $limitLoad = "start") {
        $resChild = array();
        $res = array();
        $tableName = !$preview ? "FrontendDetail_materialized" : "FRONTENDDETAILPREVIEW";
        $res = dibi::query("SELECT DISTINCT Date,Sort, SaveToCache,NoLoadSubItems,ActivatePager, FirstItemLoadPager, NextItemLoadPager,TemplateId,Id,Name,SeoUrl,Data,Header,ActiveFrom,ContentType,  
                ActiveTo,NoIncludeSearch,Identificator,ParentId FROM $tableName WHERE SeoUrl = %s  AND GroupId =%i AND WebId = %i AND LangId = %i  AND AvailableOverSeoUrl = 1 ", $seoUrl, $usergroup, $webId, $langId)->fetchAll();

        if (empty($res))
            return array();
        $checkalternative = $this->GetAlternativeItems($res[0]["Id"], $langId, $usergroup);
        if (!empty($checkalternative)) {
            return $this->GetArticleBySeoUrl($checkalternative[0]["SeoUrl"], $usergroup, $langId, $webId, $preview, $subItems, $where, $colums, $sort, $limitLoad);
        }
        return $res;
      

        
    }

    public function RenderSendEmail($id, $langId, $webId) {
        $res = dibi::query("SELECT * FROM SENDMAILDETAIL WHERE  WebId = %i AND LangId = %i AND Id = %i  ", $webId, $langId, $id)->fetchAll();
        return $res;
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("Data", RuleType::$RemoveEntity);
    }

    public function ItemExistsInLang($contentId, $langId) {
        $res = $this->SelectByCondition(" Deleted = 0 AND ContentId = $contentId AND LangId = $langId","",array("ContentId"));
        if (empty($res))
            return FALSE;
        return TRUE;
    }

    public function CopyLang($sourceLang, $destinationLang) {
        
        if ($sourceLang == $destinationLang)
            return;

        $user = Users::GetInstance();
        $usergroup = $user->GetUserGroupId();
        $useritems = dibi::query("SELECT * FROM CONTENTTREE WHERE LangId = %i AND (ContentType= 'UserItem' OR ContentType= 'Link' OR ContentType= 'ExternalLink') AND Deleted = 0", $sourceLang)->fetchAll();
        $this->CopyLangItem($useritems, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$UserItem);

        $templates = $this->GetTemplateList($usergroup, $sourceLang, true, false);
        $this->CopyLangItem($templates, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Template);

        $templatesDomain = $this->GetTemplateList($usergroup, $sourceLang, true, true);
        $this->CopyLangItem($templatesDomain, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Template);

        $css = $this->GetCssList($usergroup, $sourceLang, true);
        $this->CopyLangItem($css, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Css);

        $js = $this->GetJsList($usergroup, $sourceLang, true);
        $this->CopyLangItem($js, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Javascript);

        $forms = $this->GetFormsList($usergroup, $sourceLang, true);
        $this->CopyLangItem($forms, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Form);

        $mails = $this->GetMailList($usergroup, $sourceLang, true);
        $this->CopyLangItem($mails, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Mail);

        $files = dibi::query("SELECT * FROM CONTENTTREE  WHERE LangId = %i AND (ContentType =  'FileFolder')  AND Deleted = 0 ORDER BY `CONTENTTREE`.`Id`  DESC", $sourceLang)->fetchAll();
        $this->CopyLangItem($files, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$FileFolder);

        $files = dibi::query("SELECT * FROM CONTENTTREE  WHERE LangId = %i AND (ContentType =  'FileUpload')  AND Deleted = 0 ORDER BY `CONTENTTREE`.`Id`  DESC", $sourceLang)->fetchAll();
        $this->CopyLangItem($files, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$FileUpload);

        $mailingList = $this->GetMailingList($usergroup, $sourceLang, true);
        $this->CopyLangItem($mailingList, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Mailing);

        $datasoures = $this->GetDataSourceList($usergroup, $sourceLang, true);
        $this->CopyLangItem($datasoures, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$DataSource);

        $surveysList = $this->GetInquryList($usergroup, $sourceLang, true);
        $this->CopyLangItem($surveysList, $sourceLang, $destinationLang, $usergroup, $user->GetUserId(), ContentTypes::$Inquery);
    }

    public function SetPosition($id, $mode, $contentType) {

        $res = array();
        if ($contentType == ContentTypes::$UserItem) {
            $parentId = $this->GetParent($id);
            $res = dibi::query("SELECT * FROM CONTENTTREE WHERE LangId = %i AND (ContentType= 'UserItem' OR ContentType= 'Link' OR ContentType= 'ExternalLink') AND Deleted = 0 AND ParentId = %i ORDER BY Sort ASC", $_GET["langid"], $parentId)->fetchAll();
        } else if ($contentType == ContentTypes::$Template) {
            $users = Users::GetInstance();
            $res = $this->GetTemplateList($users->GetUserId(), $_GET["langid"], true);
        } else if ($contentType == ContentTypes::$Css || $contentType == ContentTypes::$CssExternalLink) {
            $users = Users::GetInstance();
            $res = $this->GetCssList($users->GetUserId(), $_GET["langid"], true);
        } else if ($contentType == ContentTypes::$Javascript || $contentType == ContentTypes::$JsExternalLink) {
            $users = Users::GetInstance();
            $res = $this->GetJsList($users->GetUserId(), $_GET["langid"], true);
        } else if ($contentType == ContentTypes::$Form) {
            $users = Users::GetInstance();
            $res = $this->GetFormsList($users->GetUserId(), $_GET["langid"], true);
        } else if ($contentType == ContentTypes::$Mail) {
            $users = Users::GetInstance();
            $res = $this->GetMailList($users->GetUserId(), $_GET["langid"], true);
        } else if ($contentType == ContentTypes::$FileFolder || $contentType == ContentTypes::$FileUpload) {
            $res = dibi::query("SELECT * FROM CONTENTTREE  WHERE LangId = %i AND (ContentType =  'FileFolder' OR ContentType =  'FileUpload')  AND Deleted = 0", $_GET["langid"])->fetchAll();
        } else if ($contentType == ContentTypes::$Mailing) {
            $users = Users::GetInstance();
            $res = $this->GetMailingList($users->GetUserId(), $_GET["langid"], true);
        } else if ($contentType == ContentTypes::$DataSource) {
            $users = Users::GetInstance();
            $res = $this->GetDataSourceList($users->GetUserId(), $_GET["langid"], true);
        }

        $y = 0;
        $id2 = 0;
        $position1 = 0;
        $position2 = 0;
        if ($mode == "down") {
            $y = 0;
            for ($i = 0; $i < count($res); $i++) {
                $y++;
                if ($res[$i]["Id"] == $id) {
                    if (!empty($res[$y])) {
                        $id2 = $res[$y]["Id"];
                        $position1 = $res[$i]["Sort"];
                        $position2 = $res[$y]["Sort"];
                    }
                    break;
                }
            }
            if ($position1 == 99999)
                $position1 = $position2 + 1;
            if ($position2 == 99999)
                $position2 = $position1 + 1;
        } else if ($mode == "up") {
            $y = count($res);
            for ($i = count($res); $i >= 0; $i--) {
                $y--;
                if ($res[$i]["Id"] == $id) {
                    if (!empty($res[$y])) {
                        $id2 = $res[$y]["Id"];
                        $position1 = $res[$i]["Sort"];
                        $position2 = $res[$y]["Sort"];
                    }
                    break;
                }
            }
            if ($position1 == 99999)
                $position1 = $position2 - 1;
            if ($position2 == 99999)
                $position2 = $position1 - 1;
        }

        $this->SavePosition($id2, $position1);
        $this->SavePosition($id, $position2);
    }

    private function CopyLangItem($data, $sourceLang, $destinationLang, $usergroup, $userId, $mode) {
        foreach ($data as $row) {
            $id = $row["Id"];
            if (!$this->ItemExistsInLang($id, $destinationLang)) {
                $saveData = array();
                if ($mode == ContentTypes::$UserItem || $mode == ContentTypes::$Link || $mode == ContentTypes::$ExternalLink) {
                    $saveData = $this->GetUserItemDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }
                if ($mode == ContentTypes::$Template) {
                    $saveData = $this->GetTemplateDetail($usergroup, $_GET["webid"], $sourceLang, $id);
                }
                if ($mode == ContentTypes::$Css) {
                    $saveData = $this->GetCssDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }
                if ($mode == ContentTypes::$Javascript) {
                    $saveData = $this->GetJsDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }
                if ($mode == ContentTypes::$Form) {
                    $saveData = $this->GetFormDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }
                if ($mode == ContentTypes::$Mail) {
                    $saveData = $this->GetMailDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }

                if ($mode == ContentTypes::$FileFolder || $mode == ContentTypes::$FileUpload) {
                    $saveData = $this->GetFileFolderDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }
                if ($mode == ContentTypes::$Mailing) {
                    $saveData = $this->GetMailingDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }
                if ($mode == ContentTypes::$DataSource) {
                    $saveData = $this->GetDataSourceDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }
                if ($mode == ContentTypes::$Inquery) {
                    $saveData = $this->GetInqueryDetail($id, $usergroup, $_GET["webid"], $sourceLang);
                }

                $name = empty($saveData[0]["Name"]) ? "" : $saveData[0]["Name"];
                $isActive = empty($saveData[0]["IsActive"]) ? false : $saveData[0]["IsActive"];
                $seoUrl = empty($saveData[0]["SeoUrl"]) ? "" : $saveData[0]["SeoUrl"];
                $template = empty($saveData[0]["TemplateId"]) ? 0 : $saveData[0]["TemplateId"];
                $AvailableOverSeoUrl = empty($saveData[0]["AvailableOverSeoUrl"]) ? 0 : $saveData[0]["AvailableOverSeoUrl"];
                $data = empty($saveData[0]["Data"]) ? 0 : $saveData[0]["Data"];
                $header = empty($saveData[0]["Header"]) ? 0 : $saveData[0]["Header"];
                $activeFrom = empty($saveData[0]["ActiveFrom"]) ? 0 : $saveData[0]["ActiveFrom"];
                $activeTo = empty($saveData[0]["ActiveTo"]) ? 0 : $saveData[0]["ActiveTo"];

                $this->CreateVersion($id, $name, $isActive, $userId, $seoUrl, $template, $AvailableOverSeoUrl, $destinationLang, $data, $header, $activeFrom, $activeTo, true, $mode);
            }
        }
    }

    public function GetLinkDetail($contentId, $webId, $langId) {
        $res = dibi::query("SELECT * FROM LINKDETAIL WHERE WebId = %i AND LangId = %i AND Id = %i", $webId, $langId, $contentId)->fetchAll();
        return $res;
    }

    public function GetObjectHistoryList($contentId, $webId = 0, $langId = 0) {
        $res = dibi::query("SELECT * FROM OBJECTHISTORYVIEW WHERE WebId = %i AND LangId = %i AND  Id = %i ORDER BY Date DESC", $webId, $langId, $contentId)->fetchAll();
        return $res;
    }

    private function SavePosition($id, $position) {
        dibi::query("UPDATE Content SET Sort = %i WHERE Id = %i", $position, $id);
    }

    public function GetNoPublishItems($langId) {
        return dibi::query("SELECT * FROM CONTENTTREE WHERE IsActive = 0 AND LangId = %i", $langId)->fetchAll();
    }

    public function PublishItem($contentId, $langId) {
        $user = Users::GetInstance();
        if (!$this->HasPrivileges($contentId, PrivilegesType::$CanPublish))
            return false;
        $res = $this->SelectByCondition("ContentId = $contentId AND IsLast = 1 AND LangId = $langId");
        if (empty($res))
            return false;
        $id = $res[0]["Id"];
        $this->GetObjectById($id, true);
        $this->DeactiveAllVersion($contentId, $langId);
        $this->IsActive = true;
        $this->PublishUser = $user->GetUserId();
        $this->SaveObject();
        $xml = $this->Data;
        $xmld = simplexml_load_string($xml);
        foreach ($xmld as $key => $value)
        {
            $key = trim($key);
            $value = trim($value);
            /** @var  \Model\ContentData */
            $contentData = ContentData::GetInstance();
            $contentData->DeleteByCondition("ContentId = $contentId AND LangId = $langId", true,false);
            $contentData->ContentId = $contentId;
            $contentData->LangId = $langId;
            $contentData->Value = html_entity_decode($value);
            $contentData->ValueNoHtml = strip_tags(html_entity_decode($value));
            $contentData->ItemName = $key;
            $contentData->SaveObject();
                    
                }
        
        
        
        return true;
    }

    public function SendMailing($id, $groupId = 0, $webId = 0, $langId = 0, $emailid = 0, $mailingGroupId = 0, $from = "") {
        $mailingGroup = MailingContactsInGroups::GetInstance();
        $emails = $mailingGroup->GetMailsInMailingGroups($mailingGroupId);
        foreach ($emails as $row) {
            $to = $row["Email"];
            $mail = new Mail();
            $mail->SendEmail($from, $to, $emailid, array());
        }
    }

    public function GetNameObject($id, $langId) {
        if (empty($id))
            $id = 0;
        $res = $this->GetFirstRow($this->SelectByCondition("ContentId = $id AND LangId = $langId AND IsLast = 1","",array("Name")));
        return $res["Name"];
    }

    public function GetUserItemDomainId($id) {
        $res = $this->GetFirstRow(dibi::query("SELECT Template.DomainId
        FROM Content AS uitem
        JOIN Content AS Template ON uitem.TemplateId = Template.Id
        WHERE uitem.Id =%i", $id)->fetchAll());
        return $res["DomainId"];
    }

    public function XmlDownload($seourl) {
        $url = SERVER_NAME_LANG . "xml/" . $seourl . ".xml";
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($url) . "\"");
        readfile($url);
    }

    public function GenerateXmlItem($id, $langid, $usergroup, $webId) {
        $detail = $this->GetFirstRow($this->GetDataSourceDetail($id, $usergroup, $webId, $langid));
        $xmlstring = $detail["Data"];

        $xml = simplexml_load_string($xmlstring);
        $domain = trim($xml->Domain);
        $xmlUserItem = trim($xml->SelectedObject);

        if (trim($xml->DatasourceType) == "XmlExport") {
            if ($domain == 0)
                return "";
            $domainItem = \Model\UserDomainsItems::GetInstance();
            $items = $domainItem->GetUserDomainItemById($domain);
            $xml = "";
            foreach ($items as $row) {
                $xml .= "<" . $row["Identificator"] . ">{" . $row["Identificator"] . "}</" . $row["Identificator"] . ">";
            }
            return $xml;
        } else if (trim($xml->DatasourceType) == "XmlExportUserItem") {
            $users = Users::GetInstance();
            $langId = $this->GetLangIdByWebUrl();
            if (empty($xmlUserItem))
                return "";
            $data = $this->LoadFrontendFromId($xmlUserItem, $users->GetUserGroupId(), $langId);
            $detail = $this->GetUserItemDetail($xmlUserItem, $users->GetUserGroupId(), 0, $langId);

            $templateId = empty($detail[0]["ChildTemplateId"]) ? $detail[0]["TemplateId"] : $detail[0]["ChildTemplateId"];
            $domainItems = \Model\UserDomainsItems::GetInstance();
            $identificator = $domainItems->GetUserDomainByTemplateId($templateId);
            $items = $domainItems->GetUserDomainItems($identificator);
            $xml = "";
            $xml .= "<Name>{Name}</Name>";
            $xml .= "<SeoUrl>{SeoUrl}</SeoUrl>";
            foreach ($items as $row) {
                $xml .= "<" . $row["Identificator"] . ">{" . $row["Identificator"] . "}</" . $row["Identificator"] . ">";
            }
            return $xml;
        }
    }

    public function SetFiltr($langId, $parentId, $columnName, $filtrMode) {
        $out = array();
        $data = $this->GetDataForFiltr($langId, $parentId, $columnName);
        if ($filtrMode == FiltrModes::$MinMax) {
            $data = ArrayUtils::SortArray($data, "ValueNoHtml", SORT_ASC);
            $len = count($data) - 1;

            $out["MinValue"] = empty($data[0]["ValueNoHtml"]) ? 0 : $data[0]["ValueNoHtml"];
            $out["MaxValue"] = empty($data[$len]["ValueNoHtml"]) ? 0 : $data[$len]["ValueNoHtml"];
        }
        if ($filtrMode == FiltrModes::$DistinctValues) {
            $distinct = ArrayUtils::Distinct($data);
            foreach ($distinct as $row)
                $out[] = $row["ValueNoHtml"];
        }
        return $out;
    }

    private function GetDataForFiltr($langId, $parentId, $columnName) {
        $res = dibi::query("SELECT ContentData.* FROM ContentData 
                JOIN Content ON Content.ParentId = %i AND Content.Id = ContentData.ContentId AND ContentData.LangId = %i AND ContentData.ItemName =%s
                ", $parentId, $langId, $columnName)->fetchAll();
        foreach ($res as $row) {
            $child = $this->GetDataForFiltr($langId, $row["Id"], $columnName);
            if (!empty($child)) {
                $res = array_merge($res, $child);
            }
        }
        return $res;
    }

    

    public function CreateSurveyAnswer($lang, $parentid, $data) {

        $user = Users::GetInstance();
        $data = $this->PrepareXmlFromArray($data, "keyvalue");
        setcookie("surveyanswer" . $parentid, true, time() + (86400 * 30), "/");
        return $this->CreateContentItem("surveyanswer-" . StringUtils::GenerateRandomString(), true, "", 0, ContentTypes::$SurveyAnswer, false, $lang, $parentid, true, "", array(), $data, 0, 0, "", "", "", 0, 0, 0, 99999, 0, false);
    }

    public function GetSurveyStatistic($formId, $langId, $webId) {
        return dibi::query("SELECT  Data FROM SURVEYSTATISTIC WHERE ParentId= %i AND LangId =%i AND WebId =%i", $formId, $langId, $webId)->fetchAll();
    }

    public function GetUserItems($userId, $langId, $parentId) {
        return $this->GetTree($langId, $parentId, "", $userId);
        //return  dibi::query("SELECT * FROM CONTENTTREE WHERE Owner = %i AND LangId = %i AND (ContentType= 'UserItem' OR ContentType= 'Link' OR ContentType= 'ExternalLink' OR ContentType= 'JavascriptAction') AND Deleted = 0",$userId, $langId)->fetchAll();
    }

    public function CreateDiscusion($name, $privileges, $parentid, $langid) {
        return $this->CreateContentItem($name, true, "", 0, ContentTypes::$Discusion, true, $langid, $parentid, true, "", $privileges);
    }

    public function UpdateDiscusion($id, $name, $privileges) {
        return $this->UpdateContentItem($id, $name, true, "", 0, true, false, "", $privileges);
    }

    private function SetLastVistedObject($id) {
        dibi::query("UPDATE Content SET LastVisited = NOW() WHERE Id = %i", $id);
    }

    public function GetIdBySeoUrl($seoUrl, $webId = 0) {
        $res = array();
        if ($webId == 0) {
            $res = $this->SelectByCondition(" SeoUrl = '$seoUrl'  AND IsActive = 1   AND Deleted = 0 ","",array("ContentId"));
        } else {
            $res = $this->SelectByCondition("SeoUrl = '$seoUrl' AND IsActive = 1   AND Deleted = 0  AND WebId = $webId","",array("ContentId"));
        }
        if (empty($res))
            return 0;
        return $res[0]["ContentId"];
    }

    public function LoadFrontendFromSeoUrl($seourl, $usergroup, $langId, $webid, $limit = 0, $sort = "", $subitems = false, $ignoreActiveUrl = false, $acceptItems = "",$ignoreAlternativeItems =IGNORE_ALTERNATIVE_CONTENT,$where = "",$whereColumn = "") {
        $contentId = $this->GetIdBySeoUrl($seourl, $webid);
        return $this->LoadFrontend($contentId, $usergroup, $langId, $webid, $limit, $sort, $subitems, $ignoreActiveUrl,false, $acceptItems,"",$ignoreAlternativeItems,$where,$whereColumn);
    }

    public function HasTemplate($id, $templateIdentificator) {
        $res = dibi::query("SELECT UserItem.Id FROM Content AS UserItem "
                        . " LEFT JOIN Content AS Template ON  UserItem.TemplateId = Template.Id "
                        . "WHERE UserItem.Id = %i AND Template.Identificator = %s ", $id, $templateIdentificator)->fetchAll();
        if (empty($res))
            return false;
        return true;
    }
    
    public function GetSeoUrlByIdentificator($identificator,$langId)
    {
       $res = dibi::query("SELECT SeoUrl FROM ContentVersion  
               LEFT JOIN Content ON ContentVersion.ContentId =  Content.Id AND  ContentVersion.LangId =%i  AND ContentVersion.IsLast = 1  WHERE Content.Identificator = %s ",$langId,$identificator)->fetchAll();
       $lang = Langs::GetInstance();
       $lang->GetObjectById($langId,true);
       if (empty($res)) return "";
       $url = StringUtils::NormalizeUrl($lang->RootUrl).$res[0]["SeoUrl"];
       return StringUtils::NormalizeUrl($url);
    }
    
    // ke zruseni 
    public function GetAlternativeItems($contentId, $langId, $groupId = 0) {

        if ($groupId == 0) {
            $res = dibi::query("SELECT Content.Identificator, ContentAlternative.Id,ContentAlternative.ContentId,ContentAlternative.AlternativeContentId,ContentVersion.Name AS ItemName, UserGroups.GroupName,ContentVersion.SeoUrl 
                FROM ContentAlternative 
                JOIN ContentVersion ON ContentAlternative.AlternativeContentId = ContentVersion.ContentId AND ContentVersion.IsLast = 1 AND ContentVersion.Deleted = 0 
                AND ContentAlternative.ContentId = %i   AND  ContentVersion.LangId = %i
                JOIN UserGroups ON  ContentAlternative.UserGroupId = UserGroups.Id
                JOIN Content ON ContentAlternative.AlternativeContentId = Content.Id 
                 
            ", $contentId, $langId)->fetchAll();
        } else {
            $userGroup = UserGroups::GetInstance();
            $tmp = $userGroup->ChangeSystemGroupToAdmin();
            $groupId = $tmp == 0 ? $groupId : $tmp;
            $res = dibi::query("SELECT Content.Identificator,ContentAlternative.AlternativeContentId, ContentAlternative.Id,ContentAlternative.ContentId,ContentVersion.Name AS ItemName, UserGroups.GroupName,ContentVersion.SeoUrl FROM ContentAlternative 
                JOIN ContentVersion ON ContentAlternative.ContentId = %i AND ContentAlternative.AlternativeContentId = ContentVersion.ContentId AND ContentVersion.IsLast = 1 AND ContentVersion.Deleted = 0
                   AND  ContentVersion.LangId = %i  AND ContentAlternative.UserGroupId = %i
                JOIN UserGroups ON  ContentAlternative.UserGroupId = UserGroups.Id 
                JOIN Content ON ContentAlternative.AlternativeContentId = Content.Id 
                
            ", $contentId, $langId, $groupId)->fetchAll();
        }
        return $res;
    }
    
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }
    
    public function DeleteLangVersion($contentId, $langId)
    {
        \dibi::query("UPDATE ContentVersion SET Deleted = 1 WHERE ContentId = %i AND LangId = %i",$contentId, $langId);
    }
    
    
    public function GetValueFromContentData($itemId,$langId,$itemName,$noHtml= true)
    {
        
        $data = array();
        if ($noHtml)
        {
            $data =   dibi::query("SELECT ValueNoHtml AS Data FROM ContentData WHERE ContentId = %i AND LangId = %i AND ItemName = %s ", $itemId,$langId,$itemName)->fetchAll();
            
        }
        else 
        {
            $data =   dibi::query("SELECT Value AS Data FROM ContentData WHERE ContentId = %i AND LangId = %i AND ItemName = %s ", $itemId,$langId,$itemName)->fetchAll();
        }
        if (empty($data))
            return null;
        return $data[0]["Data"];
            }
    public function UpdateValue($contentId,$key,$value)
    {
        $lang = Langs::GetInstance();
        $langList = $lang->Select();
        foreach ($langList as $row)
         {
            $exist = $this->ItemExistsInLang($contentId,$row["Id"]);
            
            if($exist)
            {
                $res = $this->SelectByCondition("ContentId = $contentId AND IsLast = 1 AND LangId = ".$row["Id"],"","Data");
                $xml =$res[0]["Data"];
                $dataAr = ArrayUtils::XmlToArray($xml,"SimpleXMLElement",LIBXML_NOCDATA);
                $saveXml = "<items>";
                \Dibi::query("UPDATE ContentData SET Value = %s, ValueNoHtml =%s WHERE ContentId = %i AND LangId =%i AND ItemName = %s ",$value, strip_tags(html_entity_decode($value)),$contentId, $row["Id"],$key);
                $dataAr[$key] = $value;
                
                foreach ($dataAr as $keyx => $valuex)
                {
                    $saveXml .= "<$keyx><![CDATA[".$valuex."]]></$keyx>";
                }
                $saveXml .= "</items>";
                \Dibi::query("UPDATE ContentVersion SET Data = %s WHERE ContentId = %i AND LangId =%i AND IsLast = 1",$saveXml, $contentId, $row["Id"]);
                \Dibi::query("UPDATE ContentVersion SET Data = %s WHERE ContentId = %i AND LangId =%i AND IsActive = 1",$saveXml, $contentId, $row["Id"]);
            }
            
        }
        $this->UpdateMaterializedView("FrontendDetail");
        
    }
            
           

}
