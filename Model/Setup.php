<?php
namespace Model;

use Types\DataTableColumn;

class Setup  extends DatabaseTable implements \Inteface\iDataTable{
    public $VersionId;
    public $ShowVersionName;
    
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Setup";
        $this->SetSelectColums(array("VersionId","ShowVersionName"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("VersionId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("ShowVersionName", \Types\DataColumnsTypes::VARCHAR, "", FALSE, 50));
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
