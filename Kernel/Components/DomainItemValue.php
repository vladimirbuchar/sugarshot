<?php
namespace Components;
class DomainItemValue extends UserComponents{
    
    public $ValueIdetificator="";
    public $LoadId = 0;
    
    
    public function __construct() {
        
        $this->Type = "DomainItemValue";
        parent::__construct();
    }     
    
    public function GetComponentHtml()
    { 
        if (\Utils\StringUtils::ContainsString($this->LoadId, "<![CDATA["))
        {
            $this->LoadId = \Utils\StringUtils::RemoveString($this->LoadId, "<![CDATA[");
            $this->LoadId = \Utils\StringUtils::RemoveString($this->LoadId, "]]>");
        }
        $ud =  \Model\UserDomainsValues::GetInstance();
        $data = $ud->UserDomainItemByObjectId($this->LoadId, self::$UserGroupId, $this->LangId, $this->WebId,$this->ValueIdetificator);
        
        if(!empty($data))
        {
            $xml = $data[0]["ValueList"];
            if ($xml =="<items></items>")
                $xml = "";
            $dataXml = $data[0]["Data"];
            $dataXml = \Utils\ArrayUtils::RemoveCData($dataXml);
            $xmlData = \Utils\ArrayUtils::XmlToArray($dataXml,"SimpleXMLElement",LIBXML_NOCDATA);
            
            $outHtml = "";
            
            
            
            $id  = $data[0]["Id"];
            $type = $data[0]["Type"];
            if(!empty($xml))
            {
                
                $xmlValueList = \Utils\ArrayUtils::XmlToArray($xml,"SimpleXMLElement",LIBXML_NOCDATA);
                
                
                
                if (!empty($xmlValueList) && !empty($xmlData))
                {
                    if (!empty($xmlValueList["item"]))
                    {
                        
                        $xmlValueList = $xmlValueList["item"];
                    }
                    else 
                    {
                        $this->IsEmptyComponent = true;
                    }
                    $count = 0;
                    foreach ($xmlValueList as $value)
                    {
                        $itemId =$type."_".$value["itemValue"]."_".$id;
                        
                        if (!empty($xmlData[$itemId]))
                        {
                            $count++;
                            $outHtml = $outHtml."\n".$this->GetWord($value["itemText"]);
                        }
                    }
                    if ($count == 0)
                    {
                        $this->IsEmptyComponent = true;
                    }
                }
                else 
                {
                    $this->IsEmptyComponent = true;
                }
            }
            else 
            {
                if ($data[0]["Type"] == "domainData")
                {
                     /** 
                     * @var Model\UserDomains
                     */
                    $userDomain = \Model\UserDomains::GetInstance();
                    $userDomain->GetObjectById($data[0]["Domain"]);
                    /** 
                     * @var Model\UserDomainsValues
                     */
                    
                     $dv =  \Model\UserDomainsValues::GetInstance();
                     $values = $dv->GetDomainValueConditon($userDomain->DomainIdentificator,0,"");
                     
                }
                else 
                {
                    $this->IsEmptyComponent = true;
                }
            }
        }
        else 
        {
            
            $this->IsEmptyComponent = true;
            
        }
        
        
        
        
        
    }
    
    
}
