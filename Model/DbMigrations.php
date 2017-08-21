<?php

namespace Model;

use Types\DataTableColumn;

class DbMigrations extends DatabaseTable  implements \Inteface\iDataTable{

    public $QueryMigrations;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "DbMigrations";
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("QueryMigrations", \Types\DataColumnsTypes::TEXT, "", true));
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
