<?php
namespace TemplateFunction; 

class PriceCount extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        $price = self::$Parametrs[0];
        $locale = self::$Parametrs[1];
        $format = self::$Parametrs[2];
        $count = self::$Parametrs[3];
        $shop = new \xweb_plugins\Shop();
        $price = $shop->GetAllPrice($price, 0,$count);
        $price = $price["PriceCount"];
        return \Utils\StringUtils::PriceFormat($price,$format,$locale);
    }
}