<?php
namespace Components;
use Utils\ArrayUtils;
class Filtr extends UserComponents{
    public $FiltrDivId = "";
    public $FiltrDomain ="";
    public function __construct() {
        
        $this->Type = "Filtr";
        parent::__construct();
    }
    
    public function GetComponentHtml()
    {
        $userDomainItems =  new \Objects\UserDomains();
        $items = $userDomainItems->GetUserDomainItemByIdentificator($this->FiltrDomain,"filtr");
        $udv =  new \Objects\UserDomains();
        $html = "";
        $arrayPosition = -1;
        $seoUrl = $data[0]["SeoUrl"];
        
        foreach ($items as $row)
        {
            $columnName = $row["Identificator"];
            $type =$row["Type"];
            $arrayPosition++;
            if ($row["FiltrSettings"] == "ValueList")
            {
                $divSlider = new Div();
                $values  = $this->GetMinValue($id);
                $min = $values["MinValue"];
                $max = $values["MaxValue"];
                $div = new Div();
                $sliderId = "slider-".$row["Id"];
                $div->Id =$sliderId ;
                $divSlider->Js = "
                    <script type='text/javascript'>
                        $(document).ready(function(){
                            var slider = document.getElementById('$sliderId');
                            noUiSlider.create(slider, {
                            start: [$min, $max],
                            step: 1,
                            connect: true,
                            range: {
                            'min': $min,
                            'max': $max
                            }
                            });
                            var skipValues = [
                            document.getElementById('MaxValue'),
                                document.getElementById('MinValue')
                                
                            ];
                            slider.noUiSlider.on('update', function( values, handle ) {
                            skipValues[handle].innerHTML = values[handle];
                            
                            
                    });
                         slider.noUiSlider.on('change', function( values, handle ) {
                            SetFiltrBetween('$columnName',values[0],values[1],'$arrayPosition','$seoUrl','$this->FiltrDivId');
                    });

                     });
                    </script>
            ";
                $spanMax = new Span();
                $spanMax->Id = "MinValue";
                $spanMin = new Span();
                $spanMin->Id = "MaxValue";
                $divSlider->SetChild($spanMin);
                $divSlider->SetChild($div);
                $divSlider->SetChild($spanMax);
                $html.= $divSlider->RenderHtml();
            }
            else if ($row["FiltrSettings"] == "SearchInput" || $row["FiltrSettings"] == "StartLike" ||  $row["FiltrSettings"] == "EndLike" ||  $row["FiltrSettings"] == "Like" || $row["FiltrSettings"]=="<" || $row["FiltrSettings"]=="<=" || $row["FiltrSettings"]==">" || $row["FiltrSettings"]==">=")
            {
                $textbox = new Textbox();
                $itemid = "textbox-".$row["Id"];
                $textbox->Id = $itemid;
                $mode = "";
                if ($row["FiltrSettings"] == "SearchInput")
                    $mode="TEXT";
                else 
                    $mode = $row["FiltrSettings"];
                $textbox->Js = "<script type='text/javascript'>
                    
                        $(document).ready(function(){
                             $('#$itemid').change(function(){
                                 SetFilterText('$columnName',$(this).val(),'$arrayPosition','$seoUrl','$this->FiltrDivId','$mode');
                                 });
                            });  
                          </script>";
                $html.= $textbox->RenderHtml();
            }
            else if ($row["FiltrSettings"] == "SelectMN" ){
                $isColor = $type =="color";
                $values = array();
                $groupName = $columnName;
                $isdomain = $row["Type"] == "domainData" ;
                $domainName = "";
                if ($isColor)
                    $values = $this->GetDistinctValues($id, $columnName);
                else
                {
                    if ($isdomain)
                    {
                        $domainInfo = UserDomains::GetInstance();
                        $domainInfo->GetObjectById($row["Domain"]);
                        $values = $udv->GetDomainValueList($row["Domain"]);
                        $showName = $domainInfo->ShowNameInSubDomain;
                        $domainName = $domainInfo->DomainIdentificator;
                        $out = array();
                        $x = 0;
                        foreach ($values as $rowInDomain)
                        {
                            $tmpShowName = $showName;
                            foreach ($rowInDomain as $kx =>$vy)
                            {
                                $tmpShowName = str_replace("{".$kx."}", $vy, $tmpShowName);
                            }
                            $out[$x]["itemValue"] = $rowInDomain["ObjectId"];
                            $out[$x]["itemText"] = $tmpShowName;
                            $x++;
                        }
                        $values = $out;
                    }
                    else 
                    {
                        $values = ArrayUtils::XmlToArray($row["ValueList"]);
                        $values =$values["item"];
                    }
                }
                for ($i = 0; $i < count($values);$i++ )
                { 
                    $val = $isColor ? $values[$i]: $values[$i]["itemValue"];
                    $div = new Div();
                    $label = new Label();
                    if ($isColor)
                    { 
                        $colorDiv = new Div();
                        $colorDiv->Style = "background-color: $val";
                        $colorDiv->CssClass = "colorFiltr";
                        $label->SetChild($colorDiv);
                    }
                    else 
                    {
                        $label->Html = $values[$i]["itemText"];;
                    }
                    $div->SetChild($label);
                    
                    $checkbox=   new Checkbox();
                    $itemid =  "SelectMN-". $row["Id"].$i;
                    
                    $checkbox->Id = $itemid;
                    $columnName = $isColor ? $columnName:  ($isdomain ?$domainName."_".$val: "checkbox_".$val);
                    if (!$isdomain)
                    {
                        $val = $isColor ? $val: 1;
                        $checkbox->Js= "<script type='text/javascript'>
                    
                        $(document).ready(function(){
                             $('#$itemid').click(function(){
                                 SetFilterMN('$columnName',$(this).is(':checked'),'$val','$arrayPosition','$seoUrl','$this->FiltrDivId','$groupName');
                                 });
                            });  
                          </script>";
                    }
                    else 
                    {
                        
                        $checkbox->Js= "<script type='text/javascript'>
                    
                        $(document).ready(function(){
                             $('#$itemid').click(function(){
                                 SetFilterMN('$columnName',$(this).is(':checked'),'$val','$arrayPosition','$seoUrl','$this->FiltrDivId','');
                                 });
                            });  
                          </script>";
                    }
                    $div->SetChild($checkbox);
                    $html .= $div->RenderHtml();
                    $arrayPosition++;
                        
                }
                
            }
            else if ($row["FiltrSettings"] == "Select1N"){
                $values = array();
                $groupName = $columnName;
                $isdomain = $row["Type"] == "domainData" ;
                $domainName = "";
                if ($isdomain)
                {
                    $domainInfo = UserDomains::GetInstance();
                    $domainInfo->GetObjectById($row["Domain"]);
                    $values = $udv->GetDomainValueList($row["Domain"]);
                    $showName = $domainInfo->ShowNameInSubDomain;
                    $domainName = $domainInfo->DomainIdentificator;
                    $out = array();
                    $x = 0;
                    foreach ($values as $rowInDomain)
                    {
                        $tmpShowName = $showName;
                        foreach ($rowInDomain as $kx =>$vy)
                        {
                            $tmpShowName = str_replace("{".$kx."}", $vy, $tmpShowName);
                        }
                        $out[$x]["itemValue"] = $rowInDomain["ObjectId"];
                        $out[$x]["itemText"] = $tmpShowName;
                        $x++;
                    }
                    $values = $out;
                }
                else 
                {
                    $values = ArrayUtils::XmlToArray($row["ValueList"]);
                    $values =$values["item"];
                }
                
                for ($i = 0; $i < count($values);$i++ )
                { 
                    $val =  $values[$i]["itemValue"];
                    $div = new Div();
                    $label = new Label();
                    $label->Html = $values[$i]["itemText"];
                    
                    $div->SetChild($label);
                    
                    $checkbox=   new RadioButton();
                    $itemid =  "Select1N-". $row["Id"].$i;
                    $checkbox->Name = $groupName;
                    $checkbox->Id = $itemid;
                    $columnName = $isdomain ? $domainName : "radio_".$val ;
                    if (!$isdomain)
                    {
                        $val = 1;
                        $checkbox->Js=  
                            "<script type='text/javascript'>
                                $(document).ready(function(){
                                    $('#$itemid').click(function(){
                                         SetFilter1N('$columnName',$(this).is(':checked'),'$val','$arrayPosition','$seoUrl','$this->FiltrDivId');
                                    });
                                });  
                            </script>";
                    }
                    else 
                    {
                        $checkbox->Js=  
                            "<script type='text/javascript'>
                                $(document).ready(function(){
                                    $('#$itemid').click(function(){
                                        SetFilter1N('$columnName','$val','$val','$arrayPosition','$seoUrl','$this->FiltrDivId');
                                    });
                                });  
                            </script>";
                    }
                    $div->SetChild($checkbox);
                    $html .= $div->RenderHtml();
                    
                        
                }
                
            }
            
        }
        return $html;
    }
    
    
    private function  GetMinValue($parentId)
    {
        $content =  new \Objects\Content();
        return $content->SetFiltr($this->LangId,$parentId,"Price",  FiltrModes::$MinMax);
        
    }
    private function GetDistinctValues($parentId,$columnName)
    {
        $content =  new \Objects\Content();
        return $content->SetFiltr($this->LangId,$parentId,$columnName,  FiltrModes::$DistinctValues);
    }
    
        
    
    
    
    
}
