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
            $web->GetObjectById($webId,true);
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
            $user =  \Model\Users::GetInstance();
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
                $ug =  \Model\UserGroups::GetInstance();
                $ugw->UserGroupId = $ug->GetUserGroupByIdeticator("system")["Id"];
                $ugw->UserWebId = $id;
                $ugw->SaveObject();
            }
            
        }   
    }
            
    
    
}
