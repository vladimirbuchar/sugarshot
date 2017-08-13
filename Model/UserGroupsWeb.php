<?php
namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserGroupsWeb  extends DatabaseTable{
    public $UserGroupId;
    public $UserWebId;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroupsWeb";
        $this->SetSelectColums(array("UserGroupId","UserWebId"));
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
    
    public function OnCreateTable() {
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 9;
        $colUserGroupId->Name ="UserGroupId";
        $colUserGroupId->Type = "INTEGER";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
        
        $colWebId = new DataTableColumn();
        $colWebId->DefaultValue =0;
        $colWebId->IsNull = true;
        $colWebId->Length = 9;
        $colWebId->Name ="UserWebId";
        $colWebId->Type = "INTEGER";
        $colWebId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebId);
        
         
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
