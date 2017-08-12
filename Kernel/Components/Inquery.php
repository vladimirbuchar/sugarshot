<?php
namespace Components;
use Model\ContentVersion;
use Utils\Forms;
class Inquery extends UserComponents{
    
    public function __construct() {
        
        $this->Type = "Inquery";
        $this->CacheBySeoUrl = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        $content =  ContentVersion::GetInstance();
        $id = $content->GetSurveyId($_GET["seourl"], $this->LangId, $this->WebId);
        $form = new Forms();
        return $form->GenerateInqueryForm($id);
    }
    
    
}
