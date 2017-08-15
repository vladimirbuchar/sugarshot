<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
 
class UserGroupsModules  extends DatabaseTable{
    public $UserGroupId;
    public $ModuleId;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroupsModules";
        $this->SetSelectColums(array("UserGroupId","ModuleId"));
        $this->SetDefaultSelectColumns();
    }
    
    
            


    
    public function OnCreateTable() {
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 9;
        $colUserGroupId->Name ="UserGroupId";
        $colUserGroupId->Type = "INTEGER";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
        
        $colModuleId = new DataTableColumn();
        $colModuleId->DefaultValue =0;
        $colModuleId->IsNull = true;
        $colModuleId->Length = 9;
        $colModuleId->Name ="ModuleId";
        $colModuleId->Type = "INTEGER";
        $colModuleId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colModuleId);
        
        
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    public function SetValidate($mode = false) {
        
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
