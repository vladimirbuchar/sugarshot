<?php
namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
class Plugins  extends DatabaseTable{
    public $PluginName;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Plugins";
    }
    
    
    
    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length = 50;
        $colLangName->Name ="PluginName";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
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
