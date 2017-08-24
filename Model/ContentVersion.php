<?php

namespace Model;

use Types\RuleType;
use Types\DataTableColumn;

class ContentVersion extends DatabaseTable  implements \Inteface\iDataTable{

    public $ContentId;
    public $Name;
    public $IsActive;
    public $IsLast;
    public $Author;
    public $SeoUrl;
    public $Template;
    public $AvailableOverSeoUrl;
    public $Data;
    public $Header;
    public $ActiveTo;
    public $ActiveFrom;
    public $Date;
    public $PublishUser;
    public $ContentSettings;

    public function __construct() {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentVersion";
        $this->MultiLang = true;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ContentId", "Name", "IsActive", "IsLast", "Author", "SeoUrl", "Template", "AvailableOverSeoUrl", "Data", "Header", "ActiveTo", "ActiveFrom", "Date", "PublishUser", "ContentSettings"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ContentId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("Name", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("IsActive", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1));
        $this->AddColumn(new DataTableColumn("IsLast", \Types\DataColumnsTypes::BOOLEAN, true, false, 1));
        $this->AddColumn(new DataTableColumn("Author", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("PublishUser", \Types\DataColumnsTypes::INTEGER, 0, false, 0));
        $this->AddColumn(new DataTableColumn("SeoUrl", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("Template", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("AvailableOverSeoUrl", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("Data", \Types\DataColumnsTypes::LONGTEXT, "", true));
        $this->AddColumn(new DataTableColumn("Header", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("ActiveFrom", \Types\DataColumnsTypes::DATETIME, "", true));
        $this->AddColumn(new DataTableColumn("ActiveTo", \Types\DataColumnsTypes::DATETIME, "", true));
        $this->AddColumn(new DataTableColumn("Date", \Types\DataColumnsTypes::DATETIME, "", true));
        $this->AddColumn(new DataTableColumn("ContentSettings", \Types\DataColumnsTypes::TEXT, "", true));
    }

    public function InsertDefaultData() {
        $this->Setup();
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("Data", RuleType::REMOVEENTITY);
    }

}
