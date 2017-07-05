<?php

namespace SendFormFunction; 
class ClearCart extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        $this->FunctionType = \Types\SendFormFunctionTypes::$After;
    }

    public function CallFunction()
     {
         $shop = new \xweb_plugins\Shop();
         $shop->ClearCart();
         
     }
             
    
}
