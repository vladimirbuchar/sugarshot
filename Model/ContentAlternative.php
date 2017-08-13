<?php

namespace Model;

use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;

class ContentAlternative extends DatabaseTable {

    public $ContentId;
    public $AlternativeContentId;
    public $UserGroupId;

    public function __construct() {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentAlternative";
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ContentId", "AlternativeContentId", "UserGroupId"));
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
        $colContentType->Length = 9;
        $colContentType->Name = "AlternativeContentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 9;
        $colContentType->Name = "UserGroupId";
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
