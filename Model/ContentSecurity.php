<?php

namespace Model;

use Types\DataTableColumn;

class ContentSecurity extends DatabaseTable  implements \Inteface\iDataTable{

    public $GroupId;
    public $SecurityType;
    public $ObjectId;
    public $Value;

    public function __construct() {

        parent::__construct();

        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentSecurity";
        $this->SetSelectColums(array("GroupId", "SecurityType", "ObjectId", "Value"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("GroupId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("ObjectId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("SecurityType", \Types\DataColumnsTypes::VARCHAR, "", FALSE, 50));
        $this->AddColumn(new DataTableColumn("Value", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1));
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
