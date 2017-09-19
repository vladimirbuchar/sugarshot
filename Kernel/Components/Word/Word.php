<?php
namespace Components;
use HtmlComponents\Span;
class Word extends UserComponents implements \Inteface\iComponent{
    public $WordId = "";
    
    
    public function __construct() {
        
        $this->Type = "Word";
        parent::__construct();
    }
    
    
    public function GetComponentHtml(){
        
        return  $this->GetWord($this->WordId);
    }
    
    
    
}
