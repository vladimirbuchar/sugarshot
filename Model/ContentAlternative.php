<?php

namespace Model;

use Types\DataTableColumn;

class ContentAlternative extends DatabaseTable  implements \Inteface\iDataTable{

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
        $this->AddColumn(new DataTableColumn("ContentId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("AlternativeContentId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("UserGroupId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        
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
