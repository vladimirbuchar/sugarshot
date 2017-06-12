<?php
namespace Model;
class UserModulesView extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "USERMODULESVIEW";
        $this->SqlView = "SELECT DISTINCT Modules.Id, Modules.ModuleName ,  ModuleControler,ModuleView, UserGroupId FROM `Modules` 
                JOIN UserGroupsModules ON Modules.Id = UserGroupsModules.ModuleId AND Modules.Deleted= 0 AND UserGroupsModules.Deleted=0 ";
                
    }
    public function TableExportSettings()
    {
        
    }


}
