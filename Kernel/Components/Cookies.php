<?php
namespace Components;
class Cookies extends UserComponents{
    
    public $MoreInfoLink = "#";
    public function __construct() {
        
        $this->Type = "Cookies";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        if (!empty($_COOKIE["cookiesAccept"]))
        {
            $this->Visible= false;
        }
        parent::__construct();
        
    }     
    
    public function GetComponentHtml()
    {
        
        $this->SetUsedWords("word761");
        $this->SetUsedWords("word762");
        $this->SetUsedWords("word763");
        $this->SetReplaceString("externalLink", $this->MoreInfoLink);
        if (empty($this->MoreInfoLink)|| $this->MoreInfoLink =="#")
            $this->SetReplaceString ("HideMoreLink", "dn");
        $this->AddMoreScript("/Scripts/ExternalApi/jquery.cookie.js");
        
    }
    
    
}
