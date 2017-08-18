<?php

namespace Objects;
use Utils\StringUtils;
class Langs extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    public function GetLangListByWeb($webid)
    {
        $model = new \Model\Langs();
        return $model->SelectByCondition("WebId = ".$webid." AND Deleted = 0");
    }
    public function BlockAdmin($web)
    {
        $model = new \Model\Langs();
        $res = $model->SelectByCondition("RootUrl ='".$web."'");
        if (!empty($res))
        {
            $webid= $res[0]["WebId"];
            $web =  Webs::GetInstance();
            $web->GetObjectById($webid,true);
            return $web->BlockAdmin;
        }
        return false;
    }
    
    public function GetRootUrl($langId)
    {
        $model = new \Model\Langs();
        $model ->GetObjectById($langId,true);
        if (!StringUtils::StartWidth($mode->RootUrl, SERVER_PROTOCOL))
        {
            $mode->RootUrl = SERVER_PROTOCOL.$mode->RootUrl;
        }
        return StringUtils::EndWith($mode->RootUrl,"/") ? $mode->RootUrl : $mode->RootUrl."/";
    }
    public function GetWebInfo($web)
    {
        if (self::$SessionManager->IsEmpty("WebInfo",$web))
        {
            $url[] = " RootUrl = '".SERVER_PROTOCOL.$web."'";
            $url[] = " RootUrl = '".SERVER_PROTOCOL."www.$web'";
            $url[] = " RootUrl = 'www.$web'";
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString($web,"www."))."'";  
            $url[] = " RootUrl = '".StringUtils::RemoveLastChar($web)."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString(StringUtils::RemoveLastChar($web),"www."))."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString($web,SERVER_PROTOCOL."www."))."'";  
            $url[] = " RootUrl = '".StringUtils::RemoveLastChar($web)."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString(StringUtils::RemoveLastChar($web),SERVER_PROTOCOL."www."))."'";  
            $url[] = " RootUrl = '". trim(StringUtils::RemoveString($web,SERVER_PROTOCOL))."'";  
            $url[] = " RootUrl = '".StringUtils::RemoveLastChar($web)."'";  
            $url[] = " RootUrl = '".$web."'";  
            $url[] = " RootUrl = '". StringUtils::RemoveString( $web,SERVER_PROTOCOL) ."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString(StringUtils::RemoveLastChar($web),SERVER_PROTOCOL))."'";  
            $where = implode(" OR ", $url);
            $model = new \Model\Langs();
            $res = $model->SelectByCondition($where);
            $res = \Utils\ArrayUtils::ObjectToArray($res);
            self::$SessionManager->SetSessionValue("WebInfo",$res,$web);
        }
        return self::$SessionManager->GetSessionValue("WebInfo",$web);
        
    }
}
