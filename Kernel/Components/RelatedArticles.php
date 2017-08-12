<?php
namespace Components;
class RelatedArticles extends UserComponents{
    
    public function __construct() {
        
        $this->Type = "RelatedArticles";
        $this->CacheBySeoUrl = true;
        $this->LoadHtml = true;
        $this->UseDataSource = true;
        $this->UseItemTemplate = true;
        $this->AutoReplaceString = true;
        parent::__construct();
    }     
    public function PrepareDatasource()
    {
        $contentConnection = new \Objects\Content();
        $content =  \Model\ContentVersion::GetInstance();
        $id = $content->GetIdGalleryBySeoUrl($_GET["seourl"], $this->LangId, $this->WebId);
        return $contentConnection->GetRelatedObject($id, $this->LangId,"document");
    }
    public function GetComponentHtml()
    {
        $data = $this->PrepareDatasource();     
        if (empty($data))
            $this->IsEmptyComponent = true;
        $this->ReplaceItems($data);
        
    }
    
    
}
