<?php
namespace Components;
class Article extends UserComponents{
    
    
    public function __construct() {
        
        $this->Type = "Article";
        $this->LoadHtml = true;
        $this->LoadSubitems = false;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $html = "";
        $this->LoadSubitems = false;
        $data = $this->GetDataSource();
            
        if (empty($data))
            $html = "";
        else
            $html = $this->PrepareHtml($this->RenderHtml, $data);
        
        return $html;
    }
    
    
}
