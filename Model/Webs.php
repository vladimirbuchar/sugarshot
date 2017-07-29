<?php

namespace Model;

use Dibi;
use Utils\ArrayUtils;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class Webs extends DatabaseTable {

    public $WebName;
    public $SmallHeight;
    public $SmallWidth;
    public $MediumHeight;
    public $MediumWidth;
    public $BigHeight;
    public $BigWidth;
    public $WebPrivileges;
    public $AdminUserActive;
    public $UserEmailActivate;
    public $EmailUserLogin;
    public $BlockSendEmails;
    public $BlockAdmin;
    public $UseHttps;
    public $WebIpRestrictionAll;
    public $WebIpRestrictionAceptIp;
    public $WebIpRestrictionBlockIp;
    public $WebIpAddress;
    public $AdminIpRestrictionAll;
    public $AdminIpRestrictionAceptIp;
    public $AdminIpRestrictionBlockIp;
    public $AdminIpAddress;
    public $GenerateAjaxLink;
    public $DefaultFramework;
    public $AfterLoginAction = "";
    public $AfterLoginUrl = "";
    public $SendInfoEmailToAdmin;
    public $AdminInfoEmail;
    public $AdmiInfoMailId;
    public $SendInfoEmailToUser;
    public $UserInfoEmailFrom;
    public $UserInfoMailId;
    public $CookiesAccept;
    public $RobotsTxt;
    //private static $_instance = null;

    public function __construct() {
        parent::__construct();
        $this->ObjectName = "Webs";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("WebName","SmallHeight","SmallWidth","MediumHeight","MediumWidth","BigHeight","BigWidth","WebPrivileges","AdminUserActive","UserEmailActivate","EmailUserLogin","BlockSendEmails",
"BlockAdmin","UseHttps","WebIpRestrictionAll","WebIpRestrictionAceptIp","WebIpRestrictionBlockIp","WebIpAddress","AdminIpRestrictionAll","AdminIpRestrictionAceptIp","AdminIpRestrictionBlockIp",
"AdminIpAddress","GenerateAjaxLink","DefaultFramework","AfterLoginUrl","SendInfoEmailToAdmin","AdminInfoEmail","AdmiInfoMailId","SendInfoEmailToUser","UserInfoEmailFrom","UserInfoMailId","CookiesAccept,RobotsTxt"));
        $this->SetDefaultSelectColumns();
    }
    /**
    public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/

    public function OnCreateTable() {
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 50;
        $colWebName->Name = "WebName";
        $colWebName->Type = "varchar";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = true;
        $colWebName->Name = "WebPrivileges";
        $colWebName->Type = "TEXT";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

     

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 9;
        $colWebName->Name = "SmallHeight";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 9;
        $colWebName->Name = "SmallWidth";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 9;
        $colWebName->Name = "MediumHeight";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 9;
        $colWebName->Name = "MediumWidth";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 9;
        $colWebName->Name = "BigHeight";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 9;
        $colWebName->Name = "BigWidth";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "AdminUserActive";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "UserEmailActivate";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "EmailUserLogin";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "BlockSendEmails";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "BlockAdmin";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "UseHttps";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "WebIpRestrictionAll";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "WebIpRestrictionAceptIp";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "WebIpRestrictionBlockIp";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Name = "WebIpAddress";
        $deletedColumn->Type = "TEXT";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "AdminIpRestrictionAll";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "AdminIpRestrictionAceptIp";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        //
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "AdminIpRestrictionBlockIp";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = "";
        $deletedColumn->Name = "AdminIpAddress";
        $deletedColumn->Type = "TEXT";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "GenerateAjaxLink";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);

        //
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 50;
        $colWebName->Name = "AfterLoginAction";
        $colWebName->Type = "varchar";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->IsNull = false;
        $colWebName->Length = 50;
        $colWebName->Name = "AfterLoginUrl";
        $colWebName->Type = "varchar";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = 0;
        $colWebName->Name = "SendInfoEmailToAdmin";
        $colWebName->Type = "BOOLEAN";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->Name = "AdminInfoEmail";
        $colWebName->Type = "varchar";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = 0;
        $colWebName->Name = "AdmiInfoMailId";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = 0;
        $colWebName->Name = "SendInfoEmailToUser";
        $colWebName->Type = "BOOLEAN";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->Name = "UserInfoEmailFrom";
        $colWebName->Type = "varchar";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = 0;
        $colWebName->Name = "UserInfoMailId";
        $colWebName->Type = "INTEGER";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = 0;
        $colWebName->Name = "CookiesAccept";
        $colWebName->Type = "BOOLEAN";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);
        
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->Name = "DefaultFramework";
        $colWebName->Type = "varchar";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);
        //RobotsTxt
        
        $colWebName = new DataTableColumn();
        $colWebName->DefaultValue = "";
        $colWebName->Name = "RobotsTxt";
        $colWebName->Type = "TEXT";
        $colWebName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWebName);

        
        //
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("WebName", RuleType::$NoEmpty, $this->GetWord("word88"));
        $this->SetValidateRule("WebName", RuleType::$Unique, $this->GetWord("word89"));
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }
    

}
