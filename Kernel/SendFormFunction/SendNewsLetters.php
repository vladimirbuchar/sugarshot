<?php

namespace SendFormFunction; 
class SendNewsLetters extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        parent::__construct();
        $this->FunctionType = \Types\SendFormFunctionTypes::AFTER;
    }

    public function CallFunction()
     {
        $mailing = new \Objects\MailingContacts();
        if ($this->GetParametrsFromSaveData("SendNewsletter") == 1)
        {
            $mailing->RegisterNewContact($this->GetParametrsFromSaveData("ShopEmail"),"Newsletter");
        }
        
        
         
     }
             
    
}
