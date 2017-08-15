<?php

namespace Model;

use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class ContentData extends DatabaseTable {

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


        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 9;
        $colContentType->Name = "ContentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 255;
        $colContentType->Name = "ItemName";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "Value";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "ValueNoHtml";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 9;
        $colContentType->Name = "ItemId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
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
