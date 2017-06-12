<?php

namespace SendFormFunction; 
class SendCart extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        $this->FunctionType = \Types\SendFormFunctionTypes::$Before;
    }

    public function CallFunction()
     {
         $saveData = $this->GetParameter("SaveData");
         $shop = new \xweb_plugins\Shop();
         $cart = $shop->GetCart();
         $xml = "";
         foreach ($cart as $item)
         {
             $data = "<data>\n".$item["Data"]."\n</data>\n";
             unset($item["Data"]);
             $xmlItem = \Utils\ArrayUtils::DibiRowToXml($item,"dataItem",$data);
             $xml = $xml."\n".$xmlItem;
         }
         
         $pos = count($saveData);
         $saveData[$pos][0] = "DataItems";
         $saveData[$pos][1] = $xml;
         $this->SetResult("SaveData", $saveData);
     }
             
    
}
