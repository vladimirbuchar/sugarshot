<?php

namespace TemplateFunction; 
class TemplateFunction {
    protected static $Parametrs = array();
    /** 
     * @var \Utils\SessionManager
     */
    protected static $SessionManager = null;
    
    

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

        if (self::$SessionManager == null)
            self::$SessionManager = new \Utils\SessionManager(\Utils\SessionManager::$WebMode);        
        $userLang =  self::$SessionManager->GetSessionValue("AdminUserLang");
        if (!self::$SessionManager->IsEmpty("AdminWords$userLang"))
            return self::$SessionManager->GetSessionValue("AdminWords$userLang",$wordid);
        return "";
        
    }
    
}
