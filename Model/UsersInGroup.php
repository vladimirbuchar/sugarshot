<?php
namespace Model;

use Types\DataTableColumn;

class UsersInGroup  extends DatabaseTable implements \Inteface\iDataTable{
    public $UserId;
    public $GroupId;
    public $IsMainGroup;

    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UsersInGroup";
        $this->SetSelectColums(array("UserId","GroupId","IsMainGroup"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("UserId", \Types\DataColumnsTypes::INTEGER, 0, FALSE, 9));
        $this->AddColumn(new DataTableColumn("GroupId", \Types\DataColumnsTypes::INTEGER, 0, FALSE, 9));
        $this->AddColumn(new DataTableColumn("IsMainGroup", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        
         
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
