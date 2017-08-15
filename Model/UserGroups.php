<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class UserGroups  extends DatabaseTable{
    public $GroupName;
    public $IsSystemGroup;
    public $GroupIdentificator;
    public $UserDefaultState;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroups";
        $this->SetSelectColums(array("GroupName","IsSystemGroup","GroupIdentificator","UserDefaultState"));
        $this->SetDefaultSelectColumns();
        
    }
    
    public function OnCreateTable() {
        
        $colUserGroupName = new DataTableColumn();
        $colUserGroupName->DefaultValue ="";
        $colUserGroupName->IsNull = false;
        $colUserGroupName->Length = 50;
        $colUserGroupName->Name ="GroupName";
        $colUserGroupName->Type = "varchar";
        $colUserGroupName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupName);
        
        $colIsSystemGroup = new DataTableColumn();
        $colIsSystemGroup->DefaultValue =0;
        $colIsSystemGroup->IsNull = false;
        $colIsSystemGroup->Length = 1;
        $colIsSystemGroup->Name ="IsSystemGroup";
        $colIsSystemGroup->Type = "BOOLEAN";
        $colIsSystemGroup->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colIsSystemGroup);
        
        $colGroupIdentificator = new DataTableColumn();
        $colGroupIdentificator->DefaultValue ="";
        $colGroupIdentificator->IsNull = true;
        $colGroupIdentificator->Length = 50;
        $colGroupIdentificator->Name ="GroupIdentificator";
        $colGroupIdentificator->Type = "varchar";
        $colGroupIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colGroupIdentificator);
        
        $colGroupIdentificator = new DataTableColumn();
        $colGroupIdentificator->DefaultValue ="";
        $colGroupIdentificator->IsNull = true;
        $colGroupIdentificator->Length = 50;
        $colGroupIdentificator->Name ="UserDefaultState";
        $colGroupIdentificator->Type = "varchar";
        $colGroupIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colGroupIdentificator);
        
 
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("GroupName", RuleType::$NoEmpty,$this->GetWord("word90"));
        $this->SetValidateRule("GroupName", RuleType::$Unique,$this->GetWord("word91"));
    }
    public function TableMigrate()
    {
        
    }
    
    public function TableExportSettings()
    {
        
    }

}
