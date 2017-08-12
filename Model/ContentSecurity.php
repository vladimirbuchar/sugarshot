<?php

namespace Model;

use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;

class ContentSecurity extends DatabaseTable {

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
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 9;
        $colContentType->Name = "GroupId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 9;
        $colContentType->Name = "ObjectId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->IsNull = false;
        $colContentType->Length = 50;
        $colContentType->Name = "SecurityType";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 1;
        $colContentType->Name = "Value";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
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
