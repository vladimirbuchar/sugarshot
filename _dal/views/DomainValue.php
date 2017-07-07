<?php
namespace Model;
class DomainValue extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "DomainValue";
        $this->SqlView = "SELECT UserDomainsValues.Id AS ValueId, DomainIdentificator,ItemId,ObjectId,`Value`,UserDomains.Id As DomainId, 
                UserDomainsItems.Identificator AS ItemIdentificator,UserDomainsValues.Deleted  FROM `UserDomainsValues` 
                LEFT JOIN UserDomains ON UserDomainsValues.DomainId = UserDomains.Id  
                LEFT JOIN UserDomainsItems ON UserDomainsValues.ItemId = UserDomainsItems.Id
                WHERE  UserDomains.Deleted = 0";
                
    }
    public function TableExportSettings()
    {
        
    }


}
