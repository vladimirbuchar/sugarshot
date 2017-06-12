<?php
namespace Controller;
class AdminController extends Controllers{
    
    
    public function __construct()
    {
        parent::__construct();
        $this->BlockAdmin();
        $this->SetTemplateData("LeftMenuSettings", "");
        if (!self::$IsAjax)
        {
            $this->SetTemplateData("IsSystem", self::$User->IsSystemUser());
        }
        
        
    }
    protected function SetLeftMenu($level1,$level2)
    {
        $script = "";
        $script .= '<script type="text/javascript">';
        $script .="$(document).ready(function(){";
        $script .='$("#side-menu .'.$level1.'").addClass("active");';
        $script .='$("#side-menu .'.$level1.' ul").attr("class","nav nav-second-level collapse in");';
        $script .='$("#side-menu .'.$level2.' a").addClass("active");';
        $script .="});";
        $script .="</script>";
        $this->SetTemplateData("LeftMenuSettings", $script);
    }
    
    

}
