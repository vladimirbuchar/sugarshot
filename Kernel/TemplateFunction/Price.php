<?php
namespace TemplateFunction; 

class Price extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        $price = self::$Parametrs[0];
        $locale = self::$Parametrs[1];
        $format = self::$Parametrs[2];
        $shop = new \xweb_plugins\Shop();
        $price = $shop->GetAllPrice($price, 0);
        $price = $price["Price1ks"];
        return \Utils\StringUtils::PriceFormat($price,$format,$locale);
    }
    
    
    
    
}