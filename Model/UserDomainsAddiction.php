<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class UserDomainsAddiction  extends DatabaseTable{
    public $DomainId;
    public $AddictionName;
    public $Item1;
    public $Item1Value;
    public $ItemX;
    public $ItemXValue;
    public $ActionName;
    public $RuleName;
    public $Priority; 
    
    public $IsDomain1;
    public $DomainId1;
    public $ItemId1;
    
    public $IsDomainX;
    public $DomainIdX;
    public $ItemIdX;
    //private static $_instance = null;
     
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsAddiction";     
        $this->SetSelectColums(array("DomainId","AddictionName","Item1","Item1Value","ItemX","ItemXValue","ActionName","RuleName","Priority"," IsDomain1","DomainId1","ItemId1","IsDomainX","DomainIdX","ItemIdX"));
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
        $colShowName->Name ="AddictionName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";  
        $colDomainId->IsNull = false;
        $colDomainId->Length = 20;
        $colDomainId->Name ="Item1";
        $colDomainId->Type = "varchar";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 255;
        $colShowName->Name ="Item1Value";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 20;
        $colDomainId->Name ="ItemX";
        $colDomainId->Type = "varchar";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 255;
        $colShowName->Name ="ItemXValue";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 50;
        $colShowName->Name ="ActionName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 50;
        $colShowName->Name ="RuleName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =1;
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="Priority";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="IsDomain1";
        $colDomainId->Type = "BIT";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="DomainId1";
        $colDomainId->Length = 9;
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="ItemId1";
        $colDomainId->Length = 9;
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="IsDomainX";
        $colDomainId->Type = "BIT";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="DomainIdX";
        $colDomainId->Length = 9;
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="ItemIdX";
        $colDomainId->Length = 9;
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
