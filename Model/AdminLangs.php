<?php

namespace Model;

use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class AdminLangs extends DatabaseTable {

    public $LangName;
    public $LangIdentificator;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "AdminLangs";
        $this->SetSelectColums(array("LangName", "LangIdentificator"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 50;
        $colLangName->Name = "LangName";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue = "";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Length = 50;
        $colLangIdentificator->Name = "LangIdentificator";
        $colLangIdentificator->Type = "varchar";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    public function TableMigrate() {
        
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("LangName", RuleType::$NoEmpty, $this->GetWord("word82"));
        $this->SetValidateRule("LangIdentificator", RuleType::$NoEmpty, $this->GetWord("word83"));
        $this->SetValidateRule("LangIdentificator", RuleType::$Unique, $this->GetWord("word84"));
        $this->SetValidateRule("LangIdentificator", RuleType::$NoUpdate);
        $this->SetValidateRule("LangIdentificator", RuleType::$ToUpper);
        $this->SetCallModelFunction("WordGroups", "AddColumnLang", "", \Types\DatabaseActions::$Insert);

        $this->SetParametrsColumn("LangIdentificator");
    }

    public function TableExportSettings() {
        
    }

}
