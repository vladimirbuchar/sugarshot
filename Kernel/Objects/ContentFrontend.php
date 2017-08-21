<?php

namespace Objects;
use Dibi;   
use Types\ContentTypes;
use Utils\StringUtils;
use Utils\ArrayUtils;

class ContentFrontend extends Content {

    public $Id = 0;
    public $SeoUrl = "";
    public $DataSource = "";
    public $CheckAlternativeContent = ENABLE_ALTERNATIVE_CONTENT;
    public $Limit = 0;
    public $Sort = "";
    public $LoadSubItems = false;
    public $IgnoreActiveUrl = "";
    public $AddParent = false;
    public $AcceptItems = "";
    public $IgnoredId = "";
    public $WebId = 0;
    public $LangId = 0;
    public $UserGroupId =0;
    public $LimitLevelLoad =0;
    public $Where = "";
    public $WhereColumns = "";
    private $_mode = "";
    private $_loadLevel = 0;
    public $LoadFirstLevel = true;
    
    public function __construct() {
        parent::__construct();
    }

    public function LoadContent($id = 0,$loadLevel = 0) {
        $tmpLevel = $loadLevel;
        $res = array();
        if ($id > 0)
            $this->Id = $id;
        if ($this->Id > 0) {
            $this->_mode = "Id";
        } else if (!empty($this->SeoUrl)) {
            $this->_mode = "SeoUrl";
        } else if (!empty($this->DataSource)) {
            $this->_mode = "Datasource";
        }
        
        if ($this->CheckAlternativeContent) {
        }
        $activePage = "";
        $columns = "";
        $columnsWhere = "";
        $sort = "";
        $limit = $this->Limit == 0 ? "" : "LIMIT 0,$this->Limit";

        if (!empty($this->Sort)) {
            
            $this->Sort = trim($this->Sort);
            if (StringUtils::StartWidth($this->Sort, "##")) {
                $this->Sort = StringUtils::RemoveString($this->Sort, "##");
                $sortType = StringUtils::GetLastWord($this->Sort);
                $column = trim(StringUtils::RemoveLastWord($this->Sort));
                $columns .= ", GROUP_CONCAT(if(ItemName = '$column', value, NULL)) AS '$column'";
                $sort = empty($this->Sort) ? "" : "ORDER BY `" . $column . "` " . $sortType;
            } else {
                $sort = "ORDER BY $this->Sort";
            }
        }
      
        
        if (!empty($columns) ) {
            $columns = "LEFT JOIN (           
                            SELECT
                                ContentData.ContentId
                                $columns
                                    
                                    
                                FROM ContentData
                                GROUP BY ContentId
                               ) AS ContentTable
                            ON child.Id = ContentTable.ContentId";
        }
        $ignoreQuery = "";
        $acceptQuery = "";
        $acceptTable = "";
        if ($this->IgnoreActiveUrl) {
            if ($this->_mode =="Id")
                $ignoreQuery .= " AND child.Id <> $this->Id ";
            else if ($this->_mode == "Datasource")
                $ignoreQuery .= " AND child.Identificator <> '$this->DataSource' ";
            else if ($this->_mode == "SeoUrl")
                $ignoreQuery .= " AND child.SeoUrl <> '$this->SeoUrl' ";
        }
        if (!empty($this->IgnoredId)) {
            $ar = explode(",", $this->IgnoredId);
            for ($y = 0; $y < count($ar); $y++) {
                $ignoreQuery .= " AND child.Id <> $ar[$y] ";
            }
        }

        if (!empty($this->AcceptItems)) {
            $ar = explode(",", $this->AcceptItems);
            for ($y = 0; $y < count($ar); $y++) {

                if (empty($acceptQuery))
                    $acceptQuery .= "  FRONTENDTEMPLATES.Identificator  =  '$ar[$y]' ";
                else
                    $acceptQuery .= " OR  FRONTENDTEMPLATES.Identificator  =  '$ar[$y]' ";
            }
            $acceptTable = "  WHERE ($acceptQuery) ";
        }
        
        
        if ($this->LoadSubItems)
        {
            
            $parentQuery = "";
            if ($this->_mode =="Id")
                $parentQuery .= "  parents.Id =  $this->Id ";
            else if ($this->_mode == "Datasource")
                $parentQuery .= "  parents.Identificator =  '$this->DataSource' ";
            else if ($this->_mode == "SeoUrl")
                $parentQuery .= "  parents.SeoUrl =  '$this->SeoUrl' ";
            
            if (empty($acceptTable))
            {
                $res = dibi::query("SELECT DISTINCT child.Date,child.Sort,child.TemplateId,child.Id,child.Name,child.SeoUrl,child.Data,child.Header,parents.Identificator AS parentIdentificator FROM `FrontendDetail_materialized` AS parents "
                        . " INNER JOIN FrontendDetail_materialized AS child ON parents.Id = child.ParentId AND $parentQuery AND (child.GroupId =%i OR (child.ContentType='link' ))  AND child.LangId = %i $ignoreQuery $columns  "
                        . " $acceptTable  $sort $limit", $this->UserGroupId, $this->LangId)->fetchAll();
            }
            else 
            {
                $res = dibi::query("SELECT DISTINCT child.Date,child.Sort,child.TemplateId,child.Id,child.Name,child.SeoUrl,child.Data,child.Header	,parents.Identificator AS parentIdentificator FROM `FrontendDetail_materialized` AS parents "
                        . " INNER JOIN FrontendDetail_materialized AS child ON parents.Id = child.ParentId AND $parentQuery AND (child.GroupId =%i OR (child.ContentType='link' ))  AND child.LangId = %i $ignoreQuery $columns  "
                        . "LEFT JOIN  FRONTENDTEMPLATES ON child.TemplateId =  FRONTENDTEMPLATES.Id  AND FRONTENDTEMPLATES.LangId = %i AND FRONTENDTEMPLATES.GroupId = %i  "
                        . " $acceptTable  $sort $limit", $this->UserGroupId, $this->LangId, $this->LangId, $this->UserGroupId)->fetchAll();
            }
            
        }

        if (empty($res) && !$this->LoadSubItems && !$this->IgnoreActiveUrl) {
            $parentQuery = "";
            if ($this->_mode =="Id")
                $parentQuery .= "  Id =  $this->Id ";
            else if ($this->_mode == "Datasource")
                $parentQuery .= "  Identificator = '$this->DataSource' ";
            else if ($this->_mode == "SeoUrl")
                $parentQuery .= "  SeoUrl =  '$this->SeoUrl' ";
            
            
                
            $res = dibi::query("SELECT Date,Sort,TemplateId,Id, GroupId,WebId,LangId,Name,SeoUrl,Data,Header FROM FrontendDetail_materialized WHERE  $parentQuery  AND LangId = %i AND  (GroupId =%i OR (ContentType='link' )) ",  $this->LangId,$this->UserGroupId)->fetchAll();
            return $res;
        }
        if ($this->AddParent) {
             $parentQuery = "";
            if ($this->_mode =="Id")
                $parentQuery .= "  Id =  $this->Id ";
            else if ($this->_mode == "Datasource")
                $parentQuery .= "  Identificator = '$this->DataSource' ";
            else if ($this->_mode == "SeoUrl")
                $parentQuery .= "  SeoUrl =  '$this->SeoUrl' ";
            $resParent = dibi::query("SELECT Date,Sort,TemplateId,Id, GroupId,WebId,LangId,Name,SeoUrl,Data,Header FROM FrontendDetail_materialized WHERE $parentQuery   AND LangId = %i AND (GroupId =%i OR (ContentType='link' ))", $this->LangId,$this->UserGroupId)->fetchAll();
            $res = array_merge($resParent, $res);
        }
        $tmpLevel++;
        if (!$this->LoadFirstLevel)
        {
            if ($this->LoadSubItems && $this->LimitLevelLoad > 0 && ($tmpLevel > $this->LimitLevelLoad)){
                if (!empty($res)) {
                foreach ($res as $row) {
                    $childs = $this->LoadContent($row["Id"],$tmpLevel);
                    if (!empty($childs))
                        $row["Child"] = $childs;
                    }
                }
            }
        }
        return $res;
    }
    
    
    public function PrepareHtml($template, $data)
    {
        
        
        $html = "";
        
        array_walk($data, function(&$row,$key) use(&$html,&$template) { 
            $rowPrepare = $row;
            if (!empty($row["ContentType"]))
            {
                if ($row["ContentType"] == ContentTypes::LINK) {
                    $xml = $row["Data"];
                    $ar = ArrayUtils::XmlToArray($xml);
                    $contentLink = new \Objects\Content();
                    if ($ar["item"]["LinkType"] == LinkType::$Document)
                    {
                        $linkInfo = $contentLink->GetUserItemDetail($ar["item"]["ObjectId"], self::$UserGroupId, $this->WebId, $this->LangId,0,true);
                        if (!empty($linkInfo)) {
                            $rowPrepare = $linkInfo[0];
                        }
                        
                    }
                    else if ($ar["item"]["LinkType"] == LinkType::$Form)
                    {
                        $linkInfo = $contentLink->GetFormDetail($ar["item"]["ObjectId"], self::$UserGroupId, $this->WebId, $this->LangId);
                        if (!empty($linkInfo)) {
                            $rowPrepare = $linkInfo[0];
                        }
                        
                    }
                    else if ($ar["item"]["LinkType"] == LinkType::$Repository)
                    {
                        $linkInfo = $contentLink->GetFileFolderDetail($ar["item"]["ObjectId"], self::$UserGroupId, $this->WebId, $this->LangId);
                        if (!empty($linkInfo)) {
                            $rowPrepare = $linkInfo[0];
                            $xmlRepositoryData = trim($linkInfo[0]["Data"]);
                            $xmlRepository = simplexml_load_string($xmlRepositoryData);
                            foreach ($xmlRepository as $key => $value)
                            {
                                if(trim($key) == "FileUpload")
                                {
                                    $rowPrepare["SeoUrl"] = trim($value);
                                    break;
                                }
                            }
                        }
                        
                    }
                }
                else if( $row["ContentType"] == ContentTypes::EXTERNAL_LINK)
                {
                    $row["SeoUrl"] = "#externalUrl#".$row["SeoUrl"];       
                }
                else if( $row["ContentType"] == ContentTypes::JAVASCRIPT_ACTION)
                {   
                    $row["SeoUrl"] ="#jsaction#".$row["SeoUrl"];
                }
            }

            $tmp = "";
            $xmlData = "";
            if (!empty($rowPrepare["Data"]))
                $xmlData = $rowPrepare["Data"];
            $find = array();
            $replace = array();
            foreach ($rowPrepare as $key => $value) {
                
                if ($key == "SeoUrl" )
                {
                    if (StringUtils::StartWidth($value, "res/"))
                    {
                        $value = "/" . $value;
                    }
                    else if (StringUtils::StartWidth($value, "#externalUrl#"))
                    {
                        $value = str_replace("#externalUrl#","", $value);
                        if (!StringUtils::StartWidth($value, SERVER_PROTOCOL) )
                        {
                            $value = SERVER_PROTOCOL.$value;
                            
                        }
                    }
                    else if (StringUtils::StartWidth($value, "#jsaction#"))
                    {
                        $value = "javascript:".str_replace("#jsaction#","", $value)."";
                        
                    }
                    else
                    {
                        $lang = empty($_GET["lang"]) ? "": $_GET["lang"]."/";
                        $value = "/$lang" . $value . "/";
                    }
                }
                $find[] = "/{" . $key . "}/";
                $replace[] = $value;
            }
            
            
                
                
            if (!empty($xmlData)) {
                $xml = simplexml_load_string($xmlData);
                if (!empty($xml)) {
                    foreach ($xml as $key => $value) {
                        $find[] = "/{" . trim($key) . "}/";
                        $replace[] = trim($value);
                    }
                }
            }
            
            $tmp = preg_replace($find, $replace, $template);
            $xml = null;
            $html .= $tmp;
            if (!empty($row["Child"]))
            {
                
                $html .= $this->PrepareHtml($template, $row["Child"]);
            }  
        },"");
        return $html;
    
        
    }


    private function GetAlternativeItems() {

        $userGroup = new \Objects\Users();
        $tmp = $userGroup->ChangeSystemGroupToAdmin();
        $groupId = $tmp == 0 ? $groupId : $tmp;
        $res = dibi::query("
            SELECT Content.Identificator,ContentAlternative.AlternativeContentId, ContentAlternative.Id,ContentAlternative.ContentId,ContentVersion.Name AS ItemName, UserGroups.GroupName,ContentVersion.SeoUrl FROM ContentAlternative 
                JOIN ContentVersion ON ContentAlternative.ContentId = %i AND ContentAlternative.AlternativeContentId = ContentVersion.ContentId AND ContentVersion.IsLast = 1 AND ContentVersion.Deleted = 0 AND  ContentVersion.LangId = %i  AND ContentAlternative.UserGroupId = %i
                JOIN UserGroups ON  ContentAlternative.UserGroupId = UserGroups.Id 
                JOIN Content ON ContentAlternative.AlternativeContentId = Content.Id 
                
            ", $contentId, $langId, $groupId)->fetchAll();

        return $res;
    }

}
