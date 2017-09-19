<?php
namespace Components;
class UserActivate extends UserComponents implements \Inteface\iComponent{
    
    public function __construct() {
        
        $this->Type = "UserActivate";
        $this->IgnoreCache = true;
        parent::__construct();
    }     
    public function GetComponentHtml()
    {
        if (empty($_GET["param1"]))
            return;
        $param = $_GET["param1"];
        $param = base64_decode($param);
        $ar = explode("#", $param);
        $id = $ar[2];
        $user =  new \Objects\Users();
        $user->UserActivate($id);
        $this->GoHome();
    }
    
    
}
