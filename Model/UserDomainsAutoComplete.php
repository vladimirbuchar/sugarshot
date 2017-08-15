<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class UserDomainsAutoComplete  extends DatabaseTable{
    public $DomainItemId;
    public $Value;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsAutoComplete";
        $this->SetSelectColums(array("DomainItemId","Value"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue ="";
        $colWebName->IsNull = true;
     
        $colWebName->Name ="Value";
        $colWebName->Type = "TEXT";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);
        
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue ="";
        $colWebName->IsNull = true;
        $colWebName->Name ="DomainItemId";
        $colWebName->Type = "INT";
        $colWebName->Length = 9;
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);
        
        
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
