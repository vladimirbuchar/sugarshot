<?php
namespace Components;
class HtmlEditor extends UserComponents{
    public $Html;
    public $HtmlEditorId = "";
    public $ShowEditHtmlCode = false;
    
    public function __construct() {
        
        $this->IgnoreCache = true;
        $this->LoadHtml = true;
        $this->Type ="HtmlEditor";
        $this->AutoReplaceString = true;
        parent::__construct();
    }
    
    public function GetComponentHtml() {
        $this->SetReplaceString("AdminLang", strtolower($this->GetLang()));
        $this->SetReplaceString("HtmlEditorId", $this->HtmlEditorId."".\Utils\StringUtils::GenerateRandomString(5));
        $this->SetReplaceString("Html", $this->Html);
        $this->SetReplaceString("ShowEditHtmlCode", $this->ShowEditHtmlCode ? "code":"");
        
    }
}
