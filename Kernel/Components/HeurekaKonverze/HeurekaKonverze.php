<?php
namespace Components;
class HeurekaKonverze extends UserComponents implements \Inteface\iComponent{
    public function __construct() {
        $this->IgnoreCache = true;
        parent::__construct();
    }
    public function GetComponentHtml() {
        $value = self::$SessionManager->GetSessionValue("HeurekaKonverze");
        self::$SessionManager->UnsetKey("HeurekaKonverze");
        return $value;
        
        
    }
}
