<?php

namespace Model;

use Types\DataTableColumn;

class BadLogins extends DatabaseTable  implements \Inteface\iDataTable{

    public $DateEvent;
    public $UserName;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "BadLogins";
        $this->SetSelectColums(array("DateEvent", "UserName"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("DateEvent", \Types\DataColumnsTypes::DATETIME, "", true));
        $this->AddColumn(new DataTableColumn("UserName", \Types\DataColumnsTypes::VARCHAR, "", true,255));
    }

    public function InsertDefaultData() {
        
    }

    public function SetValidate($mode = false) {
        
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

}
