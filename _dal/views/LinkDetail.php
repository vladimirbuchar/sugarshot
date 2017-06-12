<?php
namespace Model;
class LinkDetail extends DatabaseViews{
    public function __construct()
    {
        
    parent::__construct();
        $this->ObjectName = "LinkDetail";
        $this->SqlView = "SELECT Content.UseTemplateInChild,Content.NoChild, Content.ParentId, Content.FormId, Content.DiscusionSettings,Content.DiscusionId, Content.TemplateId, Content.Id,Content.NoIncludeSearch,ContentVersion.ActiveTo,ContentVersion.SeoUrl,  ContentVersion.AvailableOverSeoUrl,ContentVersion.ActiveFrom, Content.Identificator,ContentVersion.Name,ContentVersion.WebId,ContentVersion.LangId,ContentVersion.IsLast, ContentVersion.IsActive,ContentVersion.Data,Content.GalleryId,Content.GallerySettings
                                FROM `Content`  
                                INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId AND ContentVersion.IsLast =  1 AND (ContentType= 'Link' OR ContentType= 'ExternalLink' OR ContentType= 'CssExternalLink' OR ContentType= 'JsExternalLink' OR ContentType= 'JavascriptAction')AND Content.Deleted = 0 AND ContentVersion.Deleted = 0
                                ";
                
    }
    public function TableExportSettings()
    {
        
    }


}
