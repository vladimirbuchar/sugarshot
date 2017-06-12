<?php

namespace TemplateFunction; 
class TemplateFunction {
    protected static $Parametrs = array();
    private static $_sessionManager = null;
    
    public static function SetParametrs($params)
    {
        self::$Parametrs = array();
        foreach ($params as $param)
        {
           $param = \Utils\StringUtils::RemoveString($param, "'") ;
           if (\Utils\StringUtils::StartWidth($param, "{") && \Utils\StringUtils::EndWith($param, "}"))
                 $param = ""  ;
           self::$Parametrs[] = $param; 
        }
    }
    protected static function GetWord($wordid)
    {
        if (self::$_sessionManager == null)
            self::$_sessionManager = new \Utils\SessionManager(\Utils\SessionManager::$WebMode);
        $userLang =  self::$_sessionManager->GetSessionValue("AdminUserLang");
        if (!self::$_sessionManager->IsEmpty("AdminWords$userLang"))
            return self::$_sessionManager->GetSessionValue("AdminWords$userLang",$wordid);
        return "";
        
    }
    
}
