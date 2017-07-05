<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserDomainsAutoComplete  extends DatabaseTable{
    public $DomainItemId;
    public $Value;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsAutoComplete";
        $this->SetSelectColums(array("DomainItemId","Value"));
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
    }
    */
    public function GetItemAutoComplected($itemId)
    {
        return $this->SelectByCondition("DomainItemId = $itemId AND Deleted = 0");
    }
    
            
    
    public function OnCreateTable() {
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue ="";
        $colWebName->IsNull = true;
     
        $colWebName->Name ="Value";
        $colWebName->Type = "TEXT";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);
        
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue ="";
        $colWebName->IsNull = true;
        $colWebName->Name ="DomainItemId";
        $colWebName->Type = "INT";
        $colWebName->Length = 9;
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);
        
        
    }
    
    

    public function InsertDefaultData() {
        $this->Setup($this);
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
