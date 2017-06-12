<?php
namespace TemplateFunction; 

class DomainIdToValue extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        $id = self::$Parametrs[0];
        echo $id;
        
        
    }
    
    
    
    
}