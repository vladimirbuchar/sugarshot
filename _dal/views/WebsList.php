<?php
namespace Model;
class WebsList extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "WEBSLIST";
        $this->SqlView = "SELECT Webs.*,UserGroupsWeb.UserGroupId FROM `Webs`
JOIN UserGroupsWeb ON Webs.Id = UserGroupsWeb.UserWebId  AND UserGroupsWeb.Deleted = 0 
WHERE Webs.Deleted = 0 
 

";
                
    }
    public function TableExportSettings()
    {
        
    }


}
