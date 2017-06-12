<?php
namespace Components;
class AdminDeleteDialog extends UserComponents{
    
    public $ContentType = "";
    public function __construct() {
        
        $this->Type = "AdminDeleteDialog";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $this->SetReplaceString ("ContentType", $this->ContentType);
        $this->SetUsedWords("word456");
        $this->SetUsedWords("word457");
        $this->SetUsedWords("word458");
        $this->SetUsedWords("word459");
    }
    
    
}
