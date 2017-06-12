<?php
namespace Model;
class JsDetail extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "JsDetail";
        $this->SqlView = "SELECT Content.Sort,ContentVersion.Header, Content.DomainId, Content.TemplateId, Content.Id,Content.NoIncludeSearch, Content.Identificator,ContentVersion.Name,ContentVersion.WebId,ContentVersion.LangId,ContentVersion.IsLast, ContentVersion.IsActive,ContentVersion.Data,ContentVersion.Id AS VersionId,
                            ContentSecurity.GroupId,ContentSecurity.SecurityType,ContentSecurity.Value AS SecurityValue, securitySettings.GroupId AS SSGroupId, securitySettings.SecurityType AS SSSecurityType, securitySettings.Value AS SSValue   FROM `Content`  
                                INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId AND  Content.ContentType = 'Javascript' AND Content.Deleted = 0 AND ContentVersion.Deleted = 0
                                INNER JOIN ContentSecurity ON ContentSecurity.ObjectId = Content.Id
                                INNER JOIN ContentSecurity AS securitySettings ON securitySettings.ObjectId = Content.Id
                                AND     ContentSecurity.Deleted = 0 AND (ContentSecurity.SecurityType='canRead' AND ContentSecurity.Value = 1)";
                
    }
    public function TableExportSettings()
    {
        
    }


}
