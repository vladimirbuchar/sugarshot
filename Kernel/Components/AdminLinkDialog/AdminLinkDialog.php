<?php
namespace Components;
class AdminLinkDialog extends UserComponents implements \Inteface\iComponent{
    
    public $ContentType = "";
    public function __construct() {
        
        $this->Type = "AdminLinkDialog";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $this->SetReplaceString ("ContentType", $this->ContentType);
        $this->SetUsedWords("word463");
        $this->SetUsedWords("word464");
    }
    
    
}
