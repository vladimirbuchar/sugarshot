<?php

namespace Model;

use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;

class Crons extends DatabaseTable {

    public $CronName;
    public $CronUrl;
    public $IsActive;
    public $IsRun;
    public $RunMode;
    public $LastRun;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "Crons";

        $this->SetSelectColums(array("CronName", "CronUrl", "IsActive", "IsRun", "RunMode", "LastRun"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->Name = "CronName";
        $colLangName->Type = "varchar";
        $colLangName->Length = 255;
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->Name = "CronUrl";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue = "";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Name = "IsActive";
        $colLangIdentificator->Type = "BOOLEAN";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);

        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue = "";

        $colLangIdentificator->Name = "IsRun";
        $colLangIdentificator->Type = "BOOLEAN";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->Name = "RunMode";
        $colLangName->Type = "varchar";
        $colLangIdentificator->Length = 255;
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue = "";
        $colLangIdentificator->IsNull = true;
        $colLangIdentificator->Name = "LastRun";
        $colLangIdentificator->Type = "DATETIME";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    public function SetValidate($mode = false) {
        
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

}
