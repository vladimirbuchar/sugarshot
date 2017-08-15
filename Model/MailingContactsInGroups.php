<?php

namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class MailingContactsInGroups  extends DatabaseTable{
    public $ContactId;
    public $GroupId;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "MailingContactsInGroups";
        $this->MultiLang = false;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ContactId","GroupId"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 9;
        $colUserGroupId->Name ="ContactId";
        $colUserGroupId->Type = "INT";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 9;
        $colUserGroupId->Name ="GroupId";
        $colUserGroupId->Type = "INT";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
    }
    
    public function SetValidate($mode = false) {
        
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }
    public function TableMigrate()
    {
        
        
    }
    public function TableExportSettings()
    {
        
    }

}
