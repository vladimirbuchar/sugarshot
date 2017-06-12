<?php
namespace Components;
use Utils\ArrayUtils;
use HtmlComponents\Ul;
use HtmlComponents\Li;
use HtmlComponents\Link;
use Types\ContentTypes;
class Menu extends UserComponents{
    
    public $MainParent ="mainmenu";
    public $AcceptUserTeplates = "";
    
    
    
    public function __construct() {
        
        $this->Type = "Menu";
        $this->LoadSubitems = true;
//        $this->InsertJavascriptToContent = true;
        
        parent::__construct();
    }     
    public function CreateMenuHtml($data,$level = 1,$parentId ="",$active=false)
    {
        
        
        
        $menuId =  empty($this->Id)? \Utils\StringUtils::GenerateRandomString(5) : $this->Id;
        $ul = new Ul();
        $ul->Id = $menuId;
        if ($level == 1)
            $ul->CssClass = "nav menuList";
        else 
        {
            $ul->CssClass="nav collapse level".$level;
            $ul->Id ="submenu1".$level;
            $ul->Role = "menu";
            $ul->Arialabelledby=$parentId;   
        }
        $lang = "";
        if (!empty($_GET["lang"]))
            $lang = $_GET["lang"]."/";
        
        
        foreach ($data as $row)
        {
            $li = new Li();
            $link = new Link();
            $link->AddAtrribut("data-ajax", "false");
            if ($row["ContentType"] == ContentTypes::$Link) {
                $xml = $row["Data"];
                $ar = ArrayUtils::XmlToArray($xml);
                $contentLink =  \Model\ContentVersion::GetInstance();
                if ($ar["item"]["LinkType"] == LinkType::$Document || $ar["item"]["LinkType"] == LinkType::$Form)
                {
                    $link->Href = "/".$lang.$row["SeoUrl"];
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
                                $link->Href = "/".trim($value);
                            }
                        }
                    }
                }
            }
            else if( $row["ContentType"] == ContentTypes::$ExternalLink)
            {
                $link->Href = "";
            }
            else {
                $link->Href = "/".$lang.$row["SeoUrl"];
            }
            $link->Href = $link->Href."/";
            $link->Html = $row["Name"];
            $link->Title = $row["Name"];
            
            $isActive = "/".$lang.$_GET["seourl"]."/" == $link->Href;
            if (!$isActive && !self::$SessionManager->IsEmpty("LastActiveUrl"))
            {
                $isActive = self::$SessionManager->GetSessionValue("LastActiveUrl") == $link->Href;
            }
            if ($isActive) 
            {
                self::$SessionManager->SetSessionValue("LastActiveUrl",  $link->Href);
                $li->CssClass .= " active";
            }
            $li->SetChild($link);
            $link->Id= "menubtn-".$row["Id"];
            if (!empty($row["Child"]))
            {
                $levelTmp = $level+1;
                
                $link->DataToggle = "collapse";
                $link->DataTarget = "#submenu".$levelTmp;
                $link->Arialabel = "false";
                
                $li->SetChild($this->CreateMenuHtml($row["Child"],$levelTmp,"menubtn-".$row["Id"],$isActive));
            }
            $ul->SetChild($li);
        }
        
        return $ul;
        
    }
    public function GetComponentHtml(){
        $content =  \Model\ContentVersion::GetInstance();
        $contentId = $content->GetIdByIdentificator($this->MainParent,$this->WebId);
        //$data = $content->LoadFrontend($contentId, self::$UserGroupId, $this->LangId, $this->WebId, 0, "", true, FALSE, FALSE, $this->AcceptUserTeplates, "",true);
        $data = $this->GetDataSource($contentId);
        

                    
        return $this->CreateMenuHtml($data)->RenderHtml();
    }
    
}
