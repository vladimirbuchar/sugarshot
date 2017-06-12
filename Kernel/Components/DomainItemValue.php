<?php
namespace Components;
class DomainItemValue extends UserComponents{
    
    public $ValueIdetificator="";
    
    
    public function __construct() {
        
        $this->Type = "DomainItemValue";
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    {
        /*$selectBox = new \HtmlComponents\Select();
        $selectBox->DataRole ="none";
        $selectBox->Id = $this->SelectId;
        $selectBox->CssClass = $this->CssClass;*/
        $ud =  \Model\UserDomainsValues::GetInstance();
        $data = $ud->UserDomainItemsBySeoUrl($_GET["seourl"], self::$UserGroupId, $this->LangId, $this->WebId,$this->ValueIdetificator);
        //print_r($data);
        /*$option = new \HtmlComponents\Option();
        $option->Value="";
        $option->Html ="----";
        $selectBox->SetChild($option);
        if(!empty($data))
        {
            $xml = $data[0]["ValueList"];
            $dataXml = $data[0]["Data"];
            $id  = $data[0]["Id"];
            $type = $data[0]["Type"];
            if(!empty($xml))
            {
                $xmlValueList = \Utils\ArrayUtils::XmlToArray($xml);
                $dataXml = \Utils\ArrayUtils::RemoveCData($dataXml);
                $xmlData = \Utils\ArrayUtils::XmlToArray($dataXml);
                if (!empty($xmlValueList) && !empty($xmlData))
                {
                    if (!empty($xmlValueList["item"]))
                        $xmlValueList = $xmlValueList["item"];
                    foreach ($xmlValueList as $value)
                    {
                        $itemId =$type."_".$value["itemValue"]."_".$id;
                        if (!empty($xmlData[$itemId]))
                        {
                            $option = new \HtmlComponents\Option();
                            $option->Value = $value["itemValue"];
                            $option->Html = $this->GetWord($value["itemText"]);
                            $selectBox->SetChild($option);
                            
                        }
                    }
                }
            }
        }
        return $selectBox->RenderHtml();*/
    }
    
    
}
