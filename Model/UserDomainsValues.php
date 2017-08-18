<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class UserDomainsValues  extends DatabaseTable{
    public $DomainId;
    public $ItemId;
    public $ObjectId;
    public $Value;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsValues";
        $this->SetSelectColums(array("DomainId","ItemId","ObjectId","Value"));
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
        
        $colItemId = new DataTableColumn();
        $colItemId->DefaultValue ="";
        $colItemId->IsNull = false;
        $colItemId->Length = 9;
        $colItemId->Name ="ItemId";
        $colItemId->Type = "INTEGER";
        $colItemId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colItemId);
        
        $colObjectId = new DataTableColumn();
        $colObjectId->DefaultValue ="";
        $colObjectId->IsNull = false;
        $colObjectId->Length = 9;
        $colObjectId->Name ="ObjectId";
        $colObjectId->Type = "INTEGER";
        $colObjectId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colObjectId);
        
        
        
        $colValue = new DataTableColumn();
        $colValue->DefaultValue ="";
        $colValue->IsNull = true;
        $colValue->Name ="Value";
        $colValue->Type = "text";
        $colValue->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colValue);
        
         
        
   
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
