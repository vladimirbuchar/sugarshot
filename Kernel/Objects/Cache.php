<?php

namespace Objects;
use Dibi;
use Types\CacheTimeType;
 
class Cache extends ObjectManager {
    public function __construct() {
        parent::__construct();
        
    }
    public function GetDataFromCache($componentType,$userGroupId,$cacheType,$time=1,$timeType="")
    {
        if ($time< 1)
            $time = 1;
        if (empty($timeType))
            $timeType = CacheTimeType::$Hours;
        if ($timeType == CacheTimeType::$Minutes)
            $time = "00:".$time.":00";
        else if ($timeType == CacheTimeType::$Hours)
            $time = $time.":00:00";
        else 
            $time = "01:00:00";
        $res = dibi::query("SELECT * FROM `Cache` WHERE TIMEDIFF(NOW(),CacheTime) <= '$time' AND UserGroupId = %i AND Component=%s  AND CacheType = %s ORDER BY Id DESC LIMIT 1 ",$userGroupId,$componentType,$cacheType)->fetchAll();
        
        if (empty($res)) return "";
        return $res[0]["HtmlCache"];
    }
    
    public function SaveToCache($Id,$UserGroupId,$HtmlCache,$Time,$CacheType)
    {
        $model =  \Model\Cache::GetInstance();
        $model->Component = $Id;
        $model->UserGroupId = $UserGroupId;
        $model->HtmlCache = $HtmlCache;
        $model->CacheTime = $Time;
        $model->CacheType = $CacheType;
        $model->SaveObject();
    }
    
    public function ClearCache()
    {
        $model =  \Model\Cache::GetInstance();
        $model->TruncateTable();
    }
}
