<?php
namespace Model;
class UserGroupDetail extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroupDetail";
        $this->SqlView = "SELECT UserGroups.*,UserGroupsModules.ModuleId,UserGroupsWeb.UserWebId FROM `UserGroups` 
LEFT JOIN UserGroupsModules ON UserGroups.Id = UserGroupsModules.UserGroupId  AND UserGroupsModules.Deleted = 0
LEFT JOIN UserGroupsWeb ON UserGroups.Id = UserGroupsWeb.UserGroupId AND UserGroupsWeb.Deleted = 0";
    }
    public function TableExportSettings()
    {
        
    }


}
