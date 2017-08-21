<?php
namespace Model;

use Types\DataTableColumn;

class EETCzechRepublic  extends DatabaseTable implements \Inteface\iDataTable{
    public $PluginName;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "EETCzechRepublic";
    }
    
    
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ObjectId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
    }
    

    public function InsertDefaultData() {

    }

    

    public function SetValidate($mode = false) {
        
    }    
    
    public function CreateNewEetRecord($objectId, $price)
    {
        
    }
    
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }
}
