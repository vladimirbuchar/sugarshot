<?php

namespace SendFormFunction; 
class UpdateShopCount extends \SendFormFunction\SendFormFunction
{
    public function __construct() {
        parent::__construct();
        $this->FunctionType = \Types\SendFormFunctionTypes::BEFORE;
    }

    public function CallFunction()
     {
        $shop = new \xweb_plugins\Shop();
        $cart = $shop->GetCart();
        $content = new \Objects\Content();
        
        foreach ($cart  as $row)
        {
            $id= $row["Id"];
            $xml = $row["Data"]; 
            $array = \Utils\ArrayUtils::XmlToArray($xml, "SimpleXMLElement", LIBXML_NOCDATA);
            $productStock = $array["ProductStock"] - $row["Count"];
            if ($productStock < 1) 
            {
                $productStock = -1;
            }
            
            $content->UpdateValue($id,"ProductStock",$productStock);
        }
     }
             
    
}
