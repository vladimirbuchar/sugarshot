<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class SendEmails  extends DatabaseTable{
    public $MailId;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "SendEmails";
        $this->SetSelectColums(array("MailId"));
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
    
    
    public function OnCreateTable() {
        $colDateEvent = new DataTableColumn();
        $colDateEvent->DefaultValue ="";
        $colDateEvent->IsNull = true;
        $colDateEvent->Name ="MailId";
        $colDateEvent->Type = "INTEGER";
        $colDateEvent->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDateEvent);
        
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
