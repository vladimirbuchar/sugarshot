<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class UserDomainsItems  extends DatabaseTable{
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
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="DomainId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 50;
        $colShowName->Name ="ShowName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colIdentificator = new DataTableColumn();
        $colIdentificator->DefaultValue ="";
        $colIdentificator->IsNull = false;
        $colIdentificator->Length = 50;
        $colIdentificator->Name ="Identificator";
        $colIdentificator->Type = "varchar";
        $colIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colIdentificator);
        
        $colType = new DataTableColumn();
        $colType->DefaultValue ="";
        $colType->IsNull = false;
        $colType->Length = 50;
        $colType->Name ="Type";
        $colType->Type = "varchar";
        $colType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colType);
        
        $colShowInAdmin = new DataTableColumn();
        $colShowInAdmin->IsNull = true;
        $colShowInAdmin->Length = 1;
        $colShowInAdmin->Name ="ShowInAdmin";
        $colShowInAdmin->Type = "BOOLEAN";
        $colShowInAdmin->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowInAdmin);
        
        $colShowInWeb = new DataTableColumn();
        $colShowInWeb->IsNull = true;
        $colShowInWeb->Length = 1;
        $colShowInWeb->Name ="ShowInWeb";
        $colShowInWeb->Type = "BOOLEAN";
        $colShowInWeb->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowInWeb);
        
        $colRequired = new DataTableColumn();
        $colRequired->IsNull = true;
        $colRequired->Length = 1;
        $colRequired->Name ="Required";
        $colRequired->Type = "BOOLEAN";
        $colRequired->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRequired);
        
        $colDefaultValue = new DataTableColumn();
        $colDefaultValue->IsNull = true;
        $colDefaultValue->Length = 50;
        $colDefaultValue->Name ="DefaultValue";
        $colDefaultValue->Type = "varchar";
        $colDefaultValue->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDefaultValue);
        
        $colMaxLength = new DataTableColumn();
        $colMaxLength->IsNull = true;
        $colMaxLength->Length = 50;
        $colMaxLength->Name ="MaxLength";
        $colMaxLength->Type = "varchar";
        $colMaxLength->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colMaxLength);
        
        $colMinLength = new DataTableColumn();
        $colMinLength->IsNull = true;
        $colMinLength->Length = 50;
        $colMinLength->Name ="MinLength";
        $colMinLength->Type = "varchar";
        $colMinLength->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colMinLength);         
        
        $colMinLength = new DataTableColumn();
        $colMinLength->IsNull = true;
        $colMinLength->Name ="ValueList";
        $colMinLength->Type = "TEXT";
        $colMinLength->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colMinLength);         
        
        $colRequired = new DataTableColumn();
        $colRequired->IsNull = true;
        $colRequired->Length = 1;
        $colRequired->Name ="ShowInAdminReadOnly";
        $colRequired->Type = "BOOLEAN";
        $colRequired->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRequired);


        $colRequired = new DataTableColumn();
        $colRequired->IsNull = true;
        $colRequired->Length = 1;
        $colRequired->Name ="ShowInWebReadOnly";
        $colRequired->Type = "BOOLEAN";
        $colRequired->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRequired);
        
        $colMinLength = new DataTableColumn();
        $colMinLength->IsNull = true;
        $colMinLength->Name ="Validate";
        $colMinLength->Type = "TEXT";
        $colMinLength->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colMinLength);     
        
        $colMinLength = new DataTableColumn();
        $colMinLength->IsNull = true;
        $colMinLength->Name ="CssClass";
        $colMinLength->Type = "TEXT";
        $colMinLength->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colMinLength);     
        
        $colMinLength = new DataTableColumn();
        $colMinLength->IsNull = true;
        $colMinLength->Name ="MoreHtmlAtribut";
        $colMinLength->Type = "TEXT";
        $colMinLength->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colMinLength);     
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="Domain";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "UniqueValue";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "NoUpdate";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Length = 255;
        $deletedColumn->Name = "XmlSettings";
        $deletedColumn->Type = "varchar";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "AddCDATA";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "ShowOnlyDetail";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Length = 255;
        $deletedColumn->Name = "DomainSettings";
        $deletedColumn->Type = "varchar";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        ///FiltrSettings
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Length = 255;
        $deletedColumn->Name = "FiltrSettings";
        $deletedColumn->Type = "varchar";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "Autocomplete";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "AddToSort";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        //OnChangeEvent
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Name = "OnChangeEvent";
        $deletedColumn->Type = "varchar";
        $deletedColumn->Length = 255;
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "ValueForAllLangues";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "GenerateHiddenInput";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
    }
    

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("ShowName", RuleType::$NoEmpty,$this->GetWord("word227"));
        $this->SetValidateRule("Identificator", RuleType::$NoEmpty,$this->GetWord("word228"));
        $this->SetValidateRule("Type", RuleType::$NoEmpty,$this->GetWord("word230"));
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
