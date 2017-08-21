<?php

namespace Controller;

class ShopApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetApiFunction("AddProductToCart", array("*"));
        $this->SetApiFunction("RecountProduct", array("*"));
        $this->SetApiFunction("DeleteProductFromCart", array("*"));
        $this->SetApiFunction("RegisteredServerEvents", array("*"));
        $this->SetApiFunction("SelectTransport", array("*"));
        $this->SetApiFunction("SelectPayment", array("*"));
        $this->SetApiFunction("IsEmptyCart",array("*"));
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
        $udv =  new \Objects\UserDomains();
        $list = $udv->GetDomainValue("ShopTransport", $params["objectid"]);
        $list = \Utils\ArrayUtils::ToArray($list);
        $list = \Utils\ArrayUtils::ValueAsKey($list, "ItemIdentificator");
        $shop = new \xweb_plugins\Shop();
        $shop->SetTransport($params["objectid"],$list["TransportPrice"]["Value"],$list["TransportVat"]["Value"],$list["TransportName"]["Value"]);
        return $shop->GetSumaPrice($params["PriceFormat"],$params["Currency"]);
    }
    
    public function SelectPayment($params)
    {
        $udv = new \Objects\UserDomains();
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
        return $shop->IsEmptyCart();
    }

}
