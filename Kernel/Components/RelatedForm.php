<?php
namespace Components;
use Model\ContentVersion;
use Utils\Forms;
class RelatedForm extends UserComponents{
    
    public function __construct() {
        $this->Type = "RelatedForm";
        $this->CacheBySeoUrl = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        $content =  ContentVersion::GetInstance();
        $id = $content->GetFormIdBySeoUrl($_GET["seourl"], $this->LangId, $this->WebId);
        
        $form = new Forms();
        return $form->GenerateFrontEndForm($id);
    }
    
    
}
