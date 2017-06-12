<?php
namespace Components;
class ItemForm extends UserComponents{
    
    public $ElementId = "";
    public $DomainIdentificator = "";
    public $Result = false;
    public function __construct() {
         
        $this->Type = "ItemForm";
        $this->CacheBySeoUrl = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        $form = new \Kernel\Forms(); 
        $form->ShowElementId = $this->ElementId;
        $form->ShowLabel = false; 
        return $form->GetUserDomain($this->DomainIdentificator,  0, true,  "",  false,  "","", 0,$this->Result);
    }
    
    
}
