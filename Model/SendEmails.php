<?php

namespace Model;

use Types\DataTableColumn;

class SendEmails  extends DatabaseTable implements \Inteface\iDataTable{
    public $MailId;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "SendEmails";
        $this->SetSelectColums(array("MailId"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("MailId", \Types\DataColumnsTypes::INTEGER, 0, true, 9)); 
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
