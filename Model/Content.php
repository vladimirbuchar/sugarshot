<?php

namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class Content extends DatabaseTable implements \Inteface\iDataTable {

    public $ContentType;
    public $ParentId;
    public $NoIncludeSearch;
    public $Identificator;
    public $DomainId;
    public $TemplateId;
    public $GalleryId;
    public $GallerySettings;
    public $DiscusionSettings;
    public $DiscusionId;
    public $Sort;
    public $UploadedFileType;
    public $FormId;
    public $NoChild;
    public $UseTemplateInChild;
    public $ChildTemplateId;
    public $CopyDataToChild;
    public $ActivatePager;
    public $FirstItemLoadPager;
    public $NextItemLoadPager;
    public $Owner;
    public $Inquery;
    public $NoLoadSubItems;
    public $LastVisited;
    public $SaveToCache;
    public $SortRule;

    public function __construct() {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "Content";
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ParentId", "NoIncludeSearch", "Identificator", "DomainId", "TemplateId", "GalleryId", "GallerySettings", "DiscusionSettings", "DiscusionId", "Sort", "UploadedFileType", "FormId", "NoChild", "UseTemplateInChild", "ChildTemplateId", "CopyDataToChild", "ActivatePager", "FirstItemLoadPager", "NextItemLoadPager", "Owner", "Inquery", "NoLoadSubItems", "LastVisited", "SaveToCache", "SortRule", "ContentType"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ContentType", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("ParentId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("NoIncludeSearch", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1));
        $this->AddColumn(new DataTableColumn("Identificator", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("DomainId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("TemplateId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("GalleryId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("GallerySettings", \Types\DataColumnsTypes::INTEGER,0,true,9));
        $this->AddColumn(new DataTableColumn("DiscusionSettings", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("DiscusionId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("NoChild", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1));
        $this->AddColumn(new DataTableColumn("UseTemplateInChild", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1));
        $this->AddColumn(new DataTableColumn("Sort", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("UploadedFileType", \Types\DataColumnsTypes::VARCHAR, "", true, 100));
        $this->AddColumn(new DataTableColumn("FormId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("ChildTemplateId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("CopyDataToChild", \Types\DataColumnsTypes::BOOLEAN,0));
        $this->AddColumn(new DataTableColumn("ActivatePager", \Types\DataColumnsTypes::BOOLEAN, 0));
        $this->AddColumn(new DataTableColumn("FirstItemLoadPager", \Types\DataColumnsTypes::INTEGER, 0));
        $this->AddColumn(new DataTableColumn("NextItemLoadPager", \Types\DataColumnsTypes::INTEGER, 0));
        $this->AddColumn(new DataTableColumn("Owner", \Types\DataColumnsTypes::INTEGER, 0));
        $this->AddColumn(new DataTableColumn("Inquery", \Types\DataColumnsTypes::INTEGER, 0));
        $this->AddColumn(new DataTableColumn("NoLoadSubItems", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1));
        $this->AddColumn(new DataTableColumn("LastVisited", \Types\DataColumnsTypes::DATETIME, ""));
        $this->AddColumn(new DataTableColumn("SaveToCache", \Types\DataColumnsTypes::BOOLEAN, 0));
        $this->AddColumn(new DataTableColumn("SortRule", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
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
