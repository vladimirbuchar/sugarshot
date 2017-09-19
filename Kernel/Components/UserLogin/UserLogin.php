<?php
namespace Components;
class UserLogin extends UserComponents implements \Inteface\iComponent{
    
    public function __construct() {
        
        $this->Type = "UserLogin";
        $this->LoadHtml = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $this->SetUsedWords("word745");
        $this->SetUsedWords("word746");
        $this->SetUsedWords("word747");
    }
    
    
}
