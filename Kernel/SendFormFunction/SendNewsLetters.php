<?php

namespace SendFormFunction; 
class SendNewsLetters extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        $this->FunctionType = \Types\SendFormFunctionTypes::$After;
    }

    public function CallFunction()
     {
        $mailing = \Model\MailingContacts::GetInstance();
        if ($this->GetParametrsFromSaveData("SendNewsletter") == 1)
        {
            $mailing->AddContactToMailingGroup($this->GetParametrsFromSaveData("ShopEmail"),"Newsletter");
        }
        
        
         
     }
             
    
}
