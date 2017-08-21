<?php

namespace Model;

use Types\DataTableColumn;

class UserDomainsValues extends DatabaseTable  implements \Inteface\iDataTable{

    public $DomainId;
    public $ItemId;
    public $ObjectId;
    public $Value;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "UserDomainsValues";
        $this->SetSelectColums(array("DomainId", "ItemId", "ObjectId", "Value"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("DomainId", \Types\DataColumnsTypes::INTEGER, 0, FALSE, 9));
        $this->AddColumn(new DataTableColumn("ItemId", \Types\DataColumnsTypes::INTEGER, 0, FALSE, 9));
        $this->AddColumn(new DataTableColumn("ObjectId", \Types\DataColumnsTypes::INTEGER, 0, FALSE, 9));
        $this->AddColumn(new DataTableColumn("Value", \Types\DataColumnsTypes::TEXT, ""));
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
