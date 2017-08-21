<?php
namespace Model;
use Types\RuleType;
use Types\DataTableColumn;

class Modules  extends DatabaseTable implements \Inteface\iDataTable{
    public $ModuleName;
    public $ModuleControler;
    public $ModuleView;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Modules";
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ModuleName","ModuleControler","ModuleView"));
        $this->SetDefaultSelectColumns();
        
    }
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("ModuleName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("ModuleControler", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("ModuleView", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
    }

    public function InsertDefaultData() {
        $this->Setup();   
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("ModuleName", RuleType::$NoEmpty,$this->GetWord("word150"));
        $this->SetValidateRule("ModuleControler", RuleType::$NoEmpty,$this->GetWord("word151"));
        $this->SetValidateRule("ModuleView", RuleType::$NoEmpty,$this->GetWord("word152"));
        $this->SetCallModelFunction("Modules","SetupModule",array(),DatabaseActions::$Insert);
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }
}
