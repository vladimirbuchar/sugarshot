<?php

namespace Controller;

use Utils\Files;
use Kernel\GlobalClass;
use Model\AdminLangs;
use Utils\StringUtils;
use Types\DomainData;

class Controllers extends GlobalClass {

    /** @var string */
    protected $ControllerName = "";

    /** @var bool */
    private $_controllerPermition = false;

    /** @var bool */
    private $_noAccess = false;

    public function __construct() {
        parent::__construct();
        $this->ControllerName = $this->GetControllerName();
    }

    protected function BlockAdmin() {
        $lang = new \Objects\Langs();
        if ($lang->BlockAdmin(SERVER_NAME)) {
            self::$User->UserLogout();
            $this->GoHome();
        }
    }

    protected function SetNoAccess($value) {
        $this->_noAccess = $value;
    }

    public function GetNoAccess() {
        return $this->_noAccess;
    }

    protected function GetControllerName() {
        return empty($_GET["Controller"]) ? DEFAULT_CONTROLER_NAME :$_GET["Controller"];
    }

    protected function SetControllerPermition($settings) {
        if (empty($settings)) {
            $this->_controllerPermition = false;
        } else {

            if (in_array("*", $settings))
                $this->_controllerPermition = true;
            else {
                $userGroupIdentificator = self::$User->GetUserGroupIdentificator();
                if (empty($userGroupIdentificator))
                    $this->_controllerPermition = false;
                else {

                    if (in_array($userGroupIdentificator, $settings))
                        $this->_controllerPermition = true;
                    else
                        $this->_controllerPermition = false;
                }
            }
        }
    }

    public function GetControllerPermition() {
        return $this->_controllerPermition;
    }
    
    protected function CheckWebPrivileges($viewName = "") {

        if (empty($viewName) || $this->GetViewName() == $viewName) {
            if ($this->WebId > 0) {
                $web = new \Objects\Webs();
                if (!$web->CheckWebPrivileges(self::$UserGroupId, $this->WebId)) {
                    $this->SetNoAccess(true);
                }
            }
        }
    }

    protected function GetRoorUrl() {
        $langItem = new \Objects\Langs();
        return $langItem->GetRootUrl($this->LangId);
    }
    
    // FOR WEB EIDT
    protected function GetLastEditLangVersion($clear = true) {
        $lang = 0;
        if (!self::$SessionManager->IsEmpty("lastEditLang")) {
            $lang = self::$SessionManager->GetSessionValue("lastEditLang");
            if ($clear) {
                self::$SessionManager->UnsetKey("lastEditLang");
            }
        }
        return $lang;
    }
    
    
    // UPLNE PŘEDĚLAT 
    protected function ReplaceHtmlWord($data) {
        foreach ($data as $row) {
            foreach ($row as $key => $value) {
                if (StringUtils::StartWidth($value, '<!--{$') && StringUtils::EndWith($value, '}-->')) {
                    $value = StringUtils::RemoveString($value, '<!--{$');
                    $value = StringUtils::RemoveString($value, '}-->');
                    $value = $this->GetWord($value);
                    $row[$key] = $value;
                }
            }
        }
        return $data;
    }
    
    protected function GetAdminLangs() {
        $adminLang = AdminLangs::GetInstance();
        $adminLangData = $adminLang->Select();
        foreach ($adminLangData as $row) {
            $row["selected"] = "";
            if (self::$SelectLang == $row->LangIdentificator) {
                $row["selected"] = "selected =\"selected\"";
            }
        }
        $this->SetTemplateData("AdminLang", $adminLangData);
    }  
    
    

}
