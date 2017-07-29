<?php

namespace TemplateFunction; 
 
class AddDays extends TemplateFunction {
    public static function CallFunction() 
    {
        $daycount = empty(self::$Parametrs[0]) ?"" : self::$Parametrs[0];
        $dayAddTime = empty(self::$Parametrs[1]) ?"" : self::$Parametrs[1];
        return \Utils\Utils::AddDays($daycount,$dayAddTime);
    }
}
