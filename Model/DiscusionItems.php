<?php

namespace Model;

use Types\RuleType;
use Types\DataTableColumn;

class DiscusionItems extends DatabaseTable  implements \Inteface\iDataTable{

    public $SubjectDiscusion;
    public $TextDiscusion;
    public $UserId; 
    public $ShowUserName;
    public $DateTime;
    public $UserIp;
    public $IsLast;
    public $VersionId;
    public $ParentIdDiscusion;
    public $DiscusionId;
    public function __construct() {
        parent::__construct();
        $this->ObjectName = "DiscusionItems";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("SubjectDiscusion", "TextDiscusion", "UserId", "ShowUserName", "DateTime", "UserIp", "IsLast", "VersionId", "ParentIdDiscusion", "DiscusionId"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("DiscusionId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("VersionId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("ParentIdDiscusion", \Types\DataColumnsTypes::INTEGER, 0, false, 11));
        $this->AddColumn(new DataTableColumn("IsLast", \Types\DataColumnsTypes::BOOLEAN, false, false, 1));
        $this->AddColumn(new DataTableColumn("SubjectDiscusion", \Types\DataColumnsTypes::VARCHAR, "", false, 255));
        $this->AddColumn(new DataTableColumn("TextDiscusion", \Types\DataColumnsTypes::TEXT, "", false));
        $this->AddColumn(new DataTableColumn("UserId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("ShowUserName", \Types\DataColumnsTypes::VARCHAR, "", false, 255));
        $this->AddColumn(new DataTableColumn("DateTime", \Types\DataColumnsTypes::INTEGER, "", false, 20));
        $this->AddColumn(new DataTableColumn("UserIp", \Types\DataColumnsTypes::VARCHAR, "", false, 255));
    }

    public function InsertDefaultData() {
        $this->Setup();
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("UserIp", RuleType::USERIP);
        $this->SetValidateRule("DateTime", RuleType::ACTUALDATETIME);
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

}
