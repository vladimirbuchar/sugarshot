<?php
namespace Components;
use Utils\StringUtils;
use HtmlComponents\Link;

class xLink extends UserComponents implements \Inteface\iComponent{

    public $Controller;
    public $View;
    public $Prefix;
    public $Text;
    public $Param1;
    public $Param2;
    public $ObjectId = 0;
    
    public $OnClick;
    public $DataTarget;
    public $DataToggle;
    public function __construct() {
        $this->IgnoreCache = true;
        parent::__construct();
        
    }
    public function GetComponentHtml() {
        $class = "Controller\\".$this->Controller;
        $control = new $class();
        $control->LinkTestPrivileges = true;
        if (!StringUtils::StartWidth($this->Prefix, "/") && !empty($this->Prefix))
        {
            $this->Prefix = "/".$this->Prefix;
        }
        $permition = $control->GetControllerPermition() && $control->GetViewPermition($this->View,$this->Controller);
        
        if ($permition)
        {
            $mustBeWebId = $control->MustBeWebId($this->View);
            $mustBeLangId = $control->MustBeLangId($this->View);
            if ((($mustBeWebId && !empty($this->WebId))|| (!$mustBeWebId)) && (($mustBeLangId && !empty($this->LangId))|| (!$mustBeLangId)))
            {
                
                $aHref= new Link();
                if (!empty($this->CssClass))
                    $aHref->CssClass = $this->CssClass;
                if (!empty($this->Id))
                    $aHref->Id = $this->Id;
                $aHref->Type="href";
                $aHref->Html = $this->GetWord($this->Text);
                if (!empty($this->DataTarget))
                {
                    $aHref->DataTarget = $this->DataTarget;
                }
                
                if (!empty($this->DataToggle))
                {
                    $aHref->DataToggle = $this->DataToggle;
                }
                    
                if (!empty($this->OnClick))
                {
                    $aHref->OnClick = $this->OnClick;
                    return $aHref->RenderHtml($aHref);
                }
                $link = $this->Prefix."/".$this->Controller."/".$this->View."/";
                if (!empty($this->Param1))
                    $link = $link.$this->Param1."/";
                if (!empty($this->WebId))
                    $link = $link.$this->WebId."/";
                if (!empty($this->LangId))
                    $link = $link.$this->LangId."/";
                if (!empty($this->ObjectId))
                    $link = $link.$this->ObjectId."/";
                if (!empty($this->Param2))
                    $link = $link.$this->Param2."/";
                $aHref->Href = $link;
                return $aHref->RenderHtml($aHref);
            }
        }
        $span = new Link();
        $span->Html = $this->GetWord($this->Text);
        $span->Href="#";
        return $span->RenderHtml();
        
        
    }
}
