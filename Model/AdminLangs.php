<?php

namespace Model;

use Types\RuleType;
use Types\DataTableColumn;

class AdminLangs extends DatabaseTable implements \Inteface\iDataTable {

    public $LangName;
    public $LangIdentificator;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "AdminLangs";
        $this->SetSelectColums(array("LangName", "LangIdentificator"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("LangName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("LangIdentificator", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
    }

    public function InsertDefaultData() {
        $this->Setup();
    }

    public function TableMigrate() {
        
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("LangName", RuleType::$NoEmpty, $this->GetWord("word82"));
        $this->SetValidateRule("LangIdentificator", RuleType::$NoEmpty, $this->GetWord("word83"));
        $this->SetValidateRule("LangIdentificator", RuleType::$Unique, $this->GetWord("word84"));
        $this->SetValidateRule("LangIdentificator", RuleType::$NoUpdate);
        $this->SetValidateRule("LangIdentificator", RuleType::$ToUpper);
        $this->SetCallModelFunction("WordGroups", "AddColumnLang", "", \Types\DatabaseActions::INSERT);

        $this->SetParametrsColumn("LangIdentificator");
    }

    public function TableExportSettings() {
        
    }

}
