<?php
namespace Model;
 
  use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class UsersInGroup  extends DatabaseTable{
    public $UserId;
    public $GroupId;
    public $IsMainGroup;

    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UsersInGroup";
        $this->SetSelectColums(array("UserId","GroupId","IsMainGroup"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        
        $colUserId = new DataTableColumn();
        $colUserId->DefaultValue ="";
        $colUserId->IsNull = false;
        $colUserId->Length = 9;
        $colUserId->Name ="UserId";
        $colUserId->Type = "INTEGER";
        $colUserId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserId);
        
        $colGroupId = new DataTableColumn();
        $colGroupId->DefaultValue ="";
        $colGroupId->IsNull = false;
        $colGroupId->Length = 9;
        $colGroupId->Name ="GroupId";
        $colGroupId->Type = "INTEGER";
        $colGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colGroupId);
        
        $colIsMainGroup = new DataTableColumn();
        $colIsMainGroup->DefaultValue =0;
        $colIsMainGroup->IsNull = true;
        $colIsMainGroup->Length = 1;
        $colIsMainGroup->Name ="IsMainGroup";
        $colIsMainGroup->Type = "BOOLEAN";
        $colIsMainGroup->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colIsMainGroup);
        
         
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
