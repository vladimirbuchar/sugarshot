<?php

namespace Objects;
class MailingContacts extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    public function AddContact($email)
    {
        $this->Email = $email;
        return $this->SaveObject();
    }
    
    public function AddContactToMailingGroup($email,$mailingGroupName)
    {
        $id = $this->AddContact($email);
        $udv = new \Objects\UserDomains();
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
    
    // groups 
    
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

}
