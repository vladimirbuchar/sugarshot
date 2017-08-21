<?php

namespace Model;

use Types\RuleType;
use Types\DataTableColumn;

class Users extends DatabaseTable  implements \Inteface\iDataTable{

    public $UserName;
    public $FirstName;
    public $LastName;
    public $UserEmail;
    public $IsBadLogin = false;
    public $BlockDiscusion = false;
    public $IsActive = false;
    public $DefaultLang;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "Users";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("UserName", "FirstName", "LastName", "UserEmail", "BlockDiscusion", "IsActive", "DefaultLang"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {

        $this->AddColumn(new DataTableColumn("UserName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("UserPassword", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("FirstName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("LastName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("UserEmail", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
        $this->AddColumn(new DataTableColumn("BlockDiscusion", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("IsActive", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("DefaultLang", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
    }

    public function InsertDefaultData() {
        $this->Setup();
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("UserName", RuleType::$NoEmpty, $this->GetWord("word480"));
        $this->SetValidateRule("UserName", RuleType::$Unique, $this->GetWord("word481"));
        $this->SetValidateRule("FirstName", RuleType::$NoEmpty, $this->GetWord("word482"));
        $this->SetValidateRule("LastName", RuleType::$NoEmpty, $this->GetWord("word483"));
        $this->SetValidateRule("UserEmail", RuleType::$NoEmpty, $this->GetWord("word484"));
        $this->SetValidateRule("UserEmail", RuleType::$Unique, $this->GetWord("word485"));
        if ($mode) {
            $this->SetValidateRule("UserPassword", RuleType::$Hash);
            $this->SetValidateRule("UserPassword", RuleType::$NoEmpty, $this->GetWord("word486"));
        }
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

}
