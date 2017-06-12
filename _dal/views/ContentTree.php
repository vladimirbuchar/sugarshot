<?php

namespace Model;
class ContentTree extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "ContentTree";
        $this->SqlView = "SELECT DISTINCT Content.Owner, Content.Id,ContentVersion.`Id` AS VersionId, ContentVersion.`WebId`,ContentVersion.Deleted AS VesionDeleted, Content.Deleted,ContentVersion.`LangId`,ContentVersion.`ContentId` ,ContentVersion.`Name`,Content.ParentId,Content.ContentType,Content.Sort,ContentVersion.IsLast,ContentVersion.IsActive  FROM  Content 
                          INNER JOIN `ContentVersion` ON Content.Id = ContentVersion.`ContentId` 
                          WHERE  ContentVersion.IsLast = 1 ORDER BY Content.Sort ASC ";
                
    }
    public function TableExportSettings()
    {
        
    }


}
