<?php
namespace Model;

use Types\DataTableColumn;

class UserGroupsWeb  extends DatabaseTable implements \Inteface\iDataTable{
    public $UserGroupId;
    public $UserWebId;
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroupsWeb";
        $this->SetSelectColums(array("UserGroupId","UserWebId"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        
        $this->AddColumn(new DataTableColumn("UserGroupId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("UserWebId", \Types\DataColumnsTypes::INTEGER, 0, true, 9)); 
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
