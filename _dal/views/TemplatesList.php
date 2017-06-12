<?php
namespace Model;
class TemplatesList extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "TemplatesList";
        $this->SqlView = "SELECT Content.ContentType,Content.Id,Content.ParentId,ContentVersion.Name,ContentVersion.WebId,ContentVersion.LangId,ContentSecurity.GroupId,ContentVersion.IsLast,ContentVersion.IsActive, Content.DomainId,Content.Sort  FROM `Content`  
                            INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId
                            INNER JOIN ContentSecurity ON ContentSecurity.ObjectId = Content.Id
                            WHERE (Content.ContentType = 'Template' OR Content.ContentType = 'langfolder') AND Content.Deleted = 0 AND ContentVersion.Deleted = 0 AND ContentSecurity.Deleted = 0 AND (ContentSecurity.SecurityType='canRead' AND ContentSecurity.Value = 1) 
                            ORDER BY Content.Sort ASC";
        
                
    }
    public function TableExportSettings()
    {
        
    }
}
