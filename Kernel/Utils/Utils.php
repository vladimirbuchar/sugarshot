<?php
namespace Utils;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author vlada
 */
class Utils {
    public static function GetIp()
    {
        return $_SERVER["REMOTE_ADDR"];
    }
    public static function  Now()
    {
        return date("Y-m-d H:i:s");
    }
    
    public static function GetNowMktime()
    {
        return mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
   }
    
    public static function AddMinutes($minutes)
    {
        $now = time();
        $add = $now + ($minutes * 60);
        return date("Y-m-d H:i:s",$add);
    }
    public static function GetActualYear()
    {
        return date("Y");    
    }
    public static function GetDefaultLang()
    {
        return strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
    }
    
    public static function GetPriceVat($price,$vat)
    {
        $shop = new \xweb_plugins\Shop();
        $price = $shop->GetAllPrice($price, $vat);
        
        return $price["PriceVat1ks"];
        
    }
 }
