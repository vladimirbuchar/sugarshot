<?php

namespace Controller;

use Utils\StringUtils;
use Types\DomainData;

class ApiController extends \Controller\Controllers {

       /** @ar array */
    private $_ajaxFunctions = array();
    
    public function __construct() {
        parent::__construct();
        
    }
    protected function SetApiFunction($functioName, $settings = array()) {
        if (!empty($settings)) {
            if (!in_array("*", $settings)) {
                $userGroupIdentificator = self::$User->GetUserGroupIdentificator();
                if (empty($userGroupIdentificator))
                    return;
                if (!in_array($userGroupIdentificator, $settings))
                    return;
            }
            $this->_ajaxFunctions[] = $functioName;
        }
    }
    
    public function IsApiFunction($functionName) {
        return in_array($functionName, $this->_ajaxFunctions);
    }
    
    protected function GetHtmlEditors($ajaxParametrs)
    {
        $editors = array();
        foreach ($ajaxParametrs as $key => $value)
        {
            if (StringUtils::EndWith($key, "__ishtmleditor__")) {
                $key = StringUtils::RemoveString($key, "__ishtmleditor__");
                $key = StringUtils::RemoveLastChar($key, 5);
                $editors[$key] = $value;
            }
        }
         return $editors;
        
    }
    
    protected function PrepareAjaxParametrs($params = null) {
        if ($params == null) {
            if (!empty($_GET["params"]))
                $params = $_GET["params"];
            else if (!empty($_POST["params"]))
                $params = $_POST["params"];
            else if ($_COOKIE["params"]) {
                $params = $_COOKIE["params"];
                unset($_COOKIE["params"]);
                return $params;
            }


            if (empty($params))
                return;
        }

        $outArray = array();
        for ($i = 0; $i < count($params); $i++) {
            if (empty($params[$i]))
                continue;
            $id = $params[$i][0];
            if (StringUtils::EndWith($id, "__ishtmleditor__")) {
                $id = StringUtils::RemoveString($id, "__ishtmleditor__");
                $id = StringUtils::RemoveLastChar($id, 5);
            }

            $value = empty($params[$i][1]) ? "" : $params[$i][1];

            if (!array_key_exists($id, $outArray))
                $outArray[$id] = $value;
            else {
                if (is_array($outArray[$id])) {
                    $outArray[$id][] = $value;
                } else {
                    $outArray[$id] = array($outArray[$id], $value);
                }
            }
        }
        return $outArray;
    }
    
    protected function SaveUserDomain($data) {
        $objectId = 0;
        $domainName = "";

        $domainData = array();
        foreach ($data as $key => $value) {
            $valueId = 0;
            if (strpos($key, "ObjectId_") !== false) {
                $objectId = $value;
            } else if (strpos($key, "DomainIdentificator_") !== false) {

                $domainName = $value;
            } else {
                $ar = explode("_", $key);
                if (StringUtils::StartWidth($key, "checkbox_")) {
                    $key = $ar[2];
                    $value = $ar[1];
                    $domainData[] = new DomainData($key, $valueId, $value);
                } else if (!empty($ar[2])) {
                    $key = $ar[1];
                    $valueId = $ar[2];
                    $domainData[] = new DomainData($key, $valueId, $value);
                } else {
                    $key = $ar[1];
                    $valueId = 0;
                    $domainData[] = new DomainData($key, $valueId, $value);
                }
            }
        }
        //print_r($domainData);die();

        $domainValue = new \Objects\UserDomains();
        $domainValue->SaveDomainValue($domainName, $objectId, $domainData);
    }

}