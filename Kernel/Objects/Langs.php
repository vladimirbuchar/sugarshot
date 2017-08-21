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
            $web->GetObjectById($webid);
            return $web->BlockAdmin;
        }
        return false;
    }
    
    public function GetRootUrl($langId)
    {
        $model = new \Model\Langs();
        $model ->GetObjectById($langId,true);
        if (!StringUtils::StartWidth($model->RootUrl, SERVER_PROTOCOL))
        {
            $model->RootUrl = SERVER_PROTOCOL.$model->RootUrl;
        }
        return StringUtils::EndWith($model->RootUrl,"/") ? $model->RootUrl : $model->RootUrl."/";
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
    
    public function CreateLangFolder($id)
    {
        $content = new \Objects\Content();
        $model = \Model\Langs::GetInstance();
        $obj = $model->GetObjectById($id);
        
        $folderId = $content->GetIdByIdentificator("langfolder",$_GET["webid"]);
        
        $name = $obj->LangName;
        if ($folderId == 0)
        {
            $content->CreateContentItem($name, true,  "langfolder$id", "", "langfolder",false,$id,0,true,"langfolder",array(),"", 0, 0, "", "", "", 0,  0,  0,  99999, 0,false);
        }
        else 
        {
            $user = new \Objects\Users();
            $content->CreateVersion($folderId, $name, true, $user->GetUserId(), "langfolder$id", 0, false, $id, "", "", "", "", false, "");
        }
    }
}
