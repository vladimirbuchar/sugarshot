<?php
namespace Components;
class AdminSearch extends UserComponents implements \Inteface\iComponent{
    
    public $ContentType = "";
    public function __construct() {
        
        $this->Type = "AdminSearch";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $this->SetReplaceString ("ContentType", $this->ContentType);
    }
    
    
}
