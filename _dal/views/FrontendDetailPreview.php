<?php
namespace Model;
class FrontendDetailPreview extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "FrontendDetailPreview";
        $this->SqlView = "SELECT DISTINCT Content.Sort, Content.SaveToCache, Content.NoLoadSubItems, Content.ActivatePager, Content.FirstItemLoadPager, Content.NextItemLoadPager,Content.TemplateId,Content.Id, ContentSecurity.GroupId, ContentVersion.WebId,ContentVersion.LangId,ContentVersion.Name, ContentVersion.SeoUrl,ContentVersion.AvailableOverSeoUrl,ContentVersion.Data,ContentVersion.Header,ContentVersion.ActiveFrom,Content.ContentType,  
                ContentVersion.ActiveTo,Content.NoIncludeSearch,Content.Identificator,Content.ParentId,ContentVersion.IsActive,ContentVersion.IsLast 
                FROM Content
                LEFT JOIN ContentVersion ON Content.Id = ContentVersion.ContentId AND  ContentVersion.Deleted = 0 AND Content.Deleted = 0 AND (ContentVersion.IsActive = 1 OR ContentVersion.IsLast = 1) AND (Content.ContentType = 'UserItem' OR Content.ContentType = 'Template' OR Content.ContentType = 'Discusion' OR Content.ContentType = 'Link' OR Content.ContentType = 'ExternalLink' OR Content.ContentType = 'Form' OR Content.ContentType = 'JavascriptAction' ) 
                LEFT JOIN ContentSecurity ON Content.Id = ContentSecurity.ObjectId AND ContentSecurity.SecurityType = 'canRead' AND ContentSecurity.Value = 1 AND ContentSecurity.Deleted = 0
                ORDER BY Content.Sort ASC
";
    }
    public function TableExportSettings()
    {
        
    }


}
