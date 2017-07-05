<?php
namespace Model;
class UserItemDetail extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
    
        $this->ObjectName = "UserItemDetail";
        $this->SqlView = "SELECT Content.SortRule,Content.Sort, Content.SaveToCache ,Content.NoLoadSubItems,Content.Inquery, Content.ActivatePager, Content.FirstItemLoadPager, Content.NextItemLoadPager, Content.CopyDataToChild, Content.ChildTemplateId, Content.UseTemplateInChild,Content.NoChild, Content.ParentId, Content.FormId, Content.DiscusionSettings,Content.DiscusionId, Content.TemplateId, Content.Id,Content.NoIncludeSearch,ContentVersion.ActiveTo,ContentVersion.SeoUrl,  ContentVersion.AvailableOverSeoUrl,ContentVersion.ActiveFrom, Content.Identificator,ContentVersion.Name,ContentVersion.WebId,ContentVersion.LangId,ContentVersion.IsLast, ContentVersion.IsActive,ContentVersion.Data,Content.GalleryId,Content.GallerySettings,ContentVersion.Id AS versionId,
                            ContentSecurity.GroupId,ContentSecurity.SecurityType,ContentSecurity.Value AS SecurityValue, securitySettings.GroupId AS SSGroupId, securitySettings.SecurityType AS SSSecurityType, securitySettings.Value AS SSValue   FROM `Content`  
                                INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId  AND (Content.ContentType = 'UserItem')AND Content.Deleted = 0 AND ContentVersion.Deleted = 0 AND (ContentVersion.IsLast = 1 OR ContentVersion.IsActive = 1) 
                                INNER JOIN ContentSecurity ON ContentSecurity.ObjectId = Content.Id  AND     ContentSecurity.Deleted = 0 AND (ContentSecurity.SecurityType='canRead' AND ContentSecurity.Value = 1)
                                INNER JOIN ContentSecurity AS securitySettings ON securitySettings.ObjectId = Content.Id 
                                ";
                
    }
    public function TableExportSettings()
    {
        
    }


}
