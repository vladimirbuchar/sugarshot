<?php
namespace Components;
class DeleteDialog extends UserComponents implements \Inteface\iComponent{
    
    public $ContentType = "";
    public function __construct() {
        
        $this->Type = "DeleteDialog";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $this->SetReplaceString ("ContentType", $this->ContentType);
    }
    
    
}
