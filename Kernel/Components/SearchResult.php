<?php
namespace Components;
class SearchResult extends UserComponents{
    
    public function __construct() {
        
        $this->Type = "SearchResult";
        $this->LoadHtml = true;
        $this->UseItemTemplate = true;
        $this->AutoReplaceString = true;
        parent::__construct();
    }     
    public function PrepareDatasource()
    {
        $content =  \Model\ContentVersion::GetInstance();
        $searchString = base64_decode($_GET["param1"]);
        
        $this->SetReplaceString("searchWord", $searchString);
        $outSearch = $content->Search(self::$UserGroupId,$this->LangId, $searchString);
        
        return $outSearch;
    }
    public function GetComponentHtml()
    {
        $data = $this->PrepareDatasource();
        $this->ReplaceItems($data);
        
    }
    
    
}
