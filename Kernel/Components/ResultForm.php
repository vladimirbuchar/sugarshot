<?php
namespace Components;
class ResultForm extends UserComponents{
    
    
    public function __construct() {
        
        $this->Type = "ResultForm";
        $this->CacheBySeoUrl = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        $content = \Model\ContentVersion::GetInstance();
        $id = $content->GetIdByIdentificator($this->DataSource);
        $form = new \Kernel\Forms(); 
        //return $form->GenerateFrontEndForm($id, 0, "", true);
    }
    
    
}
