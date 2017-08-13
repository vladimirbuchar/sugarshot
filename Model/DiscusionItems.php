<?php

namespace Model;

use Dibi;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class DiscusionItems extends DatabaseTable {

    public $SubjectDiscusion;
    public $TextDiscusion;
    public $UserId;
    public $ShowUserName;
    public $DateTime;
    public $UserIp;
    public $IsLast;
    public $VersionId;
    public $ParentIdDiscusion;
    public $DiscusionId;

    //private static $_instance = null;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "DiscusionItems";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("SubjectDiscusion", "TextDiscusion", "UserId", "ShowUserName", "DateTime", "UserIp", "IsLast", "VersionId", "ParentIdDiscusion", "DiscusionId"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 9;
        $colLangName->Name = "DiscusionId";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 9;
        $colLangName->Name = "VersionId";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 9;
        $colLangName->Name = "ParentIdDiscusion";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 1;
        $colLangName->Name = "IsLast";
        $colLangName->Type = "BOOLEAN";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);


        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 255;
        $colLangName->Name = "SubjectDiscusion";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue = "";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Name = "TextDiscusion";
        $colLangIdentificator->Type = "TEXT";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 9;
        $colLangName->Name = "UserId";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 255;
        $colLangName->Name = "ShowUserName";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 20;
        $colLangName->Name = "DateTime";
        $colLangName->Type = "int";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);

        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue = "";
        $colLangName->IsNull = false;
        $colLangName->Length = 255;
        $colLangName->Name = "UserIp";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("UserIp", RuleType::$UserIp);
        $this->SetValidateRule("DateTime", RuleType::$ActualDateTime);
        $this->SetValidateRule("UserId", RuleType::$UserId);
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

}
