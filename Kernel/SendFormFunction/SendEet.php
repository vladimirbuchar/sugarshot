<?php

namespace SendFormFunction; 
class SendEet extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        $this->FunctionType = \Types\SendFormFunctionTypes::$After;
    }

    public function CallFunction()
     {
         $shop = new \xweb_plugins\Shop();
         $sumaPrice = $shop->GetSumaPrice(null, null);
         
     }
             
    
}
