<?php
namespace Model;
class SendMailList extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "SendMailList";
        $this->SqlView = "SELECT Content.ContentType,Content.Id,Content.ParentId,ContentVersion.Name,ContentVersion.WebId,ContentVersion.LangId,ContentVersion.IsLast,ContentVersion.IsActive, ContentVersion.Data,IFNULL(SendEmails.MailId,0) SendEmail  FROM `Content`  
                            INNER JOIN ContentVersion ON Content.Id =  ContentVersion.ContentId
                            LEFT JOIN SendEmails ON  Content.Id = SendEmails.MailId
                            WHERE (Content.ContentType = 'SendMail' ) AND Content.Deleted = 0 AND ContentVersion.Deleted = 0";
                
    }
    public function TableExportSettings()
    {
        
    }


}
