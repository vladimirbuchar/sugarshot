<?php
namespace Model;
class SendMailDetail extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "SendMailDetail";
        $this->SqlView = "SELECT Content.TemplateId,Content.Id,  ContentVersion.WebId,ContentVersion.LangId,ContentVersion.Name, ContentVersion.SeoUrl,ContentVersion.AvailableOverSeoUrl,ContentVersion.Data,ContentVersion.Header,ContentVersion.ActiveFrom,  
                ContentVersion.ActiveTo,Content.NoIncludeSearch,Content.Identificator,Content.ParentId
                FROM Content
                LEFT JOIN ContentVersion ON Content.Id = ContentVersion.ContentId AND ContentVersion.IsActive = 1 AND ContentVersion.Deleted = 0 AND Content.Deleted = 0  AND (Content.ContentType = 'SendMail')
";                
    }
    public function TableExportSettings()
    {
        
    }


}
