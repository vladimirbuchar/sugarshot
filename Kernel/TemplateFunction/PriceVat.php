<?php
namespace TemplateFunction; 

class PriceVat extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        $price = self::$Parametrs[0];
        $locale = self::$Parametrs[1];
        $format = self::$Parametrs[2];
        $vat = self::$Parametrs[3];
        return \Utils\StringUtils::PriceFormat(\Utils\Utils::GetPriceVat($price, $vat),$format,$locale);
    }
}