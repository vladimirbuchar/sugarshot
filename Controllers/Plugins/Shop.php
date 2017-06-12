<?php

namespace Controller;

use Model\DiscusionItems;
use Model\UsersInGroup;
use Utils\StringUtils;
use Model\ContentVersion;

class Shop extends Controllers {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetAjaxFunction("AddProductToCart", array("*"));
        $this->SetAjaxFunction("RecountProduct", array("*"));
        $this->SetAjaxFunction("DeleteProductFromCart", array("*"));
        $this->SetAjaxFunction("RegisteredServerEvents", array("*"));
        $this->SetAjaxFunction("SelectTransport", array("*"));
        $this->SetAjaxFunction("SelectPayment", array("*"));
        $this->SetAjaxFunction("IsEmptyCart",array("*"));
    }

    public function AddProductToCart() {
        $ajax = $this->PrepareAjaxParametrs();
        if (empty($ajax))
            return;
        $shop = new \xweb_plugins\Shop();
        $shop->AddProductToCart($ajax["ProductId"], self::$UserGroupId, $this->WebId, $this->LangId, $ajax["Count"], "Price", "Vat", $ajax["SelectVariant"]);
    }

    public function RecountProduct() {
        $ajax = $this->PrepareAjaxParametrs();
        if (empty($ajax))
            return;
        $shop = new \xweb_plugins\Shop();
        $prices = $shop->RecountProduct($ajax["ProductId"], $ajax["Count"], $ajax["Currency"], $ajax["PriceFormat"]);
        $prices["Price1ks"] = \Utils\StringUtils::PriceFormat($prices["Price1ks"], $ajax["PriceFormat"], $ajax["Currency"]);
        $prices["PriceVat1ks"] = \Utils\StringUtils::PriceFormat($prices["PriceVat1ks"], $ajax["PriceFormat"], $ajax["Currency"]);
        $prices["PriceCount"] = \Utils\StringUtils::PriceFormat($prices["PriceCount"], $ajax["PriceFormat"], $ajax["Currency"]);
        $prices["PriceVatCount"] = \Utils\StringUtils::PriceFormat($prices["PriceVatCount"], $ajax["PriceFormat"], $ajax["Currency"]);

        $allPrices = $shop->GetShopPrice($ajax["PriceFormat"], $ajax["Currency"]);
        $prices = array_merge($prices, $allPrices);
        return $prices;
    }

    public function DeleteProductFromCart() {
        $ajax = $this->PrepareAjaxParametrs();
        if (empty($ajax))
            return;
        $shop = new \xweb_plugins\Shop();
        $shop->DeleteProductFromCart($ajax["ProductId"]);
        $allPrices = $shop->GetShopPrice($ajax["PriceFormat"], $ajax["Currency"]);
        return $allPrices;
    }

    public function SelectTransport($params) {
        $udv =  \Model\UserDomainsValues::GetInstance();
        $list = $udv->GetDomainValue("ShopTransport", $params["objectid"]);
        $list = \Utils\ArrayUtils::ToArray($list);
        $list = \Utils\ArrayUtils::ValueAsKey($list, "ItemIdentificator");
        $shop = new \xweb_plugins\Shop();
        $shop->SetTransport($params["objectid"],$list["TransportPrice"]["Value"],$list["TransportVat"]["Value"],$list["TransportName"]["Value"]);
        return $shop->GetSumaPrice($params["PriceFormat"],$params["Currency"]);
    }
    
    public function SelectPayment($params)
    {
        $udv = \Model\UserDomainsValues::GetInstance();
        $list = $udv->GetDomainValue("Payment", $params["objectid"]);
        $list = \Utils\ArrayUtils::ToArray($list);
        $list = \Utils\ArrayUtils::ValueAsKey($list, "ItemIdentificator");
        $shop = new \xweb_plugins\Shop();
        $shop->SetPayment($params["objectid"],$list["PaymentPrice"]["Value"],$list["PaymentVat"]["Value"],$list["PaymentName"]["Value"]);
        return $shop->GetSumaPrice($params["PriceFormat"],$params["Currency"]);
    }
    
    public function IsEmptyCart()
    {
        $shop = new \xweb_plugins\Shop;
        return $shop->IsEmptyCart() ? true : false;
    }

}
