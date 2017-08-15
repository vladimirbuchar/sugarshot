<?php

namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;


class Users  extends DatabaseTable{
    public $UserName;
    public $FirstName;
    public $LastName;
    public $UserEmail;
    public $IsBadLogin = false;
    public $BlockDiscusion = false;
    public $IsActive = false;
    public $DefaultLang;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Users";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("UserName","FirstName","LastName","UserEmail","BlockDiscusion","IsActive","DefaultLang"));
        $this->SetDefaultSelectColumns();
        
    }
    
    public function OnCreateTable() {
        
        $colUserName = new DataTableColumn();
        $colUserName->DefaultValue ="";
        $colUserName->IsNull = false;
        $colUserName->Length = 50;
        $colUserName->Name ="UserName";
        $colUserName->Type = "varchar";
        $colUserName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserName);
        
        $colUserPassword = new DataTableColumn();
        $colUserPassword->DefaultValue ="";
        $colUserPassword->IsNull = false;
        $colUserPassword->Length = 50;
        $colUserPassword->Name ="UserPassword";
        $colUserPassword->Type = "varchar";
        $colUserPassword->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserPassword);
        
        $colUserFirstName = new DataTableColumn();
        $colUserFirstName->DefaultValue ="";
        $colUserFirstName->IsNull = true;
        $colUserFirstName->Length = 50;
        $colUserFirstName->Name ="FirstName";
        $colUserFirstName->Type = "varchar";
        $colUserFirstName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserFirstName);
        
        $colUserLastName = new DataTableColumn();
        $colUserLastName->DefaultValue ="";
        $colUserLastName->IsNull = true;
        $colUserLastName->Length = 50;
        $colUserLastName->Name ="LastName";
        $colUserLastName->Type = "varchar";
        $colUserLastName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserLastName);
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue ="";
        $colUserEmail->IsNull = true;
        $colUserEmail->Length = 50;
        $colUserEmail->Name ="UserEmail";
        $colUserEmail->Type = "varchar";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue = false;
        $colUserEmail->IsNull = true;
        $colUserEmail->Name ="BlockDiscusion";
        $colUserEmail->Type = "BOOLEAN";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
       
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue = false;
        $colUserEmail->IsNull = true;
        $colUserEmail->Name ="IsActive";
        $colUserEmail->Type = "BOOLEAN";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue ="";
        $colUserEmail->IsNull = true;
        $colUserEmail->Length = 50;
        $colUserEmail->Name ="DefaultLang";
        $colUserEmail->Type = "varchar";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
        
        
        

    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("UserName", RuleType::$NoEmpty,$this->GetWord("word480"));
        $this->SetValidateRule("UserName", RuleType::$Unique,$this->GetWord("word481"));
        $this->SetValidateRule("FirstName", RuleType::$NoEmpty,$this->GetWord("word482"));
        $this->SetValidateRule("LastName", RuleType::$NoEmpty,$this->GetWord("word483"));
        $this->SetValidateRule("UserEmail", RuleType::$NoEmpty,$this->GetWord("word484"));
        $this->SetValidateRule("UserEmail", RuleType::$Unique,$this->GetWord("word485"));
        if ($mode)
        {
            $this->SetValidateRule("UserPassword", RuleType::$Hash);
            $this->SetValidateRule("UserPassword", RuleType::$NoEmpty,$this->GetWord("word486"));
        }
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
