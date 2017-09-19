<?php
namespace Components;
use HtmlComponents\Span;
class Pager extends UserComponents implements \Inteface\iComponent{
    public $PagerDivId = "";
    public $AddItemText = "word676";
    public $UseUrl;
    public function __construct() {
        
        $this->Type = "Pager";
        parent::__construct();
    }
    
    public function GetPager()
    {
        $span = new Span();
        $span->Html = $this->GetWord($this->AddItemText);
        $span->OnClick = "ReloadListPage('$this->PagerDivId','".$this->UseUrl."','limit')";
        return $span->RenderHtml();     
    }
    public function GetComponentHtml(){
        return  $this->GetPager();
    }
    
    
    
}
