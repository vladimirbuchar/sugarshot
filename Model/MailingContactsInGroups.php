<?php

namespace Model;
use Utils\StringUtils;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class MailingContactsInGroups  extends DatabaseTable{
    public $ContactId;
    public $GroupId;
    //private static $_instance = null;
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "MailingContactsInGroups";
        $this->MultiLang = false;
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ContactId","GroupId"));
        $this->SetDefaultSelectColumns();
    }
    /*
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
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 9;
        $colUserGroupId->Name ="ContactId";
        $colUserGroupId->Type = "INT";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
        
        $colUserGroupId = new DataTableColumn();
        $colUserGroupId->DefaultValue =0;
        $colUserGroupId->IsNull = true;
        $colUserGroupId->Length = 9;
        $colUserGroupId->Name ="GroupId";
        $colUserGroupId->Type = "INT";
        $colUserGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupId);
    }
    
    public function AddContactToMailingGroup($contactId,$mailingGroupId)
    {
        $this->DeleteByCondition("ContactId = $contactId",true);
        for ($i= 0; $i< count($mailingGroupId);$i++)
        {
               
            if ($mailingGroupId[$i][1] == 1)
            {
                $this->SaveContactToMailingGroup($contactId, $mailingGroupId[$i][0]);
                
            }
        }
    }
    
    public function SaveContactToMailingGroup($contactId,$groupId)       
    {
        $this->ContactId = $contactId;
        $groupId = StringUtils::RemoveString($groupId,"MailingGroupName_");
        $this->GroupId = $groupId;
        $this->SaveObject();
    }
            
    
    public function GetMailsInMailingGroups($mailingGroupId)
    {
        return dibi::query("SELECT MailingContacts.* FROM MailingContacts 
                JOIN MailingContactsInGroups ON MailingContacts.Id = MailingContactsInGroups.ContactId AND MailingContactsInGroups.GroupId = %i
                ",$mailingGroupId)->fetchAll();
    }
    
     
    
    public function SetValidate($mode = false) {
        
    }

    public function InsertDefaultData() {
        
    }
    public function TableMigrate()
    {
        
        
    }
    public function TableExportSettings()
    {
        
    }

}
