<?php

namespace Model;

use Types\DataTableColumn;

class ContentConnection extends DatabaseTable implements \Inteface\iDataTable {

    public $ObjectId;
    public $ObjectIdConnected;
    public $ConnectedType;
    public $SettingConnection;

    public function __construct() {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentConnection";
        $this->MultiLang = false;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ObjectId", "ObjectIdConnected", "ConnectedType", "SettingConnection"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ObjectId", \Types\DataColumnsTypes::INTEGER, 0, FALSE, 9));
        $this->AddColumn(new DataTableColumn("ObjectIdConnected", \Types\DataColumnsTypes::INTEGER, 0, FALSE, 9));
        $this->AddColumn(new DataTableColumn("ConnectedType", \Types\DataColumnsTypes::VARCHAR, "", false, 100));
        $this->AddColumn(new DataTableColumn("SettingConnection", \Types\DataColumnsTypes::TEXT, "", true));
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
