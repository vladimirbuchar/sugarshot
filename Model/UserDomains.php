<?php

namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class UserDomains  extends DatabaseTable{
    public $DomainName;
    public $DomainIdentificator;
    public $Template;
    public $Domain;
    public $EditValue;
    public $IsSystem;
    public $ShowNameInSubDomain;
    public $SaveHiddenColumn;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomains";
        $this->SetSelectColums(array("DomainName","DomainIdentificator","Template","Domain","EditValue","IsSystem","ShowNameInSubDomain","SaveHiddenColumn"));
        $this->SetDefaultSelectColumns();
    }

    
    
    
    
            
    
    

    
    public function OnCreateTable() {
        $colDomainName = new DataTableColumn();
        $colDomainName->DefaultValue ="";
        $colDomainName->IsNull = false;
        $colDomainName->Length = 255;
        $colDomainName->Name ="DomainName";
        $colDomainName->Type = "varchar";
        $colDomainName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainName);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue ="";
        $colDomainIdentificator->IsNull = false;
        $colDomainIdentificator->Length = 50;
        $colDomainIdentificator->Name ="DomainIdentificator";
        $colDomainIdentificator->Type = "varchar";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Length = 9;
        $colDomainIdentificator->Name ="Template";
        $colDomainIdentificator->Type = "INTEGER";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Length = 9;
        $colDomainIdentificator->Name ="Domain";
        $colDomainIdentificator->Type = "INTEGER";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Name ="EditValue";
        $colDomainIdentificator->Type = "BOOLEAN";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Name ="IsSystem";
        $colDomainIdentificator->Type = "BOOLEAN";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        //
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue ="";
        $colDomainIdentificator->IsNull = false;
        $colDomainIdentificator->Length = 255;
        $colDomainIdentificator->Name ="ShowNameInSubDomain";
        $colDomainIdentificator->Type = "varchar";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        //
                $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue ="";
        $colDomainIdentificator->IsNull = false;
        $colDomainIdentificator->Length = 255;
        $colDomainIdentificator->Name ="SaveHiddenColumn";
        $colDomainIdentificator->Type = "varchar";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
    }
    

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("DomainName", RuleType::$NoEmpty,$this->GetWord("word185"));
        $this->SetValidateRule("DomainIdentificator", RuleType::$NoEmpty,$this->GetWord("word186"));
        $this->SetValidateRule("DomainIdentificator", RuleType::$Unique,$this->GetWord("word187"));   
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
