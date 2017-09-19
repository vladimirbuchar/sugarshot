<?php
namespace Components;
class UserRegister extends UserComponents implements \Inteface\iComponent{
    
    public function __construct() {
        
        $this->Type = "UserRegister";
        $this->LoadHtml = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $this->SetUsedWords("word745");
        $this->SetUsedWords("word746");
        $this->SetUsedWords("word748");
        $this->SetUsedWords("word749");
        $this->SetUsedWords("word750");
        $this->SetUsedWords("word751");
        $this->SetUsedWords("word752");
        $this->SetReplaceString("DomainHtml", $this->GetUserDomain("UserProfile"));       
    }
    
    
}
