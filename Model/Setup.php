<?php
namespace Model;
use Dibi;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
class Setup  extends DatabaseTable{
    public $VersionId;
    public $ShowVersionName;
    //private static $_instance = null;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Setup";
        $this->SetSelectColums(array("VersionId","ShowVersionName"));
        $this->SetDefaultSelectColumns();
    }
    /*
    public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }**/
    
    public function IsInstaled()
    {
        $empty = $this->TableIsEmpty();
        if ($empty) return false;
        return true;
    }
    
    
    
    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Name ="VersionId";
        $colLangName->Type = "INTEGER";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue ="";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Length = 50;
        $colLangIdentificator->Name ="ShowVersionName";
        $colLangIdentificator->Type = "varchar";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
        

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
