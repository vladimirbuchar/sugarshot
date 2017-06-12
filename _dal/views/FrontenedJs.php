<?php
namespace Model;
class FrontenedJs extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "FrontenedJs";
        $this->SqlView = "SELECT ContentVersion.Id AS VersionId, ContentVersion.Header,Content.Identificator, ContentVersion.data,  Content.ContentType,Content.Id,Content.ParentId,ContentVersion.Name,ContentVersion.WebId,ContentVersion.LangId,ContentSecurity.GroupId  FROM `Content`  
                            INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId
                            INNER JOIN ContentSecurity ON ContentSecurity.ObjectId = Content.Id
                            WHERE (Content.ContentType = 'Javascript') AND Content.Deleted = 0 AND ContentVersion.Deleted = 0 AND ContentSecurity.Deleted = 0 AND (ContentSecurity.SecurityType='canRead' AND ContentSecurity.Value = 1) AND IsActive = 1";
                
    }
    public function TableExportSettings()
    {
        
    }


}
