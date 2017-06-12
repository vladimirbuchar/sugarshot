<?php
namespace Components;
class Slider extends UserComponents{
    
    
    public function __construct() {
        
        $this->Type = "Slider";
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        $this->UseItemTemplate = true;
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $data = $this->GetDataSource();
        $this->ReplaceItems($data);
        $this->SetReplaceString("SliderId", $this->Id);
        $pointsHtml = "";
        for ($i = 0; $i< count($data);$i++)
        {
            $li = new \HtmlComponents\Li();
            $li->DataTarget = "#home-carousel";
            $li->AddAtrribut("data-slide-to", $i);
            if ($i == 0)
            {
                $li->CssClass = "active";
            }
            $pointsHtml .= $li->RenderHtml();
        }
        $this->SetReplaceString("Points", $pointsHtml);
        
    }
    
    
}
