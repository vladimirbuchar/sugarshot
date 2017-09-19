<?php
namespace Components;
class VariantList extends UserComponents implements \Inteface\iComponent{
    
    public $DivId = "";
    public $LoadUrl ="";
    public $AcceptUserTeplates ="";
    
    public function __construct() {
        $this->LinkJavascript = true;
        $this->InsertJavascriptToContent = true;
        $this->Type = "VariantList";
        $this->LoadHtml = true;
        $this->UseItemTemplate = true;    
        $this->AutoReplaceString =true;
        parent::__construct();   
    }     
    
    public function GetComponentHtml()
    {
        $content =  new \Objects\Content();
        if (!empty($_GET["seourl"])) 
            $this->LoadUrl = $_GET["seourl"];
        $childs = $content->LoadFrontendFromSeoUrl($this->LoadUrl, self::$UserGroupId, $this->LangId, $this->WebId,0,"",true,true);
        if(empty($childs))
        {
            
            $contentId = $content->GetIdBySeoUrl($this->LoadUrl,$this->WebId);
            $parentId = $content->GetParent($contentId);
            if ($content->HasTemplate($parentId, $this->AcceptUserTeplates) || empty($this->AcceptUserTeplates))
            {
                $childs = $content->LoadFrontend($parentId, self::$UserGroupId, $this->LangId, $this->WebId, 0, "", false,false,true,"",$contentId);
            }
        }
        if (empty($childs))
            $this->IsEmptyComponent = true;
        $this->ReplaceItems($childs);        
    }
}
