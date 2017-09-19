<?php

namespace Model;
 use Types\RuleType;
 use Types\DataTableColumn;
 
class UserDomainsItems  extends DatabaseTable implements \Inteface\iDataTable{
    public $DomainId;
    public $ShowName;
    public $Identificator;
    public $Type;
    public $ShowInAdmin;
    public $ShowInWeb;
    public $Required;
    public $DefaultValue;
    public $MaxLength;
    public $MinLength;
    public $ValueList;
    public $ShowInAdminReadOnly;
    public $ShowInWebReadOnly;
    public $Validate;
    public $CssClass;
    public $MoreHtmlAtribut;
    public $Domain;
    public $UniqueValue;
    public $NoUpdate;
    public $XmlSettings;
    public $AddCDATA;
    public $ShowOnlyDetail;
    public $DomainSettings; 
    public $FiltrSettings;
    public $Autocomplete;
    public $AddToSort;
    public $OnChangeEvent; 
    public $ValueForAllLangues; 
    public $GenerateHiddenInput;
    
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsItems";
        $this->ParentColumn = "DomainId";
        $this->SetSelectColums(array("DomainId","ShowName","Identificator","Type","ShowInAdmin","ShowInWeb","Required","DefaultValue","MaxLength","MinLength","ValueList","ShowInAdminReadOnly",
"ShowInWebReadOnly","Validate","CssClass","MoreHtmlAtribut","Domain","UniqueValue","NoUpdate","XmlSettings","AddCDATA","ShowOnlyDetail",
"DomainSettings","FiltrSettings","Autocomplete","AddToSort","OnChangeEvent","ValueForAllLangues,GenerateHiddenInput"));
        $this->SetDefaultSelectColumns();
    }
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("DomainId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("ShowName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("Identificator", \Types\DataColumnsTypes::VARCHAR, "", FALSE, 50));
        $this->AddColumn(new DataTableColumn("Type", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("ShowInAdmin", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("ShowInWeb", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("Required", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("DefaultValue", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
        $this->AddColumn(new DataTableColumn("MaxLength", \Types\DataColumnsTypes::VARCHAR, "", true, 50));
        $this->AddColumn(new DataTableColumn("MinLength", \Types\DataColumnsTypes::VARCHAR, "", true, 50));         
        $this->AddColumn(new DataTableColumn("ValueList", \Types\DataColumnsTypes::TEXT , "", true));         
        $this->AddColumn(new DataTableColumn("ShowInAdminReadOnly", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("ShowInWebReadOnly", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("Validate", \Types\DataColumnsTypes::TEXT, "", true));     
        $this->AddColumn(new DataTableColumn("CssClass", \Types\DataColumnsTypes::TEXT, "", true));     
        $this->AddColumn(new DataTableColumn("MoreHtmlAtribut", \Types\DataColumnsTypes::TEXT, "", true));     
        $this->AddColumn(new DataTableColumn("Domain", \Types\DataColumnsTypes::INTEGER, 0, TRUE, 9));
        $this->AddColumn(new DataTableColumn("UniqueValue", \Types\DataColumnsTypes::BOOLEAN, FALSE, true, 1));
        $this->AddColumn(new DataTableColumn("NoUpdate", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("XmlSettings", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("AddCDATA", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("ShowOnlyDetail", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("DomainSettings", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("FiltrSettings", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("Autocomplete", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("AddToSort", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("OnChangeEvent", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("ValueForAllLangues", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("GenerateHiddenInput", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
    }
    

    public function InsertDefaultData() {
        $this->Setup();
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("ShowName", RuleType::NOEMPTY,$this->GetWord("word227"));
        $this->SetValidateRule("Identificator", RuleType::NOEMPTY,$this->GetWord("word228"));
        $this->SetValidateRule("Type", RuleType::NOEMPTY,$this->GetWord("word230"));
    }
    public function TableMigrate()
    {
//        $this->RunTableMigrate("ALTER TABLE `UserDomainsItems`CHANGE `Domain` `Domain` int(9) NULL DEFAULT '0' AFTER `MoreHtmlAtribut`");
    }
    public function TableExportSettings()
    {
        
    }

}
