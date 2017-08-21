<?php

namespace Model;

use Types\DataTableColumn;

class UserGroupsModules  extends DatabaseTable implements \Inteface\iDataTable{
    public $UserGroupId;
    public $ModuleId;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroupsModules";
        $this->SetSelectColums(array("UserGroupId","ModuleId"));
        $this->SetDefaultSelectColumns();
    }
    
    
            


    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("UserGroupId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("ModuleId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        
        
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
