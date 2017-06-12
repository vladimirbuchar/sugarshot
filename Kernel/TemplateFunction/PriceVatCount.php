<?php
namespace TemplateFunction; 

class PriceVatCount extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        $price = self::$Parametrs[0];
        $locale = self::$Parametrs[1];
        $format = self::$Parametrs[2];
        $vat = self::$Parametrs[3];
        $count = self::$Parametrs[4];
        $shop = new \xweb_plugins\Shop();
        $price = $shop->GetAllPrice($price, $vat, $count);
        $price =$price["PriceVatCount"];
        return \Utils\StringUtils::PriceFormat($price,$format,$locale);
    }
    
    
    
    
}