<?php

namespace SendFormFunction; 
class SendEet extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        parent::__construct();
        $this->FunctionType = \Types\SendFormFunctionTypes::AFTER;
    }

    public function CallFunction()
     {
         $shop = new \xweb_plugins\Shop();
         $sumaPrice = $shop->GetSumaPrice(null, null);
         
     }
             
    
}
