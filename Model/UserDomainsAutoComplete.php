<?php

namespace Model;

use Types\DataTableColumn;

class UserDomainsAutoComplete  extends DatabaseTable implements \Inteface\iDataTable{
    public $DomainItemId;
    public $Value;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsAutoComplete";
        $this->SetSelectColums(array("DomainItemId","Value"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("Value", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("DomainItemId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        
        
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
