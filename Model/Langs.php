<?php
namespace Model;
use Types\RuleType;
use Types\DataTableColumn;
use Types\DatabaseActions;

class Langs  extends DatabaseTable implements \Inteface\iDataTable{
    public $LangName;
    public $RootUrl;
    public $Title;
    public $Keywords;
    public $Description;
    public $CategoryPage;
    public $LangIdentificator;
    
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Langs";
        $this->MultiWeb = true;
        $this->SaveHistory = true;
        $this->SetSelectColums(array("LangName","RootUrl","Title","Keywords","Description","CategoryPage","LangIdentificator"));
        $this->SetDefaultSelectColumns();
    }
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("LangName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("RootUrl", \Types\DataColumnsTypes::VARCHAR, "", false,50));
        $this->AddColumn(new DataTableColumn("Title", \Types\DataColumnsTypes::TEXT));
        $this->AddColumn(new DataTableColumn("Keywords", \Types\DataColumnsTypes::TEXT));
        $this->AddColumn(new DataTableColumn("Description", \Types\DataColumnsTypes::TEXT));
        $this->AddColumn(new DataTableColumn("CategoryPage", \Types\DataColumnsTypes::TEXT));
        $this->AddColumn(new DataTableColumn("LangIdentificator", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
    }
    
    
    
    
    

    public function InsertDefaultData() {
        $this->Setup();
    }
    
    public function TableMigrate()
    {
        $this->RunTableMigrate("ALTER TABLE  `Langs` CHANGE  `Title`  `Title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE  `Keywords`  `Keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE  `Description`  `Description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE  `CategoryPage`  `CategoryPage` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;");
        
    }

    

    public function SetValidate($mode = false) {
        $this->SetValidateRule("LangName", RuleType::$NoEmpty,$this->GetWord("word85"));
        $this->SetValidateRule("RootUrl", RuleType::$NoEmpty,  $this->GetWord("word86"));
        $this->SetValidateRule("RootUrl", RuleType::$Unique,$this->GetWord("word87"));
        $this->SetValidateRule("LangIdentificator", RuleType::$Unique,$this->GetWord("word87"));
        $this->SetCallModelFunction("Langs","CreateLangFolder","",DatabaseActions::INSERT);   
    }
    public function TableExportSettings()
    {
        
    }
}
