<?php

namespace Model;

use Utils\ArrayUtils;
use Utils\StringUtils;
use Types\ContentTypes;
use HtmlComponents\HtmlTable;
use HtmlComponents\HtmlTableTd;
use HtmlComponents\HtmlTableTh;
use HtmlComponents\HtmlTableTr;
use HtmlComponents\Link;
use HtmlComponents\FontAwesome;
use Types\RuleType;
use Types\PrivilegesType;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
use Types\xWebExceptions;
use Types\LinkType;
use Utils\Mail;
use Utils\Utils;
use Kernel;
use Utils\Image;

class ContentVersion extends DatabaseTable {

    public $ContentId;
    public $Name;
    public $IsActive;
    public $IsLast;
    public $Author;
    public $SeoUrl;
    public $Template;
    public $AvailableOverSeoUrl;
    public $Data;
    public $Header;
    public $ActiveTo;
    public $ActiveFrom;
    public $Date;
    public $PublishUser;
    public $ContentSettings;

    public function __construct() {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentVersion";
        $this->MultiLang = true;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ContentId", "Name", "IsActive", "IsLast", "Author", "SeoUrl", "Template", "AvailableOverSeoUrl", "Data", "Header", "ActiveTo", "ActiveFrom", "Date", "PublishUser", "ContentSettings"));
        $this->SetDefaultSelectColumns();
    }

    public function OnCreateTable() {
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name = "ContentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";

        $colContentType->Length = 255;
        $colContentType->Name = "Name";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = 0;
        $colContentType->Length = 1;
        $colContentType->Name = "IsActive";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = true;
        $colContentType->IsNull = false;
        $colContentType->Length = 1;
        $colContentType->Name = "IsLast";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name = "Author";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        //PublishUser
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name = "PublishUser";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Length = 255;
        $colContentType->Name = "SeoUrl";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Length = 9;
        $colContentType->Name = "Template";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Length = 1;
        $colContentType->Name = "AvailableOverSeoUrl";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "Data";
        $colContentType->Type = "LONGTEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "Header";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "ActiveFrom";
        $colContentType->Type = "DATETIME";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "ActiveTo";
        $colContentType->Type = "DATETIME";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);



        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "Date";
        $colContentType->Type = "DATETIME";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);

        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue = "";
        $colContentType->Name = "ContentSettings";
        $colContentType->Type = "TEXT";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    public function TableMigrate() {
        
    }

    public function TableExportSettings() {
        
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("Data", RuleType::$RemoveEntity);
    }

}
