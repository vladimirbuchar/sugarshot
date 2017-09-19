<?php
namespace Components;
class Repeater extends UserComponents implements \Inteface\iComponent{ 
    
    public function __construct() {
        
        $this->Type = "Repeater";
        $this->LoadHtml = true;
        parent::__construct();
        
    }   
    public function GetComponentHtml()
    {
        $html = "";
        
        $data = $this->GetDataSource();
        
        
        if (empty($data))
        {
            $html = "";
        }
        else
        {
            $html = $this->PrepareHtml($this->RenderHtml, $data);
        }
        return $html;
    }
    
}
