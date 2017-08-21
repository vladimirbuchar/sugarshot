<?php

namespace Model;

use Types\RuleType;
use Types\DataTableColumn;

class Webs extends DatabaseTable  implements \Inteface\iDataTable{

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
    public $SiteMapStart;
    public $SiteMapEnd;
    public $SiteMapItemUrl;
    public $SiteMapItemImage;
    public $SiteMapItemVideo;
    public $SiteMapItemStart;
    public $SiteMapItemEnd;
    public function __construct() {
        parent::__construct();
        $this->ObjectName = "Webs";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("WebName","SmallHeight","SmallWidth","MediumHeight","MediumWidth","BigHeight","BigWidth","WebPrivileges","AdminUserActive","UserEmailActivate","EmailUserLogin","BlockSendEmails",
"BlockAdmin","UseHttps","WebIpRestrictionAll","WebIpRestrictionAceptIp","WebIpRestrictionBlockIp","WebIpAddress","AdminIpRestrictionAll","AdminIpRestrictionAceptIp","AdminIpRestrictionBlockIp",
"AdminIpAddress","GenerateAjaxLink","DefaultFramework","AfterLoginUrl","SendInfoEmailToAdmin","AdminInfoEmail","AdmiInfoMailId","SendInfoEmailToUser","UserInfoEmailFrom","UserInfoMailId","CookiesAccept,RobotsTxt,
            SiteMapStart,SiteMapEnd,SiteMapItemImage,SiteMapItemUrl,SiteMapItemVideo","SiteMapItemStart","SiteMapItemEnd"
            ));
        $this->SetDefaultSelectColumns();
    }
    

    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("WebName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("WebPrivileges", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SmallHeight", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("SmallWidth", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("MediumHeight", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("MediumWidth", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("BigHeight", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("BigWidth", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("AdminUserActive", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("UserEmailActivate", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("EmailUserLogin", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("BlockSendEmails", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("BlockAdmin", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("UseHttps", \Types\DataColumnsTypes::BOOLEAN, true, true, 1));
        $this->AddColumn(new DataTableColumn("WebIpRestrictionAll", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("WebIpRestrictionAceptIp", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("WebIpRestrictionBlockIp", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("WebIpAddress", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("AdminIpRestrictionAll", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("AdminIpRestrictionAceptIp", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("AdminIpRestrictionBlockIp", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("AdminIpAddress", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("GenerateAjaxLink", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("AfterLoginAction", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("AfterLoginUrl", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("SendInfoEmailToAdmin", \Types\DataColumnsTypes::BOOLEAN, FALSE, true, 1));
        $this->AddColumn(new DataTableColumn("AdminInfoEmail", \Types\DataColumnsTypes::VARCHAR, "",true, 255));
        $this->AddColumn(new DataTableColumn("AdmiInfoMailId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("SendInfoEmailToUser", \Types\DataColumnsTypes::BOOLEAN, false, true, 1));
        $this->AddColumn(new DataTableColumn("UserInfoEmailFrom", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("UserInfoMailId", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("CookiesAccept", \Types\DataColumnsTypes::INTEGER, false, true, 1));
        $this->AddColumn(new DataTableColumn("DefaultFramework", \Types\DataColumnsTypes::VARCHAR, "", true, 255));
        $this->AddColumn(new DataTableColumn("RobotsTxt", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SiteMapStart", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SiteMapEnd", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SiteMapItemImage", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SiteMapItemVideo", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SiteMapItemUrl", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SiteMapItemStart", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("SiteMapItemEnd", \Types\DataColumnsTypes::TEXT, "", true));
    }

    public function InsertDefaultData() {
        $this->Setup();
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
