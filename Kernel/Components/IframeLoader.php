<?php
namespace Components;
class IframeLoader extends UserComponents{

    public $ExternalUrl;
    public $ReplaceUrl="";
    public $NewUrl="";
    public $StyleUrl="";
    public $AllowTransparency;
    public $Frameborder;    
    public $Scrolling;
    public $Style;  
    
    public function __construct() {
        $this->IgnoreCache = true;
        parent::__construct();
        
    } 
    public function GetComponentHtml() {
        
        $html = file_get_contents($this->ExternalUrl);
        if (!empty($this->ReplaceUrl) || !empty($this->NewUrl))
        {
            $html = preg_replace("/(?<=<a href=(\"|'))[^\"']+(?=(\"|'))/",$this->NewUrl,$html);
        }
        if (!empty($this->StyleUrl))
        {
            $html = str_replace('<link rel="stylesheet" href="', '<link rel="stylesheet" href="'.$this->StyleUrl, $html);
        }
        if (!empty($this->StyleUrl))
        {
            
            $html = str_replace('<script src="/', '<script src="'.$this->StyleUrl, $html);
        }
        
        $key = \Utils\StringUtils::GenerateRandomString();
        $_SESSION["iframe_$key"]= $html;
        $iframe = new \HtmlComponents\Iframe();
        $iframe->Src = SERVER_NAME_LANG."iframe/$key/";
        $iframe->CssClass = $this->CssClass;
        $iframe->frameborder = $this->Frameborder;
        $iframe->AddAtrribut("AllowTransparency", $this->AllowTransparency);
        $iframe->AddAtrribut("scrolling", $this->Scrolling);
        $iframe->AddAtrribut("style", $this->Style);
        return $iframe->RenderHtml();
        
        
    }
}
