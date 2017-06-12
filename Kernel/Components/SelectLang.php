<?php
namespace Components;
class SelectLang extends UserComponents{
    
    public function __construct() {
        
        $this->Type = "SelectLang";
        $this->LinkJavascript= true;
        $this->LoadHtml = true;
        $this->UseItemTemplate = true;
        parent::__construct();
        
    }     
    
    public function GetComponentHtml()
    {
        $lang = \Model\Langs::GetInstance();
        $langList = $lang->GetLangListByWeb($this->WebId);
        \Utils\ArrayUtils::AddColumn($langList,"Protocol",SERVER_PROTOCOL);
        $this->ReplaceItems($langList);
    }
}
