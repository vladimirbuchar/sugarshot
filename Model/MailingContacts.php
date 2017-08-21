<?php

namespace Model;

use Types\DataTableColumn;

class MailingContacts  extends DatabaseTable implements \Inteface\iDataTable{
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
        $this->AddColumn(new DataTableColumn("Email", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
    }
    
    public function InsertDefaultData() {
        $this->Setup();
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
