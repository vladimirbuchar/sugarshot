<?php

namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class MailingContacts  extends DatabaseTable{
    public $Email;
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "MailingContacts";
        $this->MultiLang = false;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("Email"));
        $this->SetDefaultSelectColumns();
    }
    

    
    public function OnCreateTable() {
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 255;
        $colUserGroupId->Name ="Email";
        $colUserGroupId->Type = "varchar";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
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
