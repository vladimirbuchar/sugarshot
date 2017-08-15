<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class UserDomainsItemsInGroups  extends DatabaseTable{
    public $ItemId;
    public $GroupId;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsItemsInGroups";
        $this->SetSelectColums(array("ItemId","GroupId"));
        $this->SetDefaultSelectColumns();
        
    }
        
    public function OnCreateTable() {
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="ItemId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="GroupId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
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
