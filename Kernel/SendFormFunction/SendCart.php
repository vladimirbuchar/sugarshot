<?php

namespace SendFormFunction; 
class SendCart extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        parent::__construct();
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
         $sumaPrices =  $shop->GetSumaPrice(null, null);
         $productPrices =  $shop->GetShopPrice(null, null);
         
         $pos++;
         $saveData[$pos][0] = "ProductPrice";
         $saveData[$pos][1] = $productPrices["ProductPrice"];
         
         $pos++;
         $saveData[$pos][0] = "ProductPriceVat";
         $saveData[$pos][1] = $productPrices["ProductPriceVat"];
         
         $pos++;
         $saveData[$pos][0] = "SumaPrice";
         $saveData[$pos][1] = $sumaPrices["Price"];
         $pos++;
         $saveData[$pos][0] = "SumaPriceVat";
         $saveData[$pos][1] = $sumaPrices["PriceVat"];
         
         $pos++;
         $saveData[$pos][0] = "OrderStatus";
         $saveData[$pos][1] = "new";
         
         $this->SetResult("SaveData", $saveData);
     }
             
    
}
