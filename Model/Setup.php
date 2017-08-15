<?php
namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class Setup  extends DatabaseTable{
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
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Name ="VersionId";
        $colLangName->Type = "INTEGER";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue ="";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Length = 50;
        $colLangIdentificator->Name ="ShowVersionName";
        $colLangIdentificator->Type = "varchar";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
        

    }
    

    public function InsertDefaultData() {
        $this->Setup($this);
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
