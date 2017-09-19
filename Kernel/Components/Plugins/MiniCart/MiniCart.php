<?php
namespace Components;
class MiniCart extends UserComponents implements \Inteface\iComponent{
    
      public $Currency ="cs_CZ"  ;
      public $PriceFormat = '%.2n';
      public $CartUrl = "";
      public $ShopUrl="";
      
    public function __construct() {
        $this->Type = "MiniCart";
        $this->LinkJavascript = false;
        $this->InsertJavascriptToContent = false; 
        $this->LoadHtml = true;
        $this->AutoReplaceString =true;
        $this->IsCache = false;
        
        parent::__construct();   
        
    }     
    
    public function GetComponentHtml()
    {
        $shop = new \xweb_plugins\Shop();
        $cart = $shop->GetCart();
        $this->SetReplaceString("ShowCart", "");
        $this->SetReplaceString("EmptyCart", "dn");
        $this->SetUsedWords("word794");
        $this->SetUsedWords("word798");
        $this->SetUsedWords("word792");
        $this->SetUsedWords("word799");
        $this->SetUsedWords("word800");
        
        if (empty($cart))
        {
        
            $this->SetReplaceString("ShowCart", "dn");
            $this->SetReplaceString("EmptyCart", "");   
        }
        else 
        {
            $allPrices = $shop->GetShopPrice($this->PriceFormat,$this->Currency);
            $this->SetReplaceStringArray($allPrices);
            $this->SetReplaceString("ItemsCartCount", $shop->GetCartItemsCount());
        }
        $this->SetReplaceString("CartUrl", \Utils\StringUtils::NormalizeUrl(SERVER_NAME_LANG.$this->CartUrl));
        $this->SetReplaceString("ShopUrl", \Utils\StringUtils::NormalizeUrl(SERVER_NAME_LANG.$this->ShopUrl));   
    }
}

