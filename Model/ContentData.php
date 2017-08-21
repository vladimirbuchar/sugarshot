<?php

namespace Model;

use Types\DataTableColumn;

class ContentData extends DatabaseTable implements \Inteface\iDataTable {

    public $ContentId;
    public $ItemName;
    public $Value;
    public $ValueNoHtml;
    public $ItemId;

    public function __construct() {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentData";
        $this->MultiWeb = true;
        $this->MultiLang = true;
        $this->SetSelectColums(array("ContentId", "ItemName", "Value", "ValueNoHtml", "ItemId"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ContentId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("ItemName", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("Value", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("ValueNoHtml", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("ItemId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
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
