<?php
namespace SendFormFunction; 

class HeurekaKonverze extends \SendFormFunction\SendFormFunction {
    public function __construct() {
        parent::__construct();
        $this->FunctionType = \Types\SendFormFunctionTypes::$After;
        
    }
    
    public  function CallFunction() {
    
    $shop = new \xweb_plugins\Shop();
    $itemId = $this->GetParameter("ItemId");
    $cart = $shop->GetCart();
    $out = "";
    $out .='<script type="text/javascript">';
    $out .='var _hrq = _hrq || [];';
    $out .="_hrq.push(['setKey', '".$shop->GetShopSettings("HuerekaKonverze")."']);";
    $out .="_hrq.push(['setOrderId', '$itemId']);";
    foreach ($cart as $row)
    {
        $xml= $row["Data"];
        $ar = \Utils\ArrayUtils::XmlToArray($xml, "SimpleXMLElement",LIBXML_NOCDATA);
        $out .="_hrq.push(['addProduct', '".$row["Name"]."', '".$row["PriceVat1ks"]."', '".$row["Count"]."']);";
    }
       
    $out .="_hrq.push(['trackOrder']);";
    $out .="(function() {";
    $out .="var ho = document.createElement('script'); ho.type = 'text/javascript'; ho.async = true;";
    $out .="ho.src = 'https://im9.cz/js/ext/1-roi-async.js';";
    $out .="var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ho, s);";
    $out .="})();";
    $out .="</script>";
    self::$SessionManager->SetSessionValue("HeurekaKonverze", $out);
    
    
        
        
    }
    
    
    
    
}