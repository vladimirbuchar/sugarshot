<?php

namespace Model;

use Types\DataTableColumn;

class UserDomainsItemsInGroups  extends DatabaseTable implements \Inteface\iDataTable{
    public $ItemId;
    public $GroupId;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsItemsInGroups";
        $this->SetSelectColums(array("ItemId","GroupId"));
        $this->SetDefaultSelectColumns();
        
    }
        
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ItemId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("GroupId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        
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
