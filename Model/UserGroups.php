<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;

class UserGroups  extends DatabaseTable implements \Inteface\iDataTable{
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
        $this->AddColumn(new DataTableColumn("GroupName", \Types\DataColumnsTypes::VARCHAR, "", FALSE, 50));
        $this->AddColumn(new DataTableColumn("IsSystemGroup", \Types\DataColumnsTypes::BOOLEAN, false, false, 1));
        $this->AddColumn(new DataTableColumn("GroupIdentificator", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
        $this->AddColumn(new DataTableColumn("UserDefaultState", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
        
 
    }

    public function InsertDefaultData() {
        $this->Setup();
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("GroupName", RuleType::NOEMPTY,$this->GetWord("word90"));
        $this->SetValidateRule("GroupName", RuleType::UNIQUE,$this->GetWord("word91"));
    }
    public function TableMigrate()
    {
        
    }
    
    public function TableExportSettings()
    {
        
    }

}
