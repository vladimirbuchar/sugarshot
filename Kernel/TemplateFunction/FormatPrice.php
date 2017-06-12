<?php
namespace TemplateFunction; 

class FormatPrice extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        
        $price = self::$Parametrs[0];
        $locale = self::$Parametrs[1];
        $format = self::$Parametrs[2];
        return \Utils\StringUtils::PriceFormat($price,$format,$locale);
    }
    
    
    
    
}