<?php

namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
 

class SendEmails  extends DatabaseTable{
    public $MailId;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "SendEmails";
        $this->SetSelectColums(array("MailId"));
        $this->SetDefaultSelectColumns();
    }
    
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
