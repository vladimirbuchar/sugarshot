<?php

class Caching extends Timers {
    public function Caching()
    {
        $this->TimerName = "Caching";
    }
    public function RunTimer()
    {
        $res = dibi::query("SELECT * FROM FRONTENDDETAIL WHERE SaveToCache = 1")->fetchAll();
        
        $lang = \Model\Langs::GetInstance();
        $langUrls = array();
        $cache = Model\Cache::GetInstance(); 
        $cache->TruncateTable();
         
        foreach ($res as $row)
        {
            $lgInfo = $lang->GetObjectById($row["LangId"]);
            $langUrl = Utils\StringUtils::NormalizeUrl($lgInfo->RootUrl);
            $langUrls[] = $langUrl;
            $articleUrl = $langUrl.$row["SeoUrl"]."/";
            
            $html = file_get_contents($articleUrl."?caching=true");
            $objectId = $row["Id"];
            $groupID = $row["GroupId"];
            $langID = $row["LangId"];
            $cache = Model\Cache::GetInstance(); 
            $cache->HtmlCache = $html;
            $cache->SeoUrl = $articleUrl;
            $cache->ObjectId = $objectId;
            $cache->LangId = $langID;
            $cache->UserGroupId = $groupID;
            $cache->CacheTime = \Utils\Utils::Now();
            $cache->SaveObject();
            
        }
        $langUrls = \Utils\ArrayUtils::Distinct($langUrls);
        foreach ($langUrls as $langUrl)
        {
            $cache = Model\Cache::GetInstance(); 
            $cachingUrl  = $langUrl."?caching=true";
            $html = file_get_contents($cachingUrl);
            $cache->HtmlCache = $html;
            $cache->SeoUrl = $langUrl;
            $cache->ObjectId = 0;
            $cache->LangId = 0;
            $cache->UserGroupId = 0;
            $cache->CacheTime = \Utils\Utils::Now();
            $cache->SaveObject();
        }
        
        
    }
}
