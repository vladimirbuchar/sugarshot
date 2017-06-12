<?php
namespace Model;
class FormStatistic extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "FormStatistic";
        $this->SqlView = "SELECT Content.ContentType,Content.Id,Content.ParentId,ContentVersion.Data,ContentVersion.WebId,ContentVersion.LangId,ContentVersion.IsLast,ContentVersion.IsActive  FROM `Content`  
                            INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId AND (ContentVersion.IsLast = 1 OR ContentVersion.IsActive = 1)
                            WHERE (Content.ContentType = 'FormStatistic') AND Content.Deleted = 0 AND ContentVersion.Deleted = 0";
                
    }
    public function TableExportSettings()
    {
        
    }


}
