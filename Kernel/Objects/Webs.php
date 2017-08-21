<?php

namespace Objects;
use Dibi;
use Utils\ArrayUtils;
class Webs extends ObjectManager{
    private static $_webInfo = array();
    public function __construct() {
        parent::__construct();

    }
    
    public function SetWebInfo($webId)
    {   
        if (empty(self::$_webInfo))
        {
            $web = \Model\Webs::GetInstance();
            $web->GetObjectById($webId);
            self::$_webInfo["GenerateAjaxLink"] = $web->GenerateAjaxLink;
            self::$_webInfo["DefaultFramework"] = $web->DefaultFramework;
            self::$_webInfo["CookiesAccept"] = $web->CookiesAccept;    
            self::$_webInfo["UseHttps"] = $web->UseHttps;
            $ipRestriction = array();
            $ipRestriction["WebIpRestrictionAll"]  = $web->WebIpRestrictionAll;
            $ipRestriction["WebIpRestrictionAceptIp"]  = $web->WebIpRestrictionAceptIp;
            $ipRestriction["WebIpRestrictionBlockIp"]  = $web->WebIpRestrictionBlockIp;
            $ipRestriction["WebIpAddress"]  = $web->WebIpAddress;
            $ipRestriction["AdminIpRestrictionAll"]  = $web->AdminIpRestrictionAll;
            $ipRestriction["AdminIpRestrictionAceptIp"]  = $web->AdminIpRestrictionAceptIp;
            $ipRestriction["AdminIpRestrictionBlockIp"]  = $web->AdminIpRestrictionBlockIp;
            self::$_webInfo["IpRestriction"] = $ipRestriction;
            
            
        }
        
    }
    
    
    
    public function GetWebListByUser($userGroupId)
    {
        return dibi::query("SELECT * FROM WEBSLIST WHERE UserGroupId =%i",$userGroupId)->fetchAll();
    }
    public function CheckWebPrivileges($userGroupId,$webId)
    {
       $res = dibi::query("SELECT * FROM WEBSLIST WHERE UserGroupId =%i AND Id =%i",$userGroupId,$webId)->fetchAll();
       if (empty($res)) return FALSE;
       return TRUE;
    }
    public function AjaxLinkLoad()
    {
        return self::$_webInfo["GenerateAjaxLink"];
    }
    public function JavascriptFrameworkMode()
    {
        return self::$_webInfo["DefaultFramework"];
    }
    public function MustBeCookiesAccept()
    {
        return self::$_webInfo["CookiesAccept"];
    }
    public function UseHttps()
    {
        return self::$_webInfo["UseHttps"] == 1 || self::$_webInfo["UseHttps"]=="1";
    }
    public function GetIpRestriction()
    {
        return self::$_webInfo["IpRestriction"];
    }
    
    public function SaveWebPrivileges($id,$privileges)
    {
        $newPrivilges = array();
        $i =0;
        foreach ($privileges as $row)
        {
            $newPrivilges[$i]["PrivilegesName"] = $row[0];
            $newPrivilges[$i]["UserGroup"] = $row[1];
            $newPrivilges[$i]["Value"] = $row[2];
            $i++;
        }
        $xml = ArrayUtils::ArrayToXml($newPrivilges);
        dibi::query("UPDATE Webs SET WebPrivileges = %s WHERE Id = %i",$xml,$id);
    }
    
    public function SetDefaultWebPrivileges($id)
    {
        $ugw =  \Model\UserGroupsWeb::GetInstance();
        if (empty($ugw->SelectByCondition("UserWebId = $id")))
        {
            $user =  new \Objects\Users();
            if ($user->IsSystemUser())
            {
                $ugw->UserGroupId = $user->GetUserGroupId();
                $ugw->UserWebId = $id;
                $ugw->SaveObject();
            }
            else 
            {
                $ugw->UserGroupId = $user->GetUserGroupId();
                $ugw->UserWebId = $id;
                $ugw->SaveObject();
                $ug =  new \Objects\Users();
                $ugw->UserGroupId = $ug->GetUserGroupByIdeticator("system")["Id"];
                $ugw->UserWebId = $id;
                $ugw->SaveObject();
            }
            
        }   
    }
    
    public function GetRobotsTxt($webUrl)
    {
        $res = dibi::query("SELECT RobotsTxt FROM  Webs
                LEFT JOIN Langs ON Langs.WebId = Webs.Id AND Langs.RootUrl =%s
                ",$webUrl)->fetchAll();
        return $res[0]["RobotsTxt"];   
    }
    
    public function GenerateSitemapXml($webUrl)
    {
        
        $webInfo  = dibi::query("SELECT Webs.Id AS WebId, SiteMapStart,SiteMapEnd,SiteMapItemUrl,SiteMapItemImage,SiteMapItemVideo,SiteMapItemStart,SiteMapItemEnd FROM  Webs
                LEFT JOIN Langs ON Langs.WebId = Webs.Id AND Langs.RootUrl =%s
                ",$webUrl)->fetchAll();
        
        $template = $webInfo[0];
        $lang = new \Objects\Langs();
        $langList =$lang->GetLangListByWeb($template["WebId"]);
        $langList = ArrayUtils::ValueAsKey($langList, "Id");
        $userGroups = new \Objects\Users();
        $anonymous = $userGroups->GetAnonymousGroup();
        $res = \dibi::query("SELECT * FROM FrontendDetail_materialized WHERE GroupId = %i And WebId = %i",$anonymous["Id"],$template["WebId"])->fetchAll();
        $xml = $template["SiteMapStart"]."\n";   
        
        foreach ($res as $row)
        {
            $url = $template["SiteMapItemUrl"];
            $image = $template["SiteMapItemImage"];
            $video = $template["SiteMapItemVideo"];
            $xmlItemTmp ="";
            $xmlItemTmp = $template["SiteMapItemStart"]."\n";   
            if (!empty($url))
                $xmlItemTmp .= $url."\n";   
            if (!empty($image))
                $xmlItemTmp .= $image."\n";   
            if (!empty($video))
                $xmlItemTmp .= $video."\n";   
            $xmlItemTmp .= $template["SiteMapItemEnd"]."\n";   
            $landId = $row["LangId"];
            $url = SERVER_PROTOCOL.$langList[$landId]["RootUrl"];
            
            foreach ($row as $key => $value)            
            {
                
                if ($key == "Data")
                {
                    $ar = ArrayUtils::XmlToArray($value,"SimpleXMLElement",LIBXML_NOCDATA);
                    foreach ($ar as $kar => $var)
                    {
                        $xmlItemTmp = str_replace("{".$kar."}", $var,$xmlItemTmp);
                    }
                }
                else
                {
                    if ($key == "SeoUrl")
                    {
                        $value = $url."/".$value."/";
                        $value  = str_replace('//', '/', $value);
                    }
                    $xmlItemTmp = str_replace("{".$key."}", $value,$xmlItemTmp);
                }
                
            }
            $xml .=$xmlItemTmp."\n";
        }
        
        $xml .= $template["SiteMapEnd"]."\n";   
        return $xml;
        
    }
    
}
