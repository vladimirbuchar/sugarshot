<?php

namespace Model;
use Types\RuleType;
use Types\DataTableColumn;

class UserDomains  extends DatabaseTable implements \Inteface\iDataTable{
    public $DomainName;
    public $DomainIdentificator;
    public $Template;
    public $Domain;
    public $EditValue;
    public $IsSystem;
    public $ShowNameInSubDomain;
    public $SaveHiddenColumn;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomains";
        $this->SetSelectColums(array("DomainName","DomainIdentificator","Template","Domain","EditValue","IsSystem","ShowNameInSubDomain","SaveHiddenColumn"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("DomainName", \Types\DataColumnsTypes::VARCHAR, "", false, 255));
        $this->AddColumn(new DataTableColumn("DomainIdentificator", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("Template", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("Domain", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("EditValue", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("IsSystem", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("ShowNameInSubDomain", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("SaveHiddenColumn", \Types\DataColumnsTypes::VARCHAR, "", false, 255));
        
    }
    

    public function InsertDefaultData() {
        $this->Setup();
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
