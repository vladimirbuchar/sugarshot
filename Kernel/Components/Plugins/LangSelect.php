<?php
namespace Components;
class LangSelect extends UserComponents{
    
    public function __construct() {
        $this->Type = "LangSelect";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $lang = \Model\Langs::GetInstance();
        $data = $lang->GetLangListByWeb($this->WebId);
        
    }
    
    
}
