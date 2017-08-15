<?php
namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class EETCzechRepublic  extends DatabaseTable{
    public $PluginName;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "EETCzechRepublic";
    }
    
    
    
    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue =0;
        $colLangName->IsNull = false;
        $colLangName->Length = 9;
        $colLangName->Name ="ObjectId";
        $colLangName->Type = "INTEGER";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
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
