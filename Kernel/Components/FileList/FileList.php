<?php
namespace Components;
class FileList extends UserComponents implements \Inteface\iComponent{
     
    public $FileType;
    public function __construct() {
        
        $this->Type = "FileList";
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
        $id = $contentConnection->GetIdGalleryBySeoUrl($_GET["seourl"], $this->LangId, $this->WebId);
        $data =  $contentConnection->GetRelatedObject($id, $this->LangId,$this->FileType);
        return $data;
    }
    
    public function GetComponentHtml()
    {
        $data = $this->PrepareDatasource();
        if (empty($data))
            $this->IsEmptyComponent = true;
        
        $this->ReplaceItems($data);
    }
    
    public function ImageGalleryGetComponentHtml()
    {
        
        /*$this->AddMoreScript("/Scripts/ExternalApi/slider/jquery.blueimp-gallery.min.js");
        $this->AddMoreScript("/Scripts/Components/FileListImageGallery.js");
        $this->AddMoreCss("/Styles/blueimp-gallery.min.css");*/
        
    }
}
