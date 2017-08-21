<?php
namespace Utils;
use Kernel\GlobalClass;
use PHPMailer;
class Mail extends GlobalClass{
    public function SendEmail($from,$to,$idDocument,$data,$mailAttachments = array())
    {
        if (empty($from) || empty($to) || $idDocument == 0) 
            return;
        $content = new \Objects\Content();
        $parentid = $content->GetLangRoot($this->LangId);
        $contentMail = $content->CreateSendMail($this->LangId,$parentid,$data,$idDocument,$this->WebId,self::$UserGroupId,$from,$to);
        $this->Send($from, $to, $contentMail["Name"], $contentMail["Html"],$contentMail["MailId"],$mailAttachments);
    }
    private function Send($from,$to,$Name,$Html,$id = 0,$attachments = array())
    {
        try{
            if (REDIRECT_ALL_EMAIL)
            {
                $to = DEVEL_EMAIL;
            }
            $web = \Model\Webs::GetInstance();
            $web->GetObjectById($this->WebId);
            if (!$web->BlockSendEmails)
            {
               
                $mail = new PHPMailer();
                $mail->From = $from;
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $Name;
                $mail->Body =$Html;
                $mail->FromName = $from;
                $mail->CharSet = "UTF8";
                
                if (!empty($attachments))
                {
                    foreach ($attachments as $attachment)
                    {
                        if (empty($attachment["name"]))
                        {
                            $mail->addAttachment($attachment["file"]);
                        }
                        else 
                        {
                            $mail->addAttachment($attachment["file"],$attachment["name"]);
                        }
                    }
                }
                $mail->send();
                $sendMail=  \Model\SendEmails::GetInstance();
                $sendMail->MailId = $id;
                $sendMail->SaveObject();
                
            }
        }
        catch (Exception $ex)
        {
           \Kernel\Page::ApplicationError($ex);
        }
    }
    
    public function SendEmailById($mailId)
    {
        $content = new \Objects\Content();
        $data = $content->GetSendMailDetail($this->WebId, $this->LangId,$mailId);    
        
        $xml = $data[0]["Data"];
        $xmlData = simplexml_load_string($xml);
        $Html =  trim($xmlData->EmailText);
        $from =  trim($xmlData->EmailFrom);
        $to =  trim($xmlData->EmailTo);
        $Name = $data[0]["Name"];
        $mailId2 = $content->CreateResendEmail($this->LangId, $data[0]["ParentId"], $data[0]["Data"], $from, $to, $Name, $Html);
        $this->Send($from, $to, $Name, $Html,$mailId2);
        
    }
            
            
            
}
