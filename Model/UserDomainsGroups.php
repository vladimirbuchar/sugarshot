<?php

namespace Model;

use Types\DataTableColumn;

class UserDomainsGroups  extends DatabaseTable implements \Inteface\iDataTable{
    public $DomainId;
    public $GroupName;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsGroups";
        $this->SetSelectColums(array("DomainId","GroupName"));
        $this->SetDefaultSelectColumns();
    }
    
        
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("DomainId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("GroupName", \Types\DataColumnsTypes::VARCHAR, "", FALSE, 50));
        
        
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
