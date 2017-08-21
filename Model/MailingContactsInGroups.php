<?php
namespace Model;

use Types\DataTableColumn;

class MailingContactsInGroups  extends DatabaseTable implements \Inteface\iDataTable{
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
        $this->AddColumn(new DataTableColumn("ContactId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("GroupId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
    }
    
    public function SetValidate($mode = false) {
        
    }

    public function InsertDefaultData() {
        $this->Setup();
    }
    public function TableMigrate()
    {
        
        
    }
    public function TableExportSettings()
    {
        
    }

}
