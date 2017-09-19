<?php
namespace Components;
use HtmlComponents\Div;
use HtmlComponents\Datalist;
use HtmlComponents\Textbox;
use HtmlComponents\Span;
use HtmlComponents\FontAwesome;
use HtmlComponents\Button;
class Search extends UserComponents implements \Inteface\iComponent{
    
    public $DefaultText ="";
     
    public function __construct() {
        
        $this->Type = "Search";
        $this->LinkJavascript= true;
        $this->LoadHtml = true;
        parent::__construct();
        
    }     
    
    public function GetComponentHtml()
    {
     
    }
    
    
}
