<?php

namespace Components;

class DomainItemSelectBox extends UserComponents {

    public $SelectId = "";
    public $ValueIdetificator = "";
    public $NameIdetificator = "";
    public $LoadValues = false;
    public $DomainIdentificator = "";
    public $DefalutSelectedValue ="";
    public $SelectedValue ="";

    public function __construct() {

        $this->Type = "DomainItemSelectBox";
        
        parent::__construct();
    }

    public function GetComponentHtml() {
        $selectBox = new \HtmlComponents\Select();
        $selectBox->DataRole = "none";
        $selectBox->Id = $this->SelectId;
        $selectBox->CssClass = $this->CssClass;
        $ud = \Model\UserDomainsValues::GetInstance();
        

        $option = new \HtmlComponents\Option();
        $option->Value = "";
        $option->Html = "----";
        $selectBox->SetChild($option);
        if (!$this->LoadValues) {
            $data = $ud->UserDomainItemsBySeoUrl($_GET["seourl"], self::$UserGroupId, $this->LangId, $this->WebId, $this->ValueIdetificator);
            if (!empty($data)) {
                $xml = $data[0]["ValueList"];
                $dataXml = $data[0]["Data"];
                $id = $data[0]["Id"];
                $type = $data[0]["Type"];
                if (!empty($xml)) {
                    $xmlValueList = \Utils\ArrayUtils::XmlToArray($xml, "SimpleXMLElement", LIBXML_NOCDATA);
                    $dataXml = \Utils\ArrayUtils::RemoveCData($dataXml);
                    $xmlData = \Utils\ArrayUtils::XmlToArray($dataXml, "SimpleXMLElement", LIBXML_NOCDATA);
                    if (!empty($xmlValueList) && !empty($xmlData)) {
                        if (!empty($xmlValueList["item"])) {

                            $xmlValueList = $xmlValueList["item"];
                        } else {
                            $this->IsEmptyComponent = true;
                        }
                        $count = 0;
                        foreach ($xmlValueList as $value) {
                            $itemId = $type . "_" . $value["itemValue"] . "_" . $id;

                            if (!empty($xmlData[$itemId])) {

                                $count++;
                                $option = new \HtmlComponents\Option();
                                $option->Value = $value["itemValue"];
                                $option->Html = $this->GetWord($value["itemText"]);
                                $selectBox->SetChild($option);
                            }
                        }
                        if ($count == 0) {
                            $this->IsEmptyComponent = true;
                        }
                    } else {
                        $this->IsEmptyComponent = true;
                    }
                } else {
                    $this->IsEmptyComponent = true;
                }
            } else {

                $this->IsEmptyComponent = true;
            }
        }
        else{
            if (empty($this->SelectedValue) || $this->SelectedValue == "{". $this->SelectId."}")
                $this->SelectedValue = $this->DefalutSelectedValue;
            
            $domainInfo =  new \Model\UserDomains();
            $info = $domainInfo->GetDomainInfo($this->DomainIdentificator);
            $data = $ud->GetDomainValueList($info["Id"]);
            foreach ($data as $row)
            {
                $option = new \HtmlComponents\Option();
                $option->Value = $row[$this->ValueIdetificator];
                $option->Html = $row[$this->NameIdetificator];
                $option->Selected = $option->Value == $this->SelectedValue ? 'selected = "selected"' : "";
                $selectBox->SetChild($option);
            }
            
            
        }
        return $selectBox->RenderHtml();
    }

}
