<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class UserDomainsGroups  extends DatabaseTable{
    public $DomainId;
    public $GroupName;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsGroups";
        $this->SetSelectColums(array("DomainId","GroupName"));
        $this->SetDefaultSelectColumns();
    }
    
        
    public function OnCreateTable() {
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="DomainId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 50;
        $colShowName->Name ="GroupName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        
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
