<?php
namespace Components;
class AdminCopyDialog extends UserComponents implements \Inteface\iComponent{
    
    public $ContentType = "";
    public function __construct() {
        
        $this->Type = "AdminCopyDialog";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $this->SetReplaceString ("ContentType", $this->ContentType);
        $this->SetUsedWords("word460");
        $this->SetUsedWords("word461");
        $this->SetUsedWords("word462");
        $this->SetUsedWords("word459");    }
    
    
}
