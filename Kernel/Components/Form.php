<?php
namespace Components;
class Form extends UserComponents{
    
    
    public function __construct() {
        
        $this->Type = "Form";
        $this->CacheBySeoUrl = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        $content =  new \Objects\Content();
        $id = 0;
        //$content->GetFormIdBySeoUrl($seourl, $langid, $webid)
        if (!empty($this->DataSource))
            $id = $content->GetIdByIdentificator($this->DataSource);
        else 
            $id = $content->GetFormIdBySeoUrl($_GET["seourl"], $this->LangId, $this->WebId);
        if ($id == 0)
            $this->IsEmptyComponent  =true;
        $form = new \Utils\Forms(); 
        return $form->GenerateFrontEndForm($id,0);
    }
    
    
}
