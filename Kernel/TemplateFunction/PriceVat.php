<?php
namespace TemplateFunction; 

class PriceVat extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        $price = self::$Parametrs[0];
        $locale = self::$Parametrs[1];
        $format = self::$Parametrs[2];
        $vat = self::$Parametrs[3];
        return \Utils\StringUtils::PriceFormat(self::GetPriceVat($price, $vat),$format,$locale);
    }
    
    private static function GetPriceVat($price,$vat)
    {
        $shop = new \xweb_plugins\Shop();
        $price = $shop->GetAllPrice($price, $vat);
        
        return $price["PriceVat1ks"];
        
    }
}