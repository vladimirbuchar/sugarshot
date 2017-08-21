<?php

namespace Model;

use Types\DataTableColumn;

class ObjectHistory  extends DatabaseTable implements \Inteface\iDataTable{
    public $ObjectHistoryName;
    public $ObjectId;
    public $Action;
    public $UserId;
    public $IP;
    public $OldData;
    public $CreateDate;
    public $ActiveItem;
    public $UserName;
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = false;
        $this->ObjectName = "ObjectHistory";
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ObjectHistoryName","ObjectId","Action","UserId","IP","OldData","CreateDate","ActiveItem","UserName"));
        $this->SetDefaultSelectColumns();
        
    }
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ObjectHistoryName", \Types\DataColumnsTypes::VARCHAR, "", false, 100));
        $this->AddColumn(new DataTableColumn("ObjectId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("Action", \Types\DataColumnsTypes::VARCHAR, "", false, 100));
        $this->AddColumn(new DataTableColumn("UserId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("UserName", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
        $this->AddColumn(new DataTableColumn("IP", \Types\DataColumnsTypes::VARCHAR, "", false, 100));
        $this->AddColumn(new DataTableColumn("OldData", \Types\DataColumnsTypes::TEXT, "", true, 100));
        $this->AddColumn(new DataTableColumn("CreateDate", \Types\DataColumnsTypes::DATETIME, "", false, 100));
        $this->AddColumn(new DataTableColumn("ActiveItem", \Types\DataColumnsTypes::BOOLEAN, true, true, 1));
    }
    

    public function InsertDefaultData() {
        $this->Setup();
    }

    public function SetValidate($mode = false) {
        
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
