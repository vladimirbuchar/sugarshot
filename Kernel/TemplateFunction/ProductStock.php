<?php
namespace TemplateFunction; 

class ProductStock extends \TemplateFunction\TemplateFunction {
    
    public static function CallFunction() {
        
        $count = self::$Parametrs[0];
        
        if (empty($count) || $count == 0) $count = -1;
        if ($count >= 5) // 5 and more
            return self::GetWord ("word801");
        if ($count>= 2 && $count<= 4) // 2- 4
            return self::GetWord ("word802");
        if ($count == 1)
        {
            return self::GetWord ("word803");
        }
        if ($count == -1)
        {
            return self::GetWord ("word804");// nedostupne
        }
        if ($count == -2)
            return self::GetWord ("word805"); //na ceste
        if ($count == -3)
            return self::GetWord ("word806");// skladem u vyrobce
        if ($count == -4)
            return self::GetWord ("word807"); //predobjednavky
        if ($count == -5)
            return self::GetWord ("word808"); // na dotaz
        if ($count == -6)
            return self::GetWord ("word809"); // do 14ti dnu
        echo "sdsad";die();
        return self::GetWord("word808");   
    }
}