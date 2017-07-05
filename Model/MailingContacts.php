<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class MailingContacts  extends DatabaseTable{
    public $Email;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "MailingContacts";
        $this->MultiLang = false;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("Email"));
        $this->SetDefaultSelectColumns();
    }
    
    /*public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/
    
    public function OnCreateTable() {
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 255;
        $colUserGroupId->Name ="Email";
        $colUserGroupId->Type = "varchar";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
    }
    
    public function AddContact($email)
    {
        $this->Email = $email;
        return $this->SaveObject();
    }
    
    public function AddContactToMailingGroup($email,$mailingGroupName)
    {
        $id = $this->AddContact($email);
        $udv = UserDomainsValues::GetInstance();
        $valueList = $udv->GetDomainValueConditon("Mailinggroups",0,"MailingGroupName",$mailingGroupName);
        $ids = \Utils\ArrayUtils::GetColumnsvalue($valueList,"ObjectId");
        
        if (!empty($valueList))
        {
            $mg = MailingContactsInGroups::GetInstance();
            $mg->SaveContactToMailingGroup($id,$ids[0]);
        }
        
        
    }
    public function UpdateContact($id,$email)
    {
        $this->Id = $id;
        $this->Email = $email;
        $this->SaveObject();
        return $id;
    }
    
    public function GetUserMailingGroups($id)
    {
        return dibi::query("SELECT * FROM MailingContactsInGroups WHERE ContactId = %i",$id)->fetchAll();
    }
    
    
    public function GetMailingList($webId)
    {
        return $this->SelectByCondition("Deleted = 0 AND WebId = $webId");
    }
    
    public function GetMailingDetail($id)
    {
        return $this->SelectByCondition("Id = $id");
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
