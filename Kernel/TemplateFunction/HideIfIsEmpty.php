<?php
namespace TemplateFunction; 

class HideIfIsEmpty extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        $item = self::$Parametrs[0];
        $item = strip_tags($item);
        $item = trim($item);
        if (empty($item))
            return "dn";
        
        
    }
    
    
    
    
}