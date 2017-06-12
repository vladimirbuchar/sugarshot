<?php
namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class BadLogins  extends DatabaseTable{
    public $DateEvent;
    public $UserName;
    //private static $_instance = null;
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "BadLogins";
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
        $colDateEvent = new DataTableColumn();
        $colDateEvent->DefaultValue ="";
        $colDateEvent->IsNull = true;
        $colDateEvent->Name ="DateEvent";
        $colDateEvent->Type = "DATETIME";
        $colDateEvent->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDateEvent);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "IsSystem";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->Length = 255;
        $deletedColumn->Name = "UserName";
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
