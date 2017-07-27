<?php

namespace xweb_plugins;
class Shop {
    /** 
     * @var \Utils\SessionManager
     */
    private static $_sessionManager = null;
    
    
    public function __construct() {
        if (self::$_sessionManager == null)
            self::$_sessionManager = new \Utils\SessionManager(\Utils\SessionManager::$WebMode);
    }
    public function AddProductToCart($productId,$groupId, $webId, $langId,$count,$priceColumn= "Price",  $vatColumn ="Vat",$specification ="")
    {
        $products = $this->GetCart();
        $productInfo = array();
        if (!empty($products))
        {
            if (!empty($products[$productId.$specification]))
            {
                $productInfo = $products[$productId.$specification];
                $count = $count + $productInfo["Count"];
                
            }
        }
        if ($count < 1) $count = 1;
        if (empty($productInfo))
        {
            $productInfo = $this->GetProductInfo($productId, $groupId, $webId, $langId);
        }
        $productInfo = (array)$productInfo;
        
        
        $xmlString = $productInfo["Data"];
        $xml = simplexml_load_string($xmlString);
        
        $price = trim($xml[0]->$priceColumn);
        $vat = trim($xml[0]->$vatColumn);
        
        $prices = $this->GetAllPrice($price, $vat, $count);
        $productInfo = array_merge($productInfo,$prices);
        $productInfo["Price"] = $price;
        $productInfo["Vat"] = $vat;
        $productInfo["Count"] = $count;
        $productInfo["Specification"] = $specification;
        //$xmlStr = \Utils\ArrayUtils::DibiRowToXml($productInfo);
        $products[$productId.$specification] =$productInfo;
        $this->SetProductCart($products);
        $this->SetFreeTransport();
        
    }
    
    private function GetProductInfo($productId,$groupId,$webId, $langId)
    {
        $content =  \Model\ContentVersion::GetInstance();
        $productInfo = $content->GetUserItemDetail ($productId,$groupId,$webId, $langId,0,true);
        if (!empty($productInfo))
        {
            $productInfo = $productInfo[0];
        }
        return $productInfo;
    }
    
    public function GetAllPrice($price,$vat = 0,$count = 1)
    {
        $out["Price1ks"] = $price;
        $out["PriceVat1ks"] = $this->GetPriceVat($price, $vat);
        $out["PriceCount"] = $price * $count;
        $out["PriceVatCount"] = $this->GetPriceVat($price, $vat,$count);
        return $out;
    }
    
    public function  RecountProduct($productId, $count,$currency,$priceFormat)
    {
        $products = $this->GetCart();
        $product = $products[$productId];
        
        if(!empty($product))
        {
            if ($count < 1) $count = 1;
            $prices = $this->GetAllPrice($product["Price"], $product["Vat"], $count);
            $product["Price1ks"] = $prices["Price1ks"];
            $product["PriceVat1ks"] = $prices["PriceVat1ks"];
            $product["PriceCount"] = $prices["PriceCount"];
            $product["PriceVatCount"] = $prices["PriceVatCount"];
            $product["Count"] = $count;
            $products[$productId] = $product;
            $this->SetProductCart($products);
            $this->SetFreeTransport();
            return $prices;
        }
        return null;
        
    }


    private function GetPriceVat($price,$vat,$count  = 1)
    {
        if (empty($vat))
            return $price*$count;
        $vat = $vat/100;
        $vat = 1+$vat;
            return $this->RoundPriceVat($price* $vat)*$count;
    }
    private function RoundPriceVat($price)
    {
        return round($price);
    }
    
    public function GetCart()
    {
        
        if (self::$_sessionManager->IsEmpty("shop") || self::$_sessionManager->IsEmpty("shop","products")) return array();
        return self::$_sessionManager->GetSessionValue("shop","products");
    }
    
    public function DeleteProductFromCart($productId)
    {
        $products = $this->GetCart();
        unset($products[$productId]);
        $this->SetProductCart($products);
        $this->SetFreeTransport();
    }
    
    public function IsEmptyCart()
    {
        $cart = $this->GetCart();
        return empty($cart) ? true: false;
    }
    
    public function GetShopPrice($format, $locale)
    {
        $cart = $this->GetCart();
        $productPrice = 0;
        $productPriceVat = 0;
        foreach ($cart as $row)
        {
            $productPrice = $productPrice+$row["PriceCount"];
            $productPriceVat = $productPriceVat+$row["PriceVatCount"];
        }
        $out = array();
        $out["ProductPrice"] = $productPrice;
        $out["ProductPriceVat"] = $productPriceVat;
        if ($format != null && $locale !=null)
        {
            $out["ProductPriceFormated"] = \Utils\StringUtils::PriceFormat($productPrice, $format, $locale)  ;
            $out["ProductPriceVatFormated"] = \Utils\StringUtils::PriceFormat($productPriceVat, $format, $locale) ;
        }
        
        return $out;
    }
    
    public function GetCartItemsCount()
    {
        $cart = $this->GetCart();
        if (empty($cart)) return 0;
        return count($cart);
        
    }
    
    public function SetTransport($transportId,$price,$vat,$name)
    {
        $priceVat = $this->GetPriceVat($price, $vat);
        $transport = array();
        $transport["id"] = $transportId;
        $transport["price"] = $price;
        $transport["vat"] = $vat;
        $transport["priceVat"] = $priceVat;
        $transport["name"] = $name;
        self::$_sessionManager->SetSessionValue("shop", $transport,"transport");
        
    }
    
    public function SetPayment($paymentId,$price,$vat,$name)
    {
        $priceVat = $this->GetPriceVat($price, $vat);
        $payment = array();
        $payment["id"] = $paymentId;
        $payment["price"] = $price;
        $payment["vat"] = $vat;
        $payment["priceVat"] = $priceVat;
        $payment["name"] = $name;
        self::$_sessionManager->SetSessionValue("shop", $payment,"payment");
         
    }
    
    public function GetTransport()
    {
        if (self::$_sessionManager->IsEmpty("shop") || self::$_sessionManager->IsEmpty("shop","transport")) return array();
        return self::$_sessionManager->GetSessionValue("shop","transport");
    }
    
    public function  GetPayment()
    {
        if (self::$_sessionManager->IsEmpty("shop") || self::$_sessionManager->IsEmpty("shop","payment")) return array();
        return self::$_sessionManager->GetSessionValue("shop","payment");
    }
    
    public function SetFreeTransport()
    {
        $cart = $this->GetShopPrice(null, null);
        $price = $cart["ProductPrice"];
        self::$_sessionManager->UnsetKey("shop","freeTransport");
        if ($price >= 1000)
            self::$_sessionManager->SetSessionValue ("shop", "freeTransport", true);
    }
    
    public function IsFreeTransport()
    {
        if (self::$_sessionManager->IsEmpty("shop") || self::$_sessionManager->IsEmpty("shop","freeTransport")) return false;
        return self::$_sessionManager->GetSessionValue("shop","freeTransport");
    }


    
    //privce with transport and payment
    public function GetSumaPrice($format, $locale)
    {
        $productPrice = $this->GetShopPrice($format, $locale);
        $out = array();
        $transport = $this->GetTransport();
        $payment = $this->GetPayment();
        
        $tPrice = 0;
        $tVat = 0;
        $tPriceVat = 0;
        if (!empty($transport))
        {
            $tPrice = $transport["price"];
            $tVat = $transport["vat"];
            $tPriceVat = $transport["priceVat"];
        }
        
        $pPrice = 0;
        $pVat = 0;
        $pPriceVat = 0;
        if (!empty($payment))
        {
            $pPrice = $payment["price"];
            $pVat = $payment["vat"];
            $pPriceVat = $payment["priceVat"];
        }
        
        $out["Price"] = $productPrice["ProductPrice"]+$tPrice +$pPrice;
        $out["PriceVat"] = $productPrice["ProductPriceVat"]+ $tPriceVat + $pPriceVat;
        if ($format != null && $locale != null)
        {
            $out["PriceFormat"] = \Utils\StringUtils::PriceFormat($out["Price"], $format, $locale);
            $out["PriceVatFormat"] = \Utils\StringUtils::PriceFormat($out["PriceVat"], $format, $locale);
        }
        return $out;   
    }
    private function SetProductCart($products)
    {
        self::$_sessionManager->SetSessionValue("shop", $products,"products");
        
    }
    
    public function ClearCart()
    {
        self::$_sessionManager->UnsetKey("shop");
    }
    
    public function GetShopSettings($value)
    {
        /** 
         * @var \Model\UserDomainsValues
         */
        $udv = \Model\UserDomainsValues::GetInstance();
        $values = $udv->GetDomainValueConditon("shopsettings",0,$value);
        if (empty($values)) return "";
        return $values[0]["Value"];
        
        
        
        
    }
    
    
    
    
    
    
}




