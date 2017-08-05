<?php
namespace Components;
class ProductsInOrder extends UserComponents{
    
    
    public function __construct() {
        
        $this->Type = "ProductsInOrder";
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        $html = "";
        $forms = new \Model\ContentVersion();
        $data = $forms->GetFromStatisticDetail($_GET["params"],$this->LangId);
        $dataXml= \Utils\ArrayUtils::XmlToArray($data[0]["Data"],"SimpleXMLElement",LIBXML_NOCDATA);
        $shopCart = array();
        if (empty($dataXml["DataItems"]["dataItem"][0]))
        {
            $shopCart = array($dataXml["DataItems"]["dataItem"]);
        }
        else
        {
            $shopCart = $dataXml["DataItems"]["dataItem"];
        }
        $shop = new \xweb_plugins\Shop();
        $orderNameStyle = $shop->GetShopSettings("OrderNameStyle");
        
        $table = new \HtmlComponents\HtmlTable();
        $table->CssClass="table table-striped table-bordered table-hover";
        
        $th = new \HtmlComponents\HtmlTableTr();
        
        $td = new \HtmlComponents\HtmlTableTd();
        $td->Html = $this->GetWord("word786");
        $th->SetChild($td);
        
        $td = new \HtmlComponents\HtmlTableTd();
        $td->Html = $this->GetWord("word789");
        $th->SetChild($td);
        
        $td = new \HtmlComponents\HtmlTableTd();
        $td->Html = $this->GetWord("word787");
        $th->SetChild($td);
        
        $td = new \HtmlComponents\HtmlTableTd();
        $td->Html = $this->GetWord("word790");
        $th->SetChild($td);
        
        $table->SetChild($th);
        
        foreach ($shopCart as $row)
        {
            $generetedName = $orderNameStyle;
            $tr = new \HtmlComponents\HtmlTableTr;
            $items = $row["item"];
            $data = $items["data"]["items"];
            $items = array_merge($items,$data);
            foreach ($items as $key => $value)
            {
                $generetedName = str_replace("{".$key."}", $value, $generetedName);
            }
            // name
            $td = new \HtmlComponents\HtmlTableTd();
            $td->Html = $generetedName;
            $tr->SetChild($td);
            // count
            $td = new \HtmlComponents\HtmlTableTd();
            $td->Html = $items["Count"];
            $tr->SetChild($td);
            // price 
            $td = new \HtmlComponents\HtmlTableTd();
            $td->Html = \Utils\StringUtils::PriceFormat($items["Price1ks"], $dataXml["PriceFormat"], $dataXml["Currency"]) ;
            $tr->SetChild($td);
            // pricecount
            $td = new \HtmlComponents\HtmlTableTd();
            $td->Html = \Utils\StringUtils::PriceFormat($items["PriceCount"], $dataXml["PriceFormat"], $dataXml["Currency"]) ;
                    
            $tr->SetChild($td);
            $table->SetChild($tr);
            
        }
        // payment 
        $tr = new \HtmlComponents\HtmlTableTr();
        $td = new \HtmlComponents\HtmlTableTd();
        $td->Html =  $this->GetWord("word793");
        $td->AddAtrribut("colspan", 3);
        $tr->SetChild($td);
        
        $td = new \HtmlComponents\HtmlTableTd();
        $td->Html = \Utils\StringUtils::PriceFormat($dataXml["SumaPrice"], $dataXml["PriceFormat"], $dataXml["Currency"]) ;
        $tr->SetChild($td);
        
        $table->SetChild($tr);
        
        return $table->RenderHtml();
    }
    
    
}
