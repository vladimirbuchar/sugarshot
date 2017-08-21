<?php

namespace Model;

use Types\DataTableColumn;

class Cache extends DatabaseTable implements \Inteface\iDataTable {

    public $HtmlCache;
    public $SeoUrl;
    public $ObjectId;
    public $LangId;
    public $UserGroupId;
    public $CacheTime;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "Cache";
        $this->SetSelectColums(array("HtmlCache", "SeoUrl", "ObjectId", "LangId", "UserGroupId", "CacheTime"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("HtmlCache", \Types\DataColumnsTypes::LONGTEXT));
        $this->AddColumn(new DataTableColumn("SeoUrl", \Types\DataColumnsTypes::VARCHAR));
        $this->AddColumn(new DataTableColumn("ObjectId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("LangId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("UserGroupId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("CacheTime", \Types\DataColumnsTypes::DATETIME, "", true));
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
