<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserGroupsModules  extends DatabaseTable{
    public $UserGroupId;
    public $ModuleId;
    //private static $_instance = null;
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroupsModules";
        $this->SetSelectColums(array("UserGroupId","ModuleId"));
        $this->SetDefaultSelectColumns();
    }
    /*
    public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/
    
    public function  SetUserGroupModules($userGroup,$module)
    {
        $this->UserGroupId = $userGroup;
        $this->ModuleId = $module;
        $this->SaveObject($this);
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
