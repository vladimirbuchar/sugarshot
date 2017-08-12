<?php
namespace Components;

use Utils\Forms;
class RelatedForm extends UserComponents{
    
    public function __construct() {
        $this->Type = "RelatedForm";
        $this->CacheBySeoUrl = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        $content =  new \Objects\Content();
        $id = $content->GetFormIdBySeoUrl($_GET["seourl"], $this->LangId, $this->WebId);
        
        $form = new Forms();
        return $form->GenerateFrontEndForm($id);
    }
    
    
}
