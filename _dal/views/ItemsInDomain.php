<?php
namespace Model;
class ItemsInDomain extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "ItemsInDomain";
        $this->SqlView = "SELECT UserDomainsItems.OnChangeEvent, UserDomainsItems.AddToSort, UserDomainsItems.Autocomplete,UserDomainsItemsInGroups.GroupId, UserDomainsItems.DomainSettings,UserDomainsItems.ShowOnlyDetail, UserDomainsItems.AddCDATA, UserDomainsItems.NoUpdate,UserDomainsItems.Domain, UserDomainsItems.CssClass,UserDomainsItems.ShowInAdminReadOnly,
            UserDomainsItems.ShowInWebReadOnly,UserDomainsItems.Validate, UserDomains.Id AS DomainId, UserDomainsItems.Id,`ShowName`, `Identificator`, `Type`, 
            `ShowInAdmin`, `ShowInWeb`, `Required`, `DefaultValue`, `MaxLength`, `MinLength`, DomainIdentificator,ValueList, UserDomainsItems.UniqueValue,UserDomainsItems.XmlSettings,UserDomainsItems.FiltrSettings
                FROM UserDomains 
                LEFT JOIN `UserDomainsItems` ON UserDomains.Id = UserDomainsItems.`DomainId` 
                LEFT JOIN UserDomainsItemsInGroups ON  UserDomainsItems.Id = UserDomainsItemsInGroups.ItemId    
                WHERE UserDomainsItems.`Deleted` = 0  
";
                
    }
    
    public function TableExportSettings()
    {
        
    }


}
