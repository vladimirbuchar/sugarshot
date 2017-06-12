<?php

namespace Controller;

use Model\DiscusionItems;
use Model\UsersInGroup;
use Utils\StringUtils;
use Model\ContentVersion;

class Forms extends Controllers {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetAjaxFunction("SendFormWeb", array("*"));
        $this->SetAjaxFunction("RegenerateCaptcha", array("*"));
        $this->SetAjaxFunction("ValidateForm", array("*"));
    }

    public function SendFormWeb() {
        
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        

        $formData = $ajaxParametrs["parametrs"]; //$this->PrepareAjaxParametrs();
        $formData = $this->PrepareDomains($formData);
        $formDataTmp = $this->PrepareAjaxParametrs($formData);
        $id = $ajaxParametrs["FormId"];
        $content =  ContentVersion::GetInstance();
        $form = $content->GetFormDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        $data = $form[0]["Data"];

        $xml = simplexml_load_string($data);
        $xmlRow = $xml[0];
        $form = new \Kernel\Forms();
        $lang = \Model\Langs::GetInstance();
        $url = $lang->GetRootUrl($_GET["langid"]);
        

        $state = $form->SaveUserForm(trim($xmlRow->SendAdminEmail), trim($xmlRow->EmailFrom), trim($xmlRow->FormEmailAdmin), trim($xmlRow->TextEmailAdmin), trim($xmlRow->SendCustomerEmail), trim($xmlRow->TextEmailCustomer), trim($xmlRow->SaveType), $formData, $id, $formDataTmp, trim($xmlRow->UseCaptcha), trim($xmlRow->SaveTo),trim($xmlRow->SendFormAction));
        $outArray["Errors"] = array();
        $outArray["AfterSendFormAction"] = trim($xmlRow->AfterSendFormAction);
        $outArray["EndText"] = trim($xmlRow->EndText);
        
        $outArray["RedirectUrl"] = SERVER_PROTOCOL. $url.trim($xmlRow->GoToPage);
        $outArray["captchaImage"] = "";
                
        if (!$state) {
            $outArray["AfterSendFormAction"] = "erorr";
            $outArray["EndText"] = "erorr";
            $outArray["EndText"] = "erorr";
            $outArray["Errors"] = $form->GetError();
            if (trim($xmlRow->UseCaptcha)) {
                $outArray["captchaImage"] = $form->GenerateCaptcha($id);
            }
        }
        return $outArray;
    }

    public function ValidateForm() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();

        if (empty($ajaxParametrs))
            return;
        $formData = $ajaxParametrs["parametrs"];

        $formData = $this->PrepareDomains($formData);
        $formDataTmp = $this->PrepareAjaxParametrs($formData);
        $id = $ajaxParametrs["FormId"];
        $content =  ContentVersion::GetInstance();
        $form = $content->GetFormDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        $data = $form[0]["Data"];
        $form = new \Kernel\Forms();
        $outArray["Errors"] = array();
        if (!$form->ValidateForm($formDataTmp)) {
            $outArray["Errors"] = $form->GetError();
        }
        return $outArray;
    }

    public function RegenerateCaptcha() {
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs))
            return;
        $id = $ajaxParametrs["Id"];
        $content =  ContentVersion::GetInstance();
        $form = $content->GetFormDetail($id, self::$UserGroupId, $this->WebId, $this->LangId);
        $data = $form[0]["Data"];
        $xml = simplexml_load_string($data);
        $xmlRow = $xml[0];
        if (trim($xmlRow->UseCaptcha) == "1") {
            $form = new \Kernel\Forms();
            return $form->GenerateCaptcha($id);
        }
    }

    private function PrepareDomains($array) {
        $outArray = array();
        foreach ($array as $row) {
            if ($row[0] == "DomainIdentificator") {
                $key = $row[1];
                $outArray[$key] = $this->GetValueDomain($array, $key);
            }
        }
        return $array;
    }

    private function GetValueDomain($array, $keyStart) {
        $outArray = array();

        foreach ($array as $row) {
            $key = $row[0];
            if (StringUtils::StartWidth($key, "?" . $keyStart . "?")) {
                $key = StringUtils::RemoveString($key, "?" . $keyStart . "?");
                $outArray[$key] = $row[1];
            }
        }
        return $outArray;
    }

}
