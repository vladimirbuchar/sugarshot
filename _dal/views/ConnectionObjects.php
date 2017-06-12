<?php
namespace Model;
class ConnectionObjects extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "ConnectionObjects";
        $this->SqlView = "SELECT  ContentConnection.ObjectIdConnected,ContentConnection.Id AS ConnectionId,ContentConnection.`ConnectedType`,ContentConnection.`SettingConnection`,ContentVersion.Name,ContentVersion.LangId,ContentConnection.ObjectId,ContentVersion.Data, ContentVersion.SeoUrl,Content.UploadedFileType,Content.ContentType  FROM `ContentConnection` 
JOIN ContentVersion ON (ContentConnection.`ObjectIdConnected` = ContentVersion.ContentId AND ContentVersion.IsLast = 1)
JOIN Content ON Content.Id = ContentVersion.ContentId
UNION 
SELECT  ContentConnection.ObjectIdConnected,ContentConnection.Id AS ConnectionId,ContentConnection.`ConnectedType`,ContentConnection.`SettingConnection`, '' AS  Name, 0 AS LangId,ContentConnection.ObjectId, '' AS Data, '' AS SeoUrl, '' AS  UploadedFileType, '' AS ContentType  FROM `ContentConnection` 
WHERE ContentConnection.ObjectIdConnected = 0
UNION 
SELECT  ContentConnection.ObjectIdConnected,ContentConnection.Id AS ConnectionId,ContentConnection.`ConnectedType`,ContentConnection.`SettingConnection`,ContentVersion.Name,ContentVersion.LangId, Content.Id AS ObjectId,ContentVersion.Data,ContentVersion.SeoUrl,Content.UploadedFileType, Content.ContentType  FROM `Content` 
JOIN ContentConnection ON ContentConnection.`ObjectId` = Content.GalleryId AND ContentConnection.`ConnectedType` = 'gallery'
JOIN ContentVersion  ON ContentConnection.ObjectIdConnected = ContentVersion.ContentId AND ContentVersion.IsLast = 1
 

";
                
    }
    public function TableExportSettings()
    {
        
    }


}
