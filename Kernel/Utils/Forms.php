<?php
namespace Utils;
use Utils\ArrayUtils;
use HtmlComponents\Button;
use HtmlComponents\Calendar;
use HtmlComponents\Checkbox;
use HtmlComponents\Datalist;
use HtmlComponents\Div;
use HtmlComponents\HiddenInput;
use HtmlComponents\Label;
use HtmlComponents\Textbox;
use Components\HtmlEditor;
use HtmlComponents\Textarea;
use Utils\StringUtils;
use HtmlComponents\RadioButton;
use HtmlComponents\HtmlImage;
use HtmlComponents\Link;
use Components\FileUploader;
use Utils\Mail;
use HtmlComponents\Option;
use Utils\Files;



class Forms extends GlobalClass { 
    
    private $_errors = array();
    private $_isError = false;
    public $ShowElementId = "";
    public $ShowLabel = true; 

    public function GenerateFrontEndForm($id,$editId = 0,$showElementId = "",$renderResult = false) {
        
        
        $this->ShowElementId = $showElementId;
        
        $contentVersion =  new \Objects\Content();
        $data = $contentVersion->GetFormDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        
        if (empty($data))
            return "";
        $template = $contentVersion->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId, $data[0]["TemplateId"], 0);
        $xmlData = $data[0]["Data"];
        $xml = simplexml_load_string($xmlData);
        $sendButtonValue = "";
        $domain =  \Model\UserDomains::GetInstance();
        
        $domain->GetObjectById($template[0]["DomainId"], true);
        $useBootstrap = false;
        $buttonCssClass = "";
        $catcha = false;
        
        $mode = "AllOne";
        $nextButtonName ="";
        $previesButtonName ="";
        $wizardvalidateByStep = false;
        $showResult= false;
        $resultTitle = "";
        $generateName = false;
        foreach ($xml as $key => $value) {
            if ($key == "ButtonSendForm") {
                $sendButtonValue = trim($value);
            } else if ($key == "UseBootstrap") {
                
                $useBootstrap = trim($value) == "1" ? true : false;
            } else if ($key == "ButtonCssClass") {
                $buttonCssClass = trim($value);
            }
            else if ($key == "UseCaptcha")
            {
                $catcha = trim($value) == "1" ? true : false;
            }
            else if ($key =="FormMode")
            {
                $mode = trim($value);
            }
            else if ($key=="ButtonNextText")
            {
                $nextButtonName = trim($value);
            }
            else if ($key=="ButtonPrevText")
            {
                $previesButtonName = trim($value);
            }
            else if ($key=="FormValidationMode")
            {
                $wizardvalidateByStep = trim($value) == "ValidateInStep" ? true :false;
            }
            else if ($key=="ShowResult")
            {
                $showResult = trim($value) == "1" ? true :false;
            }
            else if ($key=="ResultTitle")
            {
                $resultTitle = trim($value);
            }
            else if ($key =="ObjectName")
            {
                $generateName = trim($value) == "1" ? true :false;
            }
        }
        
        $button = new Button("input");
        $button->Id = "sendbutton-$id";
        $button->OnClick = "SendUserForm('$id')";
        $button->Value = $sendButtonValue;
        $button->Html = $sendButtonValue;
        $button->CssClass = "sendbutton noClear ".$buttonCssClass;
        
        $captchaDiv = new Div();
        $captchaDiv->CssClass ="form-group";
        $captchaLabel = new Label();
        $captchaLabel->CssClass="control-label col-sm-2";
        $captchaLabel->For = "form".$id."_captcha";
        $captchaLabel->Html= $this->GetWord("word492");
        $divCaptchTextbox = new Div();
        $divCaptchTextbox->CssClass="col-sm-3";
        $captchaTextbox = new Textbox();
        $captchaTextbox->Id ="form".$id."_captcha";
        $captchaTextbox->CssClass="form-control  required";
        $captchaTextbox->IsRequired = true;
        $divCaptchaCode = new Div();
        $divCaptchaCode->CssClass="col-sm-3";
        $img = new HtmlImage();
        $img->Src = $this->GenerateCaptcha($id);
        $img->Id = "captchaform$id";
        $divCaptchaCode->Html = $img->RenderHtml();
        
        $divNewCaptchaCode = new Div();
        $divNewCaptchaCode->CssClass ="col-sm-3";
        $newCaptchaCode=  new Link();
        $newCaptchaCode->Html = "Vygenerovat";
        $newCaptchaCode->Href ="#";
        $newCaptchaCode->OnClick="RegenerateCaptcha('".$id."');return false;";
        $divNewCaptchaCode->SetChild($newCaptchaCode);
        $divCaptchTextbox->SetChild($captchaTextbox);
        $captchaErrorDiv = new Div();
        $captchaErrorDiv->CssClass="col-sm-1 formErrors";
        $captchaErrorDiv->Id = "form".$id."_captcha_error";
        
        $captchaDiv->SetChild($captchaLabel);
        $captchaDiv->SetChild($divCaptchTextbox);
        $captchaDiv->SetChild($divCaptchaCode);
        $captchaDiv->SetChild($divNewCaptchaCode);
        $captchaDiv->SetChild($captchaErrorDiv);
        $editData = "";
        $nameObject = "";
        $html = "";
        $objectId = new HiddenInput();
        $objectId->Id = "ObjectId";
        $objectId->Value = 0;
        if ($editId > 0) 
        {
            $userdata =  new \Objects\Content();
            $dataUser = $userdata->GetUserItemDetail($editId, self::$UserGroupId, $this->WebId, $this->LangId);
            $editData = $dataUser[0]["Data"];
            $nameObject = $dataUser[0]["Name"];
            $objectId->Value = $editId;
        }
        $html .=$objectId->RenderHtml();
        
        
        if ($useBootstrap) {
            if ($mode == "AllOne")
            {
                
                $div = new Div();
                $div->Id = "form-$id";
                $div2 = new Div();
                $div2->CssClass = "form-group";
                $div2->SetChild($button);
                
                if ($generateName)
                {
                    $divName = new Div();
                    $divName->CssClass="form-group";
                    $label = new Label();
                    $label->CssClass="control-label col-sm-2";
                    $label->For="ObjectName";
                    $label->Html = $this->GetWord("word698");
                    $divName->SetChild($label);
                    $divObjName = new Div();
                    $divObjName->CssClass="col-sm-10";
                    $inputName = new Textbox();
                    $inputName->Id="ObjectName";
                    $inputName->Value = $nameObject;
                    $inputName->CssClass="form-control";
                    $divObjName->SetChild($inputName);
                    $divName->SetChild($divObjName);
                    $html .= $divName->RenderHtml();        
                }
                    
                $html .= $this->GetUserDomain($domain->DomainIdentificator,0,true,$editData,false,"","",0);
                if ($catcha)
                {   
                    $html.= $captchaDiv->RenderHtml();
                }
                $html .= $div2->RenderHtml($div2);
                $div->Html = $html;
                return $div->RenderHtml($div);
            } 
            if ($mode =="Wizard")
            {
                $userDomainGroups =  \Model\UserDomainsGroups::GetInstance();
                $groups = $userDomainGroups->SelectByCondition("Deleted = 0 AND DomainId = ".$template[0]["DomainId"]);
                $tabHolder =  new Div();
                $tabHolder->CssClass ="row wizard";
                $tabHolder->Id = "form-$id";
                $tabHeader = new Div();
                $tabHeader->CssClass ="row";
                $tabContent = new Div();
                $tabContent->CssClass ="row";
                $step = 1;
                $resultHtml = "";
                $isEnd = false;
                foreach ($groups as $group)
                {
                    
                    $isFirst = $step == 1 ? true: false;
                    $tabItemHeader = new Div();
                    $tabItemHeader->CssClass= "tabItems tabItem-$step";
                    $tabLink = new Link();
                    $tabLink->Html =$group["GroupName"];
                    $tabLink->OnClick =  "GoToTab('$step');return false;";
                    
                    $tabItemHeader->SetChild($tabLink);
                    $tabHeader->SetChild($tabItemHeader);
                    $tabItemContent = new Div();
                    
                    $tabHtml = $this->GetUserDomain($domain->DomainIdentificator, 0, $step == 1, $editData, false, "", "", $group["Id"]);
                    $tabItemContent->Html = $tabHtml;
                    $tabItemContent->Id = "step-".$group["Id"];
                    $tabItemContent->CssClass =  $wizardvalidateByStep ? "step-$step tabSteps validateByStep" : "step-$step tabSteps " ;
                    $tabItemContent->AddAtrribut("data-formId", $id);
                    $tabItemContent->AddAtrribut("data-stepId", $group["Id"]);
                    $tabItemContent->Id = "step-$step";
                    if (!$isFirst)
                        $tabItemContent->Style="display:none;";
                    $tabContent->SetChild($tabItemContent);
                    $step++;
                    if ($showResult)
                    {
                        
                        $resultHtml .= "<h3>".$group["GroupName"]."</h3>";
                        
                        $resultHtml .= $this->GetUserDomain($domain->DomainIdentificator, 0, false, "", false, "", "", $group["Id"], true);
                    }
                    
                }
                if ($showResult)
                {
                    $tabItemHeader = new Div();
                    $tabItemHeader->CssClass= "tabItems tabItem-$step";
                    $tabLink = new Link();
                    $tabLink->Html =$resultTitle;
                    $tabLink->OnClick =  "GoToTab('$step');return false;";
                    $tabItemHeader->SetChild($tabLink);
                    $tabHeader->SetChild($tabItemHeader);
                    $tabItemContent = new Div();
                    $tabItemContent->Id = "step-result";
                    $tabItemContent->CssClass =   "step-$step tabSteps ";
                    $tabItemContent->Id = "step-$step";
                    $tabItemContent->Style="display:none;";
                    $tabItemContent->Html= $resultHtml;
                    
                    $tabContent->SetChild($tabItemContent);
                }
                
                $divButtons = new Div();
                
                $prevButton = new Button();
                $prevButton->Value = $previesButtonName;
                $prevButton->Disabled = true;
                $prevButton->CssClass = "prevButton";
                $prevButton->OnClick="PrevButtonClick()";
                
                $divButtons->SetChild($prevButton);
                $nextButton = new Button();
                $nextButton->CssClass = "nextButton";
                $nextButton->Value = $nextButtonName;
                $nextButton->OnClick="NextButtonClick()";
                
                
                $divButtons->SetChild($nextButton);
                if ($step > 1)
                {
                    $button->Disabled =true;
                    
                }
                $divButtons->SetChild($button);
                $tabHolder->SetChild($tabHeader);
                $tabHolder->SetChild($tabContent);
                $tabHolder->SetChild($divButtons);
                
                $activeStep = new HiddenInput();
                $activeStep->Id = "ActiveStep";
                $activeStep->Value = 1;
                $activeStep->CssClass ="noDatabase";
                $tabHolder->SetChild($activeStep);
                return $tabHolder->RenderHtml();
            }
        } else {
            
            $htmlTemplate = $template[0]["Data"];
            //$domainIdentificator, $dataId = 0, $addDomainIdentificator = true, $data = "", $disabled = false, $templateHtml = "",$idPrefix="",$groupId = 0,$renderResultForm = false
            
            $html = $this->GetUserDomain($domain->DomainIdentificator, 0, true, $editData, false, $htmlTemplate,"",0);
            $html = str_replace("{FormButton}", $button->RenderHtml($button), $html);
            if ($catcha)
                $html = str_replace("{Captcha}", $captchaDiv->RenderHtml(), $html);
            $div = new Div();
            $div->Id = "form-$id";
            $div->CssClass = "formClass";
            $div->Html = $html;
            $html = $div->RenderHtml($div);
            return $html;
        }
    }
    
    public function ValidateStep($formId,$stepId)
    {
        
    }
    
    private function GenerateAddictionScript($list)
    {
        $script ="";
        foreach ($list as $row)
        {
            $itemType = $row["Item1Type"];
            $itemId = $row["Item1Identificator"];
            $ruleName = $row["RuleName"];
            $item1Value = $row["Item1Value"];
            
            if (empty($item1Value) && StringUtils::ContainsString($row["Item1"], "-"))
            {
                $ar = explode("-", $row["Item1"]);
                $item1Value = $ar[1];
            }
            $itemX = $row["ItemXIdentificator"];
            $itemXValue = $row["ItemXValue"];
            
            if (empty($itemXValue) && StringUtils::ContainsString($row["ItemX"], "-"))
            {
                $ar = explode("-", $row["ItemX"]);
                $itemXValue = $ar[1];
            }
            
            $itemXType = $row["ItemXType"];
            $actionName = $row["ActionName"];
            $isDomain1 = $row["IsDomain1"] == 1 || $row["IsDomain1"] == "1" ? true: false;;
            $isDomainX = $row["IsDomainX"] == 1 || $row["IsDomainX"] == "1" ? true: false;;
            
            if ($itemType == "textbox" || $itemType == "color" || $itemType == "email" || $itemType == "hidden" || $itemType == "number" || $itemType == "password" || $itemType == "search" || $itemType == "tel" || $itemType == "url" || $itemType == "range")
            {
                $script .= $this->GenerateActionScript($itemId,$ruleName,$item1Value,$actionName,$itemX,$itemXValue);
            }
            if ($isDomain1)
            {
                
                $domainInfo1 = \Model\UserDomains::GetInstance();
                $domainInfo1->GetObjectById($row["DomainId1"],true);
                $domainIdent1 = $domainInfo1->DomainIdentificator;
                if ($isDomainX)
                {
                    $domainInfoX = \Model\UserDomains::GetInstance();
                    $domainInfoX->GetObjectById($row["DomainIdX"],true);
                    $itemX = $domainInfoX->DomainIdentificator;
                }
                $script.= $this->GenerateActionScript($domainIdent1,$ruleName,$item1Value,$actionName,$itemX,$itemXValue,true);
                
            }
            
            
        }
        return $script;
    }
    
    private function GenerateActionScript($itemId,$ruleName,$item1Value,$actionName,$itemX,$itemXValue,$moreItems = false)
    {
        $script ="";
        if ($moreItems)
            $script .= "$('.$itemId').change(function(){\n";
        else
            $script .= "$('#$itemId').change(function(){\n";
        
        if ($ruleName == "==" || $ruleName=="<" || $ruleName=="<=" || $ruleName==">" || $ruleName ==">=" || $ruleName =="!=" )
        {
            $script .= "var value = $(this).val();\n";
            if ($actionName == "hide" || $actionName == "show")
            {
                $script .= $this->GenerateShowHide($actionName,$itemX,$ruleName,$item1Value);
            }
            if ($actionName == "setvalue")
            {
                $script .= $this->SetValueScript($itemX,$itemXValue,$ruleName,$item1Value);
            }   
        }
        if ($ruleName =="selected")
        {
            $script .= "var value = $(this).val();\n";
            $script .= $this->GenerateShowHide($actionName,$itemX,"==",$item1Value,$itemXValue);   
        }
        $script .="});\n";  
        return $script;
    }
    
    private function GenerateShowHide($actionName,$itemX,$ruleName,$item1Value,$itemXValue)
    {
        $script = "";
        $script .= "if (value $ruleName '$item1Value')\n";
        if ($actionName == "hide")
            {
                $script .= "{ $('.addiction-$itemX-$itemXValue').val(); $('.addiction-$itemX-$itemXValue').removeAttr('checked'); $('.addiction-$itemX-$itemXValue').hide();}\n";
                $script .= "else { $('.addiction-$itemX-$itemXValue').show();}\n";
            }
            if ($actionName == "show")
            {
                $script .= "$('.addiction-$itemX-$itemXValue').show();\n";
                $script .= "else{ $('.addiction-$itemX-$itemXValue').hide(); $('.addiction-$itemX-$itemXValue').removeAttr('checked');  $('.addiction-$itemX-$itemXValue').val();}\n";
            }
        
        return $script;
    }
    
    private function SetValueScript($itemX,$itemXValue,$ruleName,$item1Value)
    {
        $script ="";
        $script .= "if (value $ruleName '$item1Value'){\n";
        return $script."$('#$itemX').val('$itemXValue');}\n";
    }

    public function GetUserDomain($domainIdentificator, $dataId = 0, $addDomainIdentificator = true, $data = "", $disabled = false, $templateHtml = "",$idPrefix="",$groupId = 0,$renderResultForm = false){ 
        $generateFromUserTemplate = false;
        if (!empty($templateHtml)) {
            $generateFromUserTemplate = true;
        }
        $userDomainItem = new \Objects\UserDomains();;
        $domainItems = $userDomainItem->GetUserDomainItems($domainIdentificator,$groupId,$this->ShowElementId);
        
        $ud = new \Objects\UserDomains();
        $udi = $ud->GetDomainInfo($domainIdentificator);
        $addiction = new \Objects\UserDomains();
        $addictionList = $addiction->GetAddictionDomain($udi["Id"]);
        $html = "";
        $script = "";
        $script .=$this->GenerateAddictionScript($addictionList);
        $domainValue = array();
        if ($dataId > 0) {
            
            $userDomainValue = new \Objects\UserDomains();
            $domainValue = $userDomainValue->GetDomainValue($domainIdentificator, $dataId);
            $domainValue = ArrayUtils::ValueAsKey($domainValue, "ItemId",true);
            $hiddenItem = new HiddenInput();
            $hiddenItem->Id = "ObjectId_$domainIdentificator";
            $hiddenItem->Value = $dataId;
            $html.= $hiddenItem->RenderHtml($hiddenItem);
        }
        if ($addDomainIdentificator) {
            $hiddenItem = new HiddenInput();
            if ($dataId > 0) {
                $hiddenItem->Id = "DomainIdentificator_$domainIdentificator";
            } else {
                $hiddenItem->Id = "DomainIdentificator";
            }
            $hiddenItem->CssClass = "noClear";

            $hiddenItem->Value = $domainIdentificator;
            $html.= $hiddenItem->RenderHtml($hiddenItem);
        }

        $xml = null;
        if (!empty($data)) {
            $xml = simplexml_load_string($data);
        }
        
        $itemHtml = "";
         
        foreach ($domainItems as $dItem) { 
            
                 
            $visible = FALSE;
            $readOnly = true;
            $autoComplecte = $dItem["Autocomplete"] == 1 ? true : false;
            if ($this->IsFrontend) {
                $visible = $dItem["ShowInWeb"] == 1 ? true : false;
                $readOnly = $dItem["ShowInWebReadOnly"] == 1 ? true : false;
            } else {
                $visible = trim($dItem["ShowInAdmin"]) == 1 ? true : false;
                $readOnly = trim($dItem["ShowInAdminReadOnly"]) == 1 ? true : false;
            }

            if (!$visible) {
                continue;
            }
            $itemHtml = "";
            
            $value = $dItem["DefaultValue"];
            $valueKey = $dItem["Id"];
            $valueId = 0;
            if (!empty($data)) {
                $identificator = $dItem["Identificator"];
                $value = $xml->$identificator;
            } else if (!empty($domainValue)) {
                if (!empty($valueKey)) {
                    if (!empty($domainValue[$valueKey])) {
                        $value = $domainValue[$valueKey][0]["Value"];
                        $valueId = $domainValue[$valueKey][0]["ValueId"];
                    }
                }
            }
 
            $divFormGroup = new Div();
            $divFormGroup->CssClass = "form-group addiction-".$dItem["Identificator"];

            $label = new Label();
            if ($dataId > 0)
                $label->For = $idPrefix.$dItem["Identificator"] . "_" . $dItem["Id"] . "_" . $valueId;
            else
                $label->For = $idPrefix.$dItem["Identificator"];
            $label->Html = $dItem["ShowName"];
            $label->CssClass = "control-label col-sm-2";
            $htmlItemId = $idPrefix.$dItem["Identificator"];
            
            if ($dataId > 0) {
                $htmlItemId = $idPrefix.$itemIdentificator = $dItem["Identificator"] . "_" . $dItem["Id"] . "_" . $valueId;
            }
            if ($dItem["Type"] != "hidden" && !$generateFromUserTemplate && $dItem["Type"] != "domainData" && !$renderResultForm ) {
                if ($this->ShowLabel)
                {
                    $itemHtml .= $label->RenderHtml($label);
                }
                
            }

                
            $value = str_replace("<![CDATA[", "", $value);
            $value = str_replace("]]>", "", $value);
            $divFormItem = new Div();
            $divFormItem->CssClass = "col-sm-9";
            $cssClass = $dItem["CssClass"];
            $itemReadOnly = $dItem["NoUpdate"] == "1" ? true: false;
            
            //$script .= $this->GenerateAddictionScript($dItem["Id"],$addictionList,$dItem["Type"],$dItem["Identificator"],$addictionListItemX);
            if ($readOnly || $disabled) {

                $divFormItem->Html = $value;
                $itemHtml .= $divFormItem->RenderHtml($divFormItem);
            } 
            else if ($renderResultForm){
                
                if ($dItem["Type"] == "textbox" || $dItem["Type"] == "color" || $dItem["Type"] == "email" || $dItem["Type"] == "hidden" || $dItem["Type"] == "number" || $dItem["Type"] == "password" || $dItem["Type"] == "search" || $dItem["Type"] == "tel" || $dItem["Type"] == "url" || $dItem["Type"] == "range" || $dItem["Type"] == "textarea" || $dItem["Type"] == "calendar") {
                    $span = new \HtmlComponents\Span();
                    $span->Id= "result_".$htmlItemId; 
                    $itemHtml = str_replace("{".$htmlItemId."_result", $span->RenderHtml(), $itemHtml);
                }
                else if ($dItem["Type"] == "domainData") {
                    
                    
                    
                    $nDomain = \Model\UserDomains::GetInstance();
                    $nDomain->GetObjectById($dItem["Domain"], true);
                    if ($dItem["DomainSettings"] == "standard")
                    {   
                        $itemHtml.= $this->GetUserDomain($nDomain->DomainIdentificator, $dataId, $addDomainIdentificator, $data, $disabled, $templateHtml,"?".$nDomain->DomainIdentificator."?","",0,true);
                    }
                    else if ($dItem["DomainSettings"] == "1n" || $dItem["DomainSettings"] == "mn")
                    { 
                        
                        $userDomainData = new \Objects\UserDomains();
                        $domainData = $userDomainData->GetDomainValueList($nDomain->Id);
                        $showName = $nDomain->ShowNameInSubDomain;
                        $itemHtml .= $label->RenderHtml($label);
                        foreach ($domainData as $rowInDomain)
                        {
                            $tmpShowName = $showName;
                            foreach ($rowInDomain as $kx =>$vy)
                            {
                                $tmpShowName = str_replace("{".$kx."}", $vy, $tmpShowName);
                            }
                            
                            $idRadioButton = $nDomain->DomainIdentificator."_".$rowInDomain["ObjectId"];
                            $resultSpanId = $nDomain->DomainIdentificator."_result";
                            $span = new \HtmlComponents\Span();
                            $span->Id=$resultSpanId;
                            $itemHtml = str_replace("{".$resultSpanId."}", $span->RenderHtml(), $itemHtml);
                            
                          }
                          
                        }
                        
                        
                    }
                //}
            }
            
                
            else if ($dItem["Type"] == "textbox" || $dItem["Type"] == "color" || $dItem["Type"] == "email" || $dItem["Type"] == "hidden" || $dItem["Type"] == "number" || $dItem["Type"] == "password" || $dItem["Type"] == "search" || $dItem["Type"] == "tel" || $dItem["Type"] == "url" || $dItem["Type"] == "range" || $dItem["Type"] == "checkboxOneItem") {
                
                
                    $type = $dItem["Type"];
                    if ($type == "textbox") $type ="text";
                    else if ($type == "checkboxOneItem") $type ="checkbox";
                    $textBox = new Textbox();
                    $textBox->Id = $htmlItemId;
                    if ($type =="checkbox")
                    {
                        if ($value == 1 || $value == "1")
                        {
                            $textBox->Checked = true;
                        }
                    } else {
                        $textBox->Value = $value;
                    }
                    
                    $textBox->Type = $type;
                    
                    $textBox->Disabled = $itemReadOnly && !empty($value);
                    if (!empty($dItem["OnChangeEvent"]))
                        $textBox->OnChange = $dItem["OnChangeEvent"]."(this)";
                    
                    if ($autoComplecte)
                    {
                        $textBox->List = "list".$htmlItemId;
                        $dataList = new Datalist();
                        $dataList->Id = "list".$htmlItemId;
                        $dataList->Style="display:none;";
                        $userDomainsAutoComplete =  new \Objects\UserDomains();
                        $complList = $userDomainsAutoComplete->GetItemAutoComplected($dItem["Id"]);
                        foreach ($complList as $row)
                        {
                            $option = new Option();
                            $option->Value = $row["Value"];
                            $dataList->SetChild($option);
                        }
                        $itemHtml .= $dataList->RenderHtml();
                    }
                    if ($type =="checkbox")
                        $textBox->CssClass =  $cssClass;
                    else 
                        $textBox->CssClass = $generateFromUserTemplate ? $cssClass : "form-control $cssClass";
                    if ($dItem["Required"] == 1)
                    {
                        $textBox->CssClass .=" required";
                        $textBox->IsRequired = TRUE;
                    }
                    if ($dItem["MinLength"] > 0) {
                        $textBox->CssClass .=" validateminLength";
                        $validateLength = new HiddenInput();
                        $validateLength->Id = $htmlItemId . "-validateminLength";
                        $validateLength->Value = $dItem["MinLength"];
                        $divFormItem->SetChild($validateLength);
                    }
                    if ($dItem["MaxLength"] > 0) {
                        $textBox->CssClass .=" validatemaxLength";
                        $validateLength = new HiddenInput();
                        $validateLength->Id = $htmlItemId . "-validatemaxLength";
                        $validateLength->Value = $dItem["MaxLength"];
                        $divFormItem->SetChild($validateLength);
                    }
                    if (!empty($dItem["Validate"])) {
                        $textBox->CssClass .=" userValidate";
                        $validateLength = new HiddenInput();
                        $validateLength->Id = $htmlItemId . "-uservalidate";
                        $validateLength->Value = $dItem["Validate"];
                        $divFormItem->SetChild($validateLength);
                    }
                    if ($type !="checkbox")
                        $textBox->CssClass .= " xwebformitem";
                    if ($generateFromUserTemplate) {
                        $itemHtml .= $textBox->RenderHtml($textBox);
                        $templateHtml = str_replace("{" . $htmlItemId . "_label}", $dItem["ShowName"], $templateHtml);
                        $span = new \HtmlComponents\Span();
                        $span->Id= "result_".$htmlItemId; 
                        $templateHtml = str_replace("{" . $htmlItemId . "_result}", $span->RenderHtml(), $templateHtml);
                        $templateHtml = str_replace("{" . $htmlItemId . "}", $itemHtml, $templateHtml);
                    } else {
                        $divFormItem->SetChild($textBox);
                        $itemHtml .= $divFormItem->RenderHtml($divFormItem);
                    }
                    
                    
                    
                    if ($this->IsJquery())
                        {
                            if (self::$IsAjax)
                            {
                                $script .= "$('#$htmlItemId').change(function(){
                                    $('#result_$htmlItemId').html($(this).val());
                                    });";
                            }
                            else 
                            {
                                $script .= "$(document).ready(function(){
                                    $('#$htmlItemId').change(function(){
                                    $('#result_$htmlItemId').html($(this).val());
                                        });
                                });";
                            }
                        }
                    
                
            } else if ($dItem["Type"] == "textarea") {
                $textarea = new Textarea();
                $textarea->Id = $htmlItemId;
                $textarea->Html = $value;
                $textarea->CssClass = "form-control $cssClass";
                if (!empty($dItem["OnChangeEvent"]))
                        $textarea->OnChange = $dItem["OnChangeEvent"]."(this)";
                if ($dItem["Required"] == 1)
                {
                    $textarea->CssClass .=" required";
                    $textarea->IsRequired = true;
                }
                $textarea->CssClass .= " xwebformitem";
                if ($generateFromUserTemplate) {
                    //$itemHtml .= $
                } else {
                    $divFormItem->SetChild($textarea);
                    $itemHtml .= $divFormItem->RenderHtml($divFormItem);
                }
                
            } else if ($dItem["Type"] == "html") {
                $htmleditor = new HtmlEditor();
                $htmleditor->HtmlEditorId = $htmlItemId;
                $value = str_replace("\n", "", $value);
                $value = str_replace("\r", "", $value);
                $htmleditor->Html = $value;
                $divFormItem->Html = $htmleditor->LoadComponent();
                $itemHtml .= $divFormItem->RenderHtml($divFormItem);
            } else if ($dItem["Type"] == "calendar") {
                $calendar = new Calendar($htmlItemId);
                $calendar->Value = $value;
                $calendar->CssClass = "form-control $cssClass";
                $calendar->CssClass .= " xwebformitem";
                $divFormItem->Html = $calendar->RenderHtml($calendar);
                $itemHtml .= $divFormItem->RenderHtml($divFormItem);
            }
            else if ($dItem["Type"] == "checkbox" || $dItem["Type"] == "radio") {
                $vid = $dItem["Id"];
                $valueList = empty($domainValue[$vid]) ? array(): $domainValue[$vid];
                $divFormItem->Html = $this->PrepareFormValueList($dItem["Type"], $dItem["ValueList"], $xml,$itemReadOnly,$dItem["Id"],$valueList);
                $divFormItem->CssClass = $cssClass . " col-md-10";
                $itemHtml .=$divFormItem->RenderHtml($divFormItem);
            } else if ($dItem["Type"] == "file") {
                $fileUpload = new FileUploader($value);
                $fileUpload->CssClass .= " xwebformitem";
                if ($dItem["Required"] == 1)
                {
                    $fileUpload->CssClass .=" required";
                    $fileUpload->IsRequired = true;
                }
                $fileUpload->Id = $htmlItemId;
                $divFormItem->Html = $fileUpload->GetComponentHtml();
                $divFormItem->CssClass = $cssClass. " col-md-10";
                
                
                $itemHtml .= $divFormItem->RenderHtml($divFormItem);
            }
            else if ($dItem["Type"] == "domainData") {
                $nDomain = \Model\UserDomains::GetInstance();
                $changeEvent = "";
                if (!empty($dItem["OnChangeEvent"]))
                    $changeEvent = $dItem["OnChangeEvent"]."(this)";
                $nDomain->GetObjectById($dItem["Domain"], true);
                if ($dItem["DomainSettings"] == "standard")
                {
                    
                    $itemHtml.= $this->GetUserDomain($nDomain->DomainIdentificator, $dataId, $addDomainIdentificator, $data, $disabled, $templateHtml,"?".$nDomain->DomainIdentificator."?");
                }
                
                else if ($dItem["DomainSettings"] == "1n" || $dItem["DomainSettings"] == "mn")
                {
                    $generateHiddenInput = $dItem["GenerateHiddenInput"] == 1 || $dItem["GenerateHiddenInput"] == "1" ? true: false;
                    if ($generateHiddenInput)
                    {
                        $domianHiddenInput = new HiddenInput();
                        $domianHiddenInput->Id = $nDomain->DomainIdentificator;
                        $domianHiddenInput->Value = $value;
                        $itemHtml .= $domianHiddenInput->RenderHtml();
                        if($this->IsJquery())
                        {
                            $itemHtml .= '<script type="text/javascript">';
                            $itemHtml .= '$(document).ready(function(){';
                            $itemHtml .= '$(".'.$nDomain->DomainIdentificator.'").click(function(){';
                            $itemHtml .= 'var value = $(this).attr("data-'. strtolower($nDomain->SaveHiddenColumn).'");';
                            //$itemHtml .= 'alert(value);';
                            
                            $itemHtml .= 'if (!IsUndefined(value)){';
                            if ($dItem["DomainSettings"] == "1n")
                            {
                                $itemHtml .= '$("#'.$nDomain->DomainIdentificator.'").val(value);';
                            }
                            else 
                            {
                                
                            
                                
                            }
                            $itemHtml .= '}';
                            $itemHtml .= '});';
                            $itemHtml .= '});';
                            $itemHtml .= '</script>';
                            
                            
                        }
                    }
                    
                    $labelMain = new Label();
                    $labelMain->Html = $dItem["ShowName"];
                    $labelMain->CssClass = "control-label col-sm-2";
                    $userDomainData = new \Objects\UserDomains();
                    $showName = $nDomain->ShowNameInSubDomain;
                    $identificatorD =  $nDomain->DomainIdentificator;
                    
                    $divCol10 = new Div();
                    if ($this->ShowLabel)
                        $divCol10->CssClass = "col-md-10";
                    else 
                        $divCol10->CssClass = "col-md-12";
                    $domainData = $userDomainData->GetDomainValueList($nDomain->Id);
                    foreach ($domainData as $rowInDomain)
                    {
                        $tmpShowName = $showName;
                        $dataAtributs =array();
                        foreach ($rowInDomain as $kx =>$vy)
                        {
                            $tmpShowName = str_replace("{".$kx."}", $vy, $tmpShowName);
                            $dataAtributs["data-".$kx] = $vy;
                        }
                        $divRow= new Div();
                        $divRow->CssClass = $dItem["DomainSettings"] == "1n form-check" ? "radio" : "checkbox form-check";
                        $radioButton =  $dItem["DomainSettings"] == "1n" ? new RadioButton(): new Checkbox();
                        if ($dItem["Required"] == 1)
                        {
                            $radioButton->IsRequired = true;
                        }
                        $nDomain->DomainIdentificator."_".$rowInDomain["ObjectId"];
                        
                        $idRadioButton = $nDomain->DomainIdentificator."_".$rowInDomain["ObjectId"];
                        $resultSpanId = $nDomain->DomainIdentificator."_result";
                        $radioButton->Id = $idRadioButton;
                        $xmlItem = $identificatorD."_".$rowInDomain["ObjectId"];
                        if ($xml != null) 
                        {
                            $xmlValue = trim($xml->$xmlItem);
                            $radioButton->Checked = $xmlValue == $rowInDomain["ObjectId"]; 
                        }
                        $value = $rowInDomain["ObjectId"];
                        $radioButton->Value =$value;
                        $radioButton->AddAtrribut("data-resultname",$tmpShowName);
                        if (!empty($changeEvent))
                            $radioButton->OnChange = $changeEvent;
                        $radioButton->AddAtributes($dataAtributs);
                        $radioButton->Name = $nDomain->DomainIdentificator;
                        $radioButton->CssClass= $dItem["DomainSettings"] == "1n" ? "domainRadioButton arrayItem $identificatorD addiction-$identificatorD-$value form-check-input" : " domainCheckbox arrayItem $identificatorD form-check-input";
                        $radioButton->CssClass .= " xwebformitem";
                        $radioButtonLabel = new Label();
                        //$radioButtonLabel->For = $nDomain->DomainIdentificator."_".$rowInDomain["ObjectId"];
                        $radioButtonLabel->CssClass = " addiction-$identificatorD-$value form-check-label";
                        
                        $radioButtonLabel->Html = $tmpShowName ." ".$radioButton->RenderHtml();
                        //$divRow->SetChild($radioButton);
                        $divRow->SetChild($radioButtonLabel);
                        
                        $divCol10->SetChild($divRow);
                        if ($this->IsJquery())
                        { 
                                $script .= "$(document).ready(function(){
                                    $('#$idRadioButton').change(function(){
                                $('#$resultSpanId').html('$tmpShowName');
                            });
                            });";
                        }
                    }
                    if ($this->ShowLabel)
                        $itemHtml.=$labelMain->RenderHtml()." ". $divCol10->RenderHtml();
                    else 
                        $itemHtml.=  $divCol10->RenderHtml();
                }
            }
            $erorrDiv = new Div();
            $erorrDiv->CssClass = "col-sm-1 formErrors";
            $erorrDiv->Id = $dItem["Identificator"]."_error";
            $itemHtml .= $erorrDiv->RenderHtml($erorrDiv);
            
            $divFormGroup->Html = $itemHtml;
            $html .=$divFormGroup->RenderHtml($divFormGroup);
        }
        if (!empty($script))
        {
            if (self::$IsAjax)
            {
            $script = "<script type=\"text/javascript\">
                $script
                </script>";
            }
            else 
            {
                $script = "<script type=\"text/javascript\">
                $(document).ready(function(){
                $script
                });</script>";
            }
        }
        
        if ($generateFromUserTemplate) {
            return $script . " " . $templateHtml;
        }

        $html = $script . " " . $html;
        return $html;
    }

    public function SaveUserForm($sendAdminEmail, $emailFrom, $formEmailAdmin, $adminTextMail, $sendUserMail, $userTextMail, $saveMode, $saveData, $formId,$dataByDomain,$captchaTest,$saveTo = 0,$SendFormAction= "") {
        $functions = array();
        
        
       // echo $saveMode; 
        if (!empty($SendFormAction))
        {
            $functions = explode(",", $SendFormAction);
        }
        
        if (!empty($functions))
        {
            foreach ($functions as $function)
            {
                $className = "SendFormFunction\\".$function;
                $class = new $className();
                if ($class->IsBeforeFunction()){
                    $class->SetParameter("AdminEmail",$sendAdminEmail);
                    $class->SetParameter("EmailFrom",$emailFrom);
                    $class->SetParameter("FormEmailAdmin",$formEmailAdmin);
                    $class->SetParameter("AdminTextMail",$adminTextMail);
                    $class->SetParameter("SendUserMail",$sendUserMail);
                    $class->SetParameter("UserTextMail",$userTextMail);
                    $class->SetParameter("SaveMode",$saveMode);
                    $class->SetParameter("SaveData",$saveData);
                    $class->SetParameter("FormId",$formId);
                    $class->SetParameter("DataByDomain",$dataByDomain);
                    $class->SetParameter("CaptchaTest",$captchaTest);
                    $class->SetParameter("SaveTo",$saveTo);
                    $class->SetParameter("SendFormAction",$SendFormAction);
                    $class->CallFunction();
                    $result = $class->GetResult();
                    $sendAdminEmail = empty($result["AdminEmail"]) ? $sendAdminEmail : $result["AdminEmail"];
                    $emailFrom = empty($result["EmailFrom"]) ? $emailFrom : $result["EmailFrom"];
                    $formEmailAdmin = empty($result["FormEmailAdmin"]) ? $formEmailAdmin : $result["FormEmailAdmin"];
                    $adminTextMail = empty($result["AdminTextMail"]) ? $adminTextMail : $result["AdminTextMail"];
                    $sendUserMail = empty($result["SendUserMail"]) ? $sendUserMail : $result["SendUserMail"];
                    $userTextMail = empty($result["UserTextMail"]) ? $userTextMail : $result["UserTextMail"];
                    $saveMode = empty($result["SaveMode"]) ? $saveMode : $result["SaveMode"];
                    $saveData = empty($result["SaveData"]) ? $saveData : $result["SaveData"];
                    $formId = empty($result["FormId"]) ? $formId : $result["FormId"];
                    $dataByDomain = empty($result["DataByDomain"]) ? $dataByDomain : $result["DataByDomain"];
                    $captchaTest = empty($result["CaptchaTest"]) ? $captchaTest : $result["CaptchaTest"];
                    $saveTo = empty($result["SaveTo"]) ? $saveTo : $result["SaveTo"];
                }
            }
        }
        
        
        if ($captchaTest)
        {
            $captchaId = "form".$formId."_captcha";
            $valueCaptcha = $dataByDomain[$captchaId];
            if ($valueCaptcha != $this->GetCaptchaForm($formId) || empty($valueCaptcha))
            {
                $this->SetError($captchaId, "captcha");
                $this->_isError = true;

                                }
            unset($dataByDomain[$captchaId]);
            $this->ClearFormCaptcha($formId);
        }
        
        
        $mailAttachments = array();
        
        $ud = new \Objects\UserDomains();
        $endValidate = "";
        $unSetKey = array();
        $domainArray = array();
        $domainNames = array();
        $count = empty ($dataByDomain["DomainIdentificator"]) ? 0 :count($dataByDomain["DomainIdentificator"]);
        if ($count> 1 )
        {
            foreach ($dataByDomain["DomainIdentificator"] as $domainName )
            {
                $domainNames[] = $domainName;
                $validateData = $this->GetValueFromDomain("?".$domainName."?",$dataByDomain);
                if (empty($validateData))
                    $endValidate = $domainName;
                else 
                {
                    $domainArray[$domainName] = $validateData;
                    $unSetKey[] = $domainName;
                    if (!$ud->IsValidValue($domainName,$validateData))
                    {
                        $err = $ud->GetValidateError();
                        $this->_errors = array_merge($this->_errors, $err);
                        $this->_isError = true;
                    }
                    $arm = $ud->GetFiles($domainName,$validateData);
                    if (!empty($arm))
                        $mailAttachments = array_merge ($arm,$mailAttachments);
                }   
            }
        }
        else 
        {
            $domainNames[] = $dataByDomain["DomainIdentificator"];
            $endValidate = empty($dataByDomain["DomainIdentificator"]) ?"" :$dataByDomain["DomainIdentificator"];
        }
        
        
        if (!empty($dataByDomain["DomainIdentificator"]))
            unset($dataByDomain["DomainIdentificator"]);
        if (!empty($unSetKey))
        {            
            $nArray  = array();
            foreach ($unSetKey as $key)
            {
                foreach ($dataByDomain as $k =>$v)
                {
                    if (!StringUtils::ContainsString($k, "?".$key."?"))
                    {
                        $nArray[$k] = $v;
                    }
                }
            }
            $dataByDomain = $nArray;
        }
        
        
        if (!$ud->IsValidValue($endValidate, $dataByDomain))
        {
            $err = $ud->GetValidateError();
            $this->_errors = array_merge($this->_errors, $err);
            $this->_isError = true;
        }
        $arm = $ud->GetFiles($endValidate,$dataByDomain);
       
        
        if (!empty($arm))
            $mailAttachments = array_merge ($arm,$mailAttachments);
        if ($this->_isError)
        {
            
            return false;
        }
        
        $content = new \Objects\Content();
        $mail = new Mail();
        $userEmail = "";
        
        
         
        $userDomaiItems = new \Objects\UserDomains();
        $userDomainValues = new \Objects\UserDomains();
        $userDomain = \Model\UserDomains::GetInstance();
        foreach ($domainNames as $identifcator)
        {
            $domainItems = $userDomaiItems->GetUserDomainItemsOnlyMn($identifcator);
            foreach ($domainItems as $row)
            {
                $x = 0;
                $iden = $row["Identificator"];
                $domainId = $row["Domain"];
                
                foreach ($saveData as $rowSave)
                {
                    if ($rowSave[0] == $iden)
                    {
                        $valueId = $rowSave[1];
                        $value = $userDomainValues->GetDomainValueByDomainId($domainId, $valueId);
                        $userDomain->GetObjectById($domainId,true);
                        $nameItem = $userDomain->ShowNameInSubDomain;
                        foreach ($value as $k => $v)
                        {
                            $nameItem = str_replace("{".$k."}", $v,$nameItem);    
                        }
                        $saveData[$x][1] = $nameItem;
                    }
                    $x++;
                }
            }
        }
        
         
        
        for ($x = 0;$x< count($saveData);$x++)
        {
            $key = $saveData[$x][0];
            $value = $saveData[$x][1];
            if (StringUtils::ContainsString($key, "_"))
            {
                
                $ar = explode("_", $key);
                $dataInfo = $userDomainValues->GetDomainValue($ar[0],$value);  
                
                if (count($dataInfo) > 0)
                {
                    $domain = \Model\UserDomains::GetInstance();
                    $domain->GetObjectById($dataInfo[0]["DomainId"],true);
                    $tmp = $domain->ShowNameInSubDomain;
                    
                    foreach ($dataInfo as $key => $value)
                    {
                        $tmp = str_replace("{".$key."}", $value["Value"], $tmp);
                    }
                    if ($tmp == $domain->ShowNameInSubDomain)
                    {
                        foreach ($dataInfo as $row)
                        {
                            $ident =$row["ItemIdentificator"];
                            $val = $row["Value"];
                            $tmp = str_replace("{".$ident."}", $val, $tmp);
                        }
                    }
                    
                    $saveData[$x][0] = $ar[0];
                    $saveData[$x][1] = $tmp;
                }
            }
        }
                
        
        for ($i = 0; $i < count($saveData); $i++) {
            $saveData[$i][1] = Page::CallTemplateFunction($saveData[$i][1]);
            if (filter_var($saveData[$i][1], FILTER_VALIDATE_EMAIL) && empty($userEmail)) {
                $userEmail = $saveData[$i][1];
                
            }
        }
        
        
        $objectId = 0;
        if ($saveMode == "SaveStatisticForm") {
            
            $objectId = $content->CreateFormStatisticItem($this->LangId, $formId, $saveData);
            
        }
    
         
        else if ($saveMode=="SaveToFolder")
        {
            
            $tmp = array();
            for($z = 0; $z<count($saveData);$z++)
            {
                $key = $saveData[$z][0];
                $value = $saveData[$z][1];    
                $tmp[$key] = $value;
            }
            $objectName = "";
            
            unset($tmp["DomainIdentificator"]);

            if (!empty($tmp["ObjectName"]))
            {
                $objectName = $tmp["ObjectName"];
            }
            if (!empty($tmp["ObjectId"]))
            {
                $objectId = $tmp["ObjectId"];
            }            
            
            
            $content = new \Objects\Content();
            $detail = $content->GetUserItemDetail($saveTo, self::$UserGroupId, $this->WebId, $this->LangId);
            if (!empty($detail))
            {
                $templateId = $detail[0]["ChildTemplateId"] == 0 ? $detail[0]["TemplateId"] :$detail[0]["ChildTemplateId"];
                if ($objectId == 0)
                {
                    $objectId = $content->CreateUserItem($objectName,$objectName."-".self::$UserId , true, false, "", "", "", $templateId, true, $this->LangId, $saveTo, array(), $saveData, false);
                }
                else 
                {
                    $objectId = $content->UpdateUserItem($objectId, $objectName, $objectName."-".self::$UserId, true, false, "", "", "", $templateId, true, array(), $saveData);
                }
            }
        }
        
        $saveData[] =  array("Id",$objectId);
        
        $mailAttachments = array_merge($mailAttachments,$this->AddFormAttachment($formId,$saveData));
        if ($sendAdminEmail) {
            $mail->SendEmail($userEmail == "" ? $emailFrom : $userEmail, $formEmailAdmin, $adminTextMail, $saveData,$mailAttachments);
        }
        if ($sendUserMail && $userEmail != "") {
            $mail->SendEmail($emailFrom, $userEmail, $userTextMail, $saveData,$mailAttachments);
        }
        if (!empty($functions))
        {
            foreach ($functions as $function)
            {
                $className = "SendFormFunction\\".$function;
                $class = new $className();
                if ($class->IsAfterFunction()){
                    $class->SetParameter("AdminEmail",$sendAdminEmail);
                    $class->SetParameter("EmailFrom",$emailFrom);
                    $class->SetParameter("FormEmailAdmin",$formEmailAdmin);
                    $class->SetParameter("AdminTextMail",$adminTextMail);
                    $class->SetParameter("SendUserMail",$sendUserMail);
                    $class->SetParameter("UserTextMail",$userTextMail);
                    $class->SetParameter("SaveMode",$saveMode);
                    $class->SetParameter("SaveData",$saveData);
                    $class->SetParameter("FormId",$formId);
                    $class->SetParameter("DataByDomain",$dataByDomain);
                    $class->SetParameter("CaptchaTest",$captchaTest);
                    $class->SetParameter("SaveTo",$saveTo);
                    $class->SetParameter("SendFormAction",$SendFormAction);
                    $class->SetParameter("ItemId",$objectId);
                    $class->CallFunction();
                    $result = $class->GetResult();
                    $sendAdminEmail = empty($result["AdminEmail"]) ? $sendAdminEmail : $result["AdminEmail"];
                    $emailFrom = empty($result["EmailFrom"]) ? $emailFrom : $result["EmailFrom"];
                    $formEmailAdmin = empty($result["FormEmailAdmin"]) ? $formEmailAdmin : $result["FormEmailAdmin"];
                    $adminTextMail = empty($result["AdminTextMail"]) ? $adminTextMail : $result["AdminTextMail"];
                    $sendUserMail = empty($result["SendUserMail"]) ? $sendUserMail : $result["SendUserMail"];
                    $userTextMail = empty($result["UserTextMail"]) ? $userTextMail : $result["UserTextMail"];
                    $saveMode = empty($result["SaveMode"]) ? $saveMode : $result["SaveMode"];
                    $saveData = empty($result["SaveData"]) ? $saveData : $result["SaveData"];
                    $formId = empty($result["FormId"]) ? $formId : $result["FormId"];
                    $dataByDomain = empty($result["DataByDomain"]) ? $dataByDomain : $result["DataByDomain"];
                    $captchaTest = empty($result["CaptchaTest"]) ? $captchaTest : $result["CaptchaTest"];
                    $saveTo = empty($result["SaveTo"]) ? $saveTo : $result["SaveTo"];
                }
            }
        }
        
        return true;
    }
    
    public function ValidateForm($dataByDomain)
    {
        $ud = new \Objects\UserDomains();
        $endValidate = "";
        $unSetKey = array();
        $domainArray = array();
        $count = empty ($dataByDomain["DomainIdentificator"]) ? 0 :count($dataByDomain["DomainIdentificator"]);
        if ($count> 1 )
        {
            foreach ($dataByDomain["DomainIdentificator"] as $domainName )
            {
                $validateData = $this->GetValueFromDomain("?".$domainName."?",$dataByDomain);
                if (empty($validateData))
                    $endValidate = $domainName;
                else 
                {
                    $domainArray[$domainName] = $validateData;
                    $unSetKey[] = $domainName;
                    if (!$ud->IsValidValue($domainName,$validateData))
                    {
                        $err = $ud->GetValidateError();
                        $this->_errors = array_merge($this->_errors, $err);
                        $this->_isError = true;
                    }
                }   
            }
        }
        else 
        {
            $endValidate = empty($dataByDomain["DomainIdentificator"]) ?"" :$dataByDomain["DomainIdentificator"];
        }
        
        if (!empty($dataByDomain["DomainIdentificator"]))
            unset($dataByDomain["DomainIdentificator"]);
        if (!empty($unSetKey))
        {            
            $nArray  = array();
            foreach ($unSetKey as $key)
            {
                foreach ($dataByDomain as $k =>$v)
                {
                    if (!StringUtils::ContainsString($k, "?".$key."?"))
                    {
                        $nArray[$k] = $v;
                    }
                }
            }
            $dataByDomain = $nArray;
        }
        
        if (!$ud->IsValidValue($endValidate, $dataByDomain))
        {
            $err = $ud->GetValidateError();
            $this->_errors = array_merge($this->_errors, $err);
            $this->_isError = true;
        }
        
        if ($this->_isError)
            return false;
        return true;
    }

    
    private function SetError($item,$errorCode)
    {
        $ar = array();
        $ar["ItemName"] = $item;
        $ar["ErrorCode"] = $errorCode;
        $this->_errors[] = $ar;
    }
    public function GetError()
    {
        return $this->_errors;
    }
    
    private function GetValueFromDomain($domainName,$saveData)
    {
        $ar = array();
        foreach ($saveData as $key => $value)
        {
            if (StringUtils::ContainsString($key,$domainName))
            {
                $keyTemp = StringUtils::RemoveString($key,$domainName);
                $ar[$keyTemp] = $value;
            }
        }
        return $ar;
    }
            
    public function GetFormStatistic($id, $templateId, $renderHtml = true) {
        $content = new \Objects\Content();
        $formStatistic = $content->GetFormStatistic($id, $this->LangId, $this->WebId);
          
        $autoColumn = array();
        $autoColumn["Id"] = "Id záznamu";
        $autoColumn["UserIp"] = "IP adresa";
        $autoColumn["UserName"] = "Uživatelské jméno";
        $autoColumn["Date"] = "Datum a čas";
        
        $autoColumn[] = "&nbsp;";
        $header = $this->GetHeader($templateId,false,$autoColumn);
        
        $userDomainItems =new \Objects\UserDomains();
        $data = $userDomainItems->GetUserDomainItems($userDomainItems->GetUserDomainByTemplateId($templateId));
        $ignored = array("DomainIdentificator","ActiveStep","UserId");
        $domainsItems = array();
        try{
        foreach ($data as $row)
        {
            if ($row["Type"] =="domainData" && ($row["DomainSettings"] =="1n" || $row["DomainSettings"] =="mn"))
            {
                $identificator = $row["Identificator"];
                $domainsItems[$identificator] = $row["Domain"];
            }
            if ($row["ShowOnlyDetail"] == 1)
            {
                $ignored[] = $row["Identificator"];
                if ($row["Type"] == "domainData")
                {
                    
                    $ud = \Model\UserDomains::GetInstance();
                    $ud->GetObjectById($row["Domain"],true);
                    $ignored[] = trim($ud->DomainIdentificator);
                    
                }
            }
        }
        
        
        if (!empty($domainsItems))
        {
            $domainItem = \Model\UserDomains::GetInstance();
            foreach ($domainsItems as  $key => $value)
            {
                $domainItem->GetObjectById($value,true);
                $domainValues =  new \Objects\UserDomains();
                $vals = $domainValues->GetDomainValueList($value);
                $vals = ArrayUtils::ValueAsKey($vals, "ObjectId");
                $showName = $domainItem->ShowNameInSubDomain;
                foreach ($formStatistic as $row)
                {
                    $xmlstring = $row["Data"];
                    $xml = simplexml_load_string($xmlstring);
                    $k = trim($xml->$key);
                    //;
                    if (!empty($vals[$k]))
                    {
                        $tmpName = $showName;
                        foreach ($vals[$k] as $kx => $vx)
                        {
                            $tmpName = str_replace("{".$kx."}", $vx, $tmpName);
                        }
                        $xml->$key = $tmpName;
                    }
                    try{
                        $xml->addChild("Id", $row["Id"]);
                        $row = array_merge($row,ArrayUtils::ObjectToArray($xml)); 
                        $row["Data"] = $xml->asXML();
                    }
                    catch (Exception $ex)
                    {
                        echo $ex;die();
                    }
                }
                
            } 
           // print_r($formStatistic);die();
        }
        
        //print_r($formStatistic);
        } catch(Exception $e)
        {
            echo $e;die();
        }
        
        $formStatistic = ArrayUtils::SortArray($formStatistic, "Id", SORT_DESC);
        
      
        if ($renderHtml)
            return ArrayUtils::XmlToHtmlTable($formStatistic, "Data", $ignored, $header,false,"",true,"Id","scrollTable1200");
        return $formStatistic;
    }

    public function GetHeader($templateId,$all=false,$autoColumn = array()) {
        $content =  \Model\Content::GetInstance();
        $content->GetObjectById($templateId, true);
        $domainId = $content->DomainId;
        $domain = new \Objects\UserDomains();
        $domainItems = $domain->GetUserDomainItemById($domainId);
        $header = array();
        foreach ($domainItems as $item) {
            if ($item["ShowInWeb"] == 1 || $all) {
                $Identificator = $item["Identificator"];
                $ShowName = $item["ShowName"];

                $header[$Identificator] = $ShowName;
            }
        }
        if (!empty($autoColumn))
        $header = array_merge($header, $autoColumn);
        return $header;
    }
    
    public function GenerateCaptcha($formId)
    {
        $captchaString = StringUtils::GenerateRandomString(5);
        self::$SessionManager->SetSessionValue("formCaptcha", $captchaString, $formId);
        $size_x = 200;
        $size_y = 75;

        $code = "";
        $pocet_znaku = strlen($captchaString); 
        $znaky = str_split($captchaString);

        $space_per_char = $size_x / ($pocet_znaku + 0.5);

        $font = ROOT_PATH."Fonts/verdana.ttf";
        $image_name = mktime(Date("H"),Date("i"),Date("s"),Date("Y"),Date("m"),Date("d")); 
        $save_as = TEMP_CAPTCHA_PATH . $image_name . ".jpg" ; // kam se obrázek uloží


        /* vytvořit plátno */
        $img = imagecreatetruecolor($size_x, $size_y);

        /* definice barev */
        $background = imagecolorallocate($img, 255, 255, 255);
        $border = imagecolorallocate($img, 200, 200, 200);
        $colors[] = imagecolorallocate($img, 128, 64, 192);
        $colors[] = imagecolorallocate($img, 192, 64, 128);
        $colors[] = imagecolorallocate($img, 108, 192, 64);

        /* nakreslit pozadí */
        imagefilledrectangle($img, 1, 1, $size_x - 2, $size_y - 2, $background);
        imagerectangle($img, 0, 0, $size_x - 1, $size_y - 1, $border);

        /* vykreslit text */
        for ($i = 0; $i < $pocet_znaku; $i++) {
            $color = $colors[$i % count($colors)];
            $znak = $znaky[$i];
            imagettftext($img, 28 + rand(0, 8), -20 + rand(0, 40), ($i + 0.5) * $space_per_char, 50 + rand(0, 10), $color, $font, $znak);
            $code .= $znak;
        }

        /* zkreslení */
        imageantialias($img, true);
        for ($i = 0; $i < 1000; $i++) {
            $x1 = rand(5, $size_x - 5);
            $y1 = rand(5, $size_y - 5);
            $x2 = $x1 - 4 + rand(0, 8);
            $y2 = $y1 - 4 + rand(0, 8);
            imageline($img, $x1, $y1, $x2, $y2, $colors[rand(0, count($colors) - 1)]);
        }

        /* uložit soubor */
        imagepng($img, $save_as);
        return SERVER_NAME."res/captcha/" . "$image_name.jpg";
    }
    
    private function GetCaptchaForm($formId)
    {
        return self::$SessionManager->IsEmpty("formCaptcha", $formId) ? "" : self::$SessionManager->GetSessionValue ("formCaptcha", $formId);
    }
    private function ClearFormCaptcha($formId)
    {
        self::$SessionManager->UnsetKey("formCaptcha", $formId);
    }
    private function PrepareFormValueList($mode,$xml,$value,$disabled,$itemId="",$selectedList = array())
    {
        $radioName = StringUtils::GenerateRandomString(10);
        $outHtml = "";
        $xml=simplexml_load_string($xml);
        $selectedList = ArrayUtils::ValueAsKey($selectedList, "Value");
        foreach ($xml as $row)
        {
            $divFormGroup = new Div();
            $divFormGroup->CssClass="form-group";
            $rowId ="";
            if ($mode == "checkbox")
            {
                $label = new Label();
                $label->Html = $this->GetWord(trim($row->itemText));  
                $label->For = "checkbox_".trim($row->itemValue)."_".$itemId;
                $label->CssClass="control-label col-sm-1";
                
                
                $checkbox = new Checkbox();
                $checkbox->CssClass ="domainRadioButton";
                $checkbox->CssClass .= " xwebformitem";
                $checkbox->Id = "checkbox_".trim($row->itemValue)."_".$itemId;
                $checkbox->Value = $row->itemValue;
                $id = "checkbox_".trim($row->itemValue)."_".$itemId;
                if (!empty($value->$id))
                {
                    $checkbox->Checked = trim($value->$id) == 1; 
                    $checkbox->Disabled = $checkbox->Checked && $disabled;
                }
                if (!$checkbox->Checked && !empty($selectedList))
                {
                    $rowId = trim($row->itemValue);
                    $checkbox->Checked = empty($selectedList[$rowId]) ? false:true;
                    $checkbox->Disabled = $checkbox->Checked && $disabled;
                }
                $divCheckBox = new Div();
                $divCheckBox->CssClass="col-sm-1";
                $divCheckBox->Html  = $checkbox->RenderHtml($checkbox);
                $divFormGroup->Html = $label->RenderHtml($label).$divCheckBox->RenderHtml($divCheckBox);
                $outHtml .=$divFormGroup->RenderHtml($divFormGroup);
            }
            else if ($mode == "radio")
            {
                $label = new Label();
                $label->Html = $this->GetWord(trim($row->itemText));
                $label->For = "radio_".trim($row->itemValue)."_".$itemId;
                $label->CssClass="control-label col-sm-1";
                
                $radioButton = new RadioButton();
                $radioButton->CssClass ="domainRadioButton";
                $radioButton->CssClass .= " xwebformitem";
                $radioButton->Id = "radio_".trim($row->itemValue)."_".$itemId;
                $radioButton->Value = $row->itemValue;
                $radioButton->Name = $radioName;
                $id = "radio_".trim($row->itemValue)."_".$itemId;
                 if (!empty($value->$id))
                {
                    if (!empty($id))
                    {
                        if (!empty($value))
                            $radioButton->Checked = trim($value->$id) == 1; 
                        $radioButton->Disabled = $radioButton->Checked && $disabled;
                    }
                }
                if (!$radioButton->Checked && !empty($selectedList))
                {
                    $rowId = trim($row->itemValue);
                    $radioButton->Checked = empty($selectedList[$rowId]) ? false:true;
                    $radioButton->Disabled = $checkbox->Checked && $disabled;
                }
                $divCheckBox = new Div();
                $divCheckBox->CssClass="col-sm-1";
                $divCheckBox->Html  = $radioButton->RenderHtml($radioButton);
                $divFormGroup->Html = $label->RenderHtml($label).$divCheckBox->RenderHtml($divCheckBox);
                $outHtml .=$divFormGroup->RenderHtml($divFormGroup);
            }
        }
        
        return $outHtml;
        
    }
    private function AddFormAttachment($formId,$saveData)
    {
        $content = new \Objects\Content();
        $formDetail = $content->GetFormDetail($formId, self::$UserGroupId, $this->WebId, $this->LangId);
        $xmlString = $formDetail[0]["Data"];
        $xml = simplexml_load_string($xmlString);
        $out = array();
        
        if ($xml->GeneratePDF)
        {
            $templateId = trim($xml->PDFTemplate);
            $templateDetail =  $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId, $templateId);
            $html = $templateDetail[0]["Data"];
            $html = $content->PrepareHtml($saveData, $html);
            $html = Page::RenderXWebComponent($html);
            $html = Page::CallTemplateFunction($html);
            $words = $this->PrepareWords($this->GetLang());
            foreach ($words as $key => $value)
            {
                $html = str_replace("{".$key."}", $value, $html);
            }
            $fileName = StringUtils::GenerateRandomString().".pdf";
            Files::CreatePDF($html,$fileName);
            $out[0]["file"] = ROOT_PATH."res/".$fileName;
            if (!empty($xml->GenerateFileName))
                $out[0]["name"] = $xml->GenerateFileName.".pdf";
            sleep(5);
        }
        
        return $out;        
    }
    public function GenerateInqueryForm($id)
    {
        $content = new \Objects\Content();
        $surveyDetail = $content->GetInqueryDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        if (count($surveyDetail) > 0)
        {
            $xml = $surveyDetail[0]["Data"];
            $disabled = empty($_COOKIE["surveyanswer".$id]) ? false : true;
            $data = simplexml_load_string($xml);
            $moreAnswer =  $data->MoreItem == 1 ? true:false;
            $div = new Div();
            $div->Class = "survey-".$surveyDetail[0]["Id"];
            $div->Id = "survey-".$surveyDetail[0]["Id"];
            $divQuestion = new Div();
            $divQuestion->Classs="question";
            $divQuestion->Html = $data->InquryQuestion;
            $div->SetChild($divQuestion);
            for ($i = 1 ;$i<= 5; $i++)
            {
                $id = "Answer".$i;
                $divAnswer = new Div();
                $divAnswer->CssClass ="form-group";
                $label = new Label();
                $label->CssClass = "control-label col-sm-2";
                $label->For = $id;
                $label->Html = $data->$id;
                $divControl = new Div();
                $divControl->CssClass="col-sm-10";
                $item = null;
                if ($moreAnswer)
                {
                    $item = new Checkbox();
                }
                else 
                {
                    $item = new RadioButton();
                }
                $item->Disabled = $disabled;
                $item->CssClass = "form-control";
                $item->Id = $id;
                $divControl->SetChild($item);
                $divAnswer->SetChild($label);
                $divAnswer->SetChild($divControl);
                $div->SetChild($divAnswer);
            }
            if ($data->OtherItem == 1)
            {
                $divAnswer = new Div();
                $divAnswer->CssClass ="form-group";
                $label = new Label();
                $label->CssClass = "control-label col-sm-2";
                $label->For = "OtherItem";
                $label->Html = $data->OtherText;
                $divControl = new Div();
                $divControl->CssClass="col-sm-10";
                $item = new Textbox();
                $item->CssClass = "form-control";
                $item->Id = "OtherItem";
                $divControl->SetChild($item);
                $divAnswer->SetChild($label);
                $divAnswer->SetChild($divControl);
                $div->SetChild($divAnswer);
            }
            $divButton = new Div();
            $divButton->CssClass ="form-group";
            $button = new Button();
            $button->OnClick = "SaveSurveyAnswer('survey-".$surveyDetail[0]["Id"]."');";
            
            $button->Value= $data->SendButtonText;
            $div->SetChild($button);
            $input = new HiddenInput();
            $input->Id="ParentId";
            $input->Value= $surveyDetail[0]["Id"];
            $div->SetChild($input);
            
            return $div->RenderHtml();    
        }
        
    }
    public function GenerateSurveyStatistic($id)
    {
        $content = new \Objects\Content();
        $formStatistic = $content->GetSurveyStatistic($id, $this->LangId, $this->WebId);
        $surveyDetail = $content->GetInqueryDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        $xmlstring =empty($surveyDetail)?"":$surveyDetail[0]["Data"];
        $data = simplexml_load_string($xmlstring);
        $header = array();
        for($i = 1;$i<= 5;$i++)
        {
            $id = "Answer".$i;
            if (!empty($data->$id))
                $header["Answer".$i] = $data->$id;
        }
        if (!empty($data->OtherText))
            $header["OtherItem"] = $data->OtherText;
        
        return ArrayUtils::XmlToHtmlTable($formStatistic, "Data", array(), $header);
    }
    
    private function GenerateResultScript($elementId,$type="text")
    {
        
    }
    
    public function GetFormItemDetail($id)
    {
        $userDomainItems = new \Objects\UserDomains();
        $content = new \Objects\Content();
        $parent = $content->GetParent($id);
        $data = $content->GetFromStatisticDetail($id,$this->LangId);
        $ignored = array("DomainIdentificator","ActiveStep");
        $formDetail = $content->GetFormDetail($parent,self::$UserGroupId,$this->WebId,$this->LangId);
        $xml = "";
        $html ="";
        if(!empty($formDetail[0]["Data"]))
            $xml = $formDetail[0]["Data"];
        $dataTemplate = ArrayUtils::XmlToArray($xml,"SimpleXMLElement",LIBXML_NOCDATA);
        $detailTemplate = $dataTemplate["DetailStatisticTemplate"];
        if (empty($detailTemplate))
        {
            $domainSettings = $userDomainItems->GetUserDomainItems($userDomainItems->GetUserDomainByTemplateId($formDetail[0]["TemplateId"]));
            $header = array();
            foreach ($domainSettings as $row)
            {
                if ($row["Type"] =="domainData" && ($row["DomainSettings"] =="1n" || $row["DomainSettings"] =="mn"))
                {
                    $id = $row["Domain"];
                    $ud = \Model\UserDomains::GetInstance();
                    $info = $ud->GetObjectById($id);
                    $key = $info["DomainIdentificator"];
                    $val = $info["DomainName"];
                    $header[$key] = $val;
                }
            }
            $header = array_merge($header,$this->GetHeader($formDetail[0]["TemplateId"],true));
            $html = ArrayUtils::GetItemDetail($data,"Data",$ignored,$header);
            
        }
        else 
        {
            $template = $content->GetTemplateDetail(self::$UserGroupId, $this->WebId, $this->LangId, $detailTemplate, 0);
            if (!empty($template))
            {
                $html = $template[0]["Data"];
                $dataDetail = ArrayUtils::XmlToArray($data[0]["Data"],"SimpleXMLElement",LIBXML_NOCDATA);
                $dataDetail["Id"] = $id;
                $html = $this->ReplaceStatisticDetail($dataDetail,$html);
            }
            
        }
        return $html;
    }
    private function ReplaceStatisticDetail($array,$html)
    {
       foreach ($array as $key =>$value)
        {
            $html = str_replace("{".$key."}", $value, $html);
            
        }
        return $html;
    }
}
