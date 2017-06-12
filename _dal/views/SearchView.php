<?php
namespace Model;
class SearchView extends DatabaseViews{
    public function __construct()
    {
        
    parent::__construct();
        $this->ObjectName = "SearchView";
        $this->SqlView = "SELECT DISTINCT Content.ContentType, ContentData.Value AS SearchValue, Content.FormId, Content.DiscusionSettings,Content.DiscusionId, Content.TemplateId, Content.Id,Content.NoIncludeSearch,ContentVersion.ActiveTo,ContentVersion.SeoUrl,  ContentVersion.AvailableOverSeoUrl,ContentVersion.ActiveFrom, Content.Identificator,ContentVersion.Name,ContentVersion.WebId,ContentVersion.LangId,ContentVersion.IsLast, ContentVersion.IsActive,ContentVersion.Data,Content.GalleryId,Content.GallerySettings,
                               securitySettings.GroupId AS SSGroupId, securitySettings.SecurityType AS SSSecurityType, securitySettings.Value AS SSValue   FROM `Content`  
                                INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId AND ContentVersion.IsLast =  1 AND (Content.ContentType = 'UserItem' )AND Content.Deleted = 0 AND ContentVersion.Deleted = 0
                                INNER JOIN ContentSecurity AS securitySettings ON securitySettings.ObjectId = Content.Id  AND securitySettings.Deleted = 0
                                LEFT JOIN ContentData ON  Content.Id = ContentData.ContentId AND ContentData.Deleted = 0
                                WHERE Content.NoIncludeSearch = 0 AND ContentVersion.IsActive = 1 AND securitySettings.SecurityType = 'canRead'
                                ";
                
    }
    public function TableExportSettings()
    {
        
    }


}
