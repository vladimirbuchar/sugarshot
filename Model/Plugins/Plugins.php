<?php
namespace Model;

use Types\DataTableColumn;

class Plugins  extends DatabaseTable implements \Inteface\iDataTable{
    public $PluginName;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Plugins";
    }
    
    
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("PluginName", \Types\DataColumnsTypes::VARCHAR, "", false, 50) );
    }
    

    public function InsertDefaultData() {

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
