<?php
namespace Components;
class ResultForm extends UserComponents implements \Inteface\iComponent{
    
    
    public function __construct() {
        
        $this->Type = "ResultForm";
        $this->CacheBySeoUrl = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        $content = new \Objects\Content();
        $id = $content->GetIdByIdentificator($this->DataSource);
        $form = new \Utils\Forms(); 
        //return $form->GenerateFrontEndForm($id, 0, "", true);
    }
    
    
}
