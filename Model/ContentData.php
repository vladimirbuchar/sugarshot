<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class ContentData  extends DatabaseTable{
    
    public $ContentId;
    public $ItemName;
    public $Value;
    public $ValueNoHtml;
    public $ItemId;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentData";
        $this->MultiWeb= true;
        $this->MultiLang=true;
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
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="ContentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 255;
        $colContentType->Name ="ItemName";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue ="";
        $colContentType->Name ="Value";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue ="";
        $colContentType->Name ="ValueNoHtml";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="ItemId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        
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
