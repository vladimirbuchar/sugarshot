<?php

namespace Model;
use Dibi;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
class Content  extends DatabaseTable{
    
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
    //private static $_instance = null;
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "Content";
        $this->MultiWeb= true;
        $this->SetSelectColums(array("ParentId","NoIncludeSearch","Identificator","DomainId","TemplateId","GalleryId","GallerySettings","DiscusionSettings","DiscusionId","Sort","UploadedFileType","FormId","NoChild","UseTemplateInChild","ChildTemplateId","CopyDataToChild","ActivatePager","FirstItemLoadPager","NextItemLoadPager","Owner","Inquery","NoLoadSubItems","LastVisited","SaveToCache","SortRule","ContentType"));
        $this->SetDefaultSelectColumns();
    }
    /*public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/
    
    
    
    public function OnCreateTable() {
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue ="";
        $colContentType->IsNull = false;
        $colContentType->Length = 50;
        $colContentType->Name ="ContentType";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="ParentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 1;
        $colContentType->Name ="NoIncludeSearch";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 255;
        $colContentType->Name ="Identificator";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="DomainId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="TemplateId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="GalleryId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="GallerySettings";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="DiscusionSettings";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="DiscusionId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "NoChild";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "UseTemplateInChild";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="Sort";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Length = 100;
        $deletedColumn->Name = "UploadedFileType";
        $deletedColumn->Type = "varchar";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="FormId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="ChildTemplateId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Name ="CopyDataToChild";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Name ="ActivatePager";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Name ="FirstItemLoadPager";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Name ="NextItemLoadPager";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Name ="Owner";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        //Inquery
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Name ="Inquery";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);        
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Name ="NoLoadSubItems";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "LastVisited";
        $colContentType->Type = "DATETIME";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        //SaveToCache
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Name = "SaveToCache";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Length = 50;
        $deletedColumn->Name = "SortRule";
        $deletedColumn->Type = "varchar";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
    }
    
    public function InsertDefaultData() {
        
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