<?php

namespace Model;

use Types\DataTableColumn;

class Crons extends DatabaseTable  implements \Inteface\iDataTable {

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
        $this->AddColumn(new DataTableColumn("CronName", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("CronUrl", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("IsActive", \Types\DataColumnsTypes::BOOLEAN, false, false, 1));
        $this->AddColumn(new DataTableColumn("IsRun", \Types\DataColumnsTypes::BOOLEAN, false, true));
        $this->AddColumn(new DataTableColumn("RunMode", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("LastRun", \Types\DataColumnsTypes::DATETIME, "", true));
    }

    public function InsertDefaultData() {
        $this->Setup();
    }

    public function SetValidate($mode = false) {
        
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

}
