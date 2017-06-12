<?php
namespace Utils;
class SessionManager  {
    private static $_seesionMode = "";
    public static  $AdminMode ="adminmode";
    public static $WebMode = "webmode";
    public function __construct($sessionMode ="") {
        $this->SetSessionMode($sessionMode);
    }
    
    private function SetSessionMode($sessionMode)
    {
        if (!empty($sessionMode))
        {
            $_SESSION["activeSessionMode"] = $sessionMode;
            self::$_seesionMode = $sessionMode;
        }
        else 
        {
            if (!empty($_SESSION["activeSessionMode"]))
            {
                self::$_seesionMode = $_SESSION["activeSessionMode"];
            }
        }
    }


    public function GetSessionValue($key,$key1 = "")
    {
        if (empty($key1))
            return $_SESSION[self::$_seesionMode][$key];
        else 
            return $_SESSION[self::$_seesionMode][$key][$key1];
            
    }
    
    public function SetSessionValue($key, $value,$key1 = "")
    {
        if (empty($key1))
            $_SESSION[self::$_seesionMode][$key] = $value;
        else 
            $_SESSION[self::$_seesionMode][$key][$key1] = $value;
    }
    
    public function IsEmpty($key,$key1 = "")
    {
        if (empty($key1))
            return empty($_SESSION[self::$_seesionMode][$key]);
        else 
        {
            return empty($_SESSION[self::$_seesionMode][$key][$key1]);
        }   
    }
    public function UnsetKey($key,$key1="")
    {
        if (empty($key1))
        {
            $_SESSION[self::$_seesionMode][$key] = null;
            unset($_SESSION[self::$_seesionMode][$key]);
        }
        else 
        {
            $_SESSION[self::$_seesionMode][$key][$key1] = null;
            unset($_SESSION[self::$_seesionMode][$key][$key1]);
        }
    }       
}