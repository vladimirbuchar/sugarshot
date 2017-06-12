<?php
namespace Components;
class ShopCart extends UserComponents{
    
      public $Currency ="cs_CZ"  ;
      public $PriceFormat = '%.2n';
      
    public function __construct() {
        $this->Type = "ShopCart";
        $this->LinkJavascript = true;
        
        
        $this->LoadHtml = true;
        $this->UseItemTemplate = true;    
        $this->AutoReplaceString =true;
        parent::__construct();   
    }     
    
    public function GetComponentHtml()
    {
        $shop = new \xweb_plugins\Shop();
        $cart = $shop->GetCart();
        $this->SetReplaceString("ShowCart", "");
        $this->SetReplaceString("EmptyCart", "dn");
        if (empty($cart))
        {
            $this->SetReplaceString("ShowCart", "dn");
            $this->SetReplaceString("EmptyCart", "");
            
        }
        $this->SetUsedWords("word786");
        $this->SetUsedWords("word787");
        $this->SetUsedWords("word788");
        $this->SetUsedWords("word789");
        $this->SetUsedWords("word790");
        $this->SetUsedWords("word791");
        $this->SetUsedWords("word792");
        $this->SetUsedWords("word793");
        $this->SetUsedWords("word794");
        $this->SetReplaceString("Currency", $this->Currency);
        $this->SetReplaceString("PriceFormat", $this->PriceFormat);
        if (!empty($cart))
        {
            $this->ReplaceItems($cart);
            $sumaPrice = $shop->GetSumaPrice($this->PriceFormat,$this->Currency);
            $this->SetReplaceString("SumaPrice", $sumaPrice["PriceFormat"]);
            $this->SetReplaceString("SumaPriceVat", $sumaPrice["PriceVatFormat"]);
            $transportInfo = $shop->GetTransport();
            $paymentInfo = $shop->GetPayment();
            $this->SetReplaceString("TransportName", $transportInfo["name"]);
            $this->SetReplaceString("TransportPrice", \Utils\StringUtils::PriceFormat($transportInfo["price"], $this->PriceFormat, $this->Currency));
            $this->SetReplaceString("TransportPriceVat", \Utils\StringUtils::PriceFormat($transportInfo["priceVat"], $this->PriceFormat, $this->Currency));
            
            $this->SetReplaceString("PaymentName", $paymentInfo["name"]);
            $this->SetReplaceString("PaymentPrice", \Utils\StringUtils::PriceFormat($paymentInfo["price"], $this->PriceFormat, $this->Currency));
            $this->SetReplaceString("PaymentPriceVat", \Utils\StringUtils::PriceFormat($paymentInfo["priceVat"], $this->PriceFormat, $this->Currency));
        }
        
        
        
        
    }
    
    
}

