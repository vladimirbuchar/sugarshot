<?php
namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class Cache  extends DatabaseTable{
    public $HtmlCache;
    public $SeoUrl; 
    public $ObjectId;
    public $LangId;
    public $UserGroupId;
    public $CacheTime;
    
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Cache";
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
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->Name ="HtmlCache";
        $colLangName->Type = "LONGTEXT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->Name ="SeoUrl";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue ="";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Length = 9;
        $colLangIdentificator->Name ="ObjectId";
        $colLangIdentificator->Type = "INT";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
        
        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue ="";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Length = 9;
        $colLangIdentificator->Name ="LangId";
        $colLangIdentificator->Type = "INT";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
        
        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue ="";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Length = 9;
        $colLangIdentificator->Name ="UserGroupId";
        $colLangIdentificator->Type = "INT";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
        
        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue ="";
        $colLangIdentificator->IsNull = true;
        $colLangIdentificator->Name ="CacheTime";
        $colLangIdentificator->Type = "DATETIME";
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
