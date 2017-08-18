<?php

namespace Controller;

class Login extends Controllers {

    private $_errorLogin;
    private $_isBadLogin = false;

    public function __construct() {
        parent::__construct();
        $this->SetCommnadFunction("UserLogin", array("*"));
        $this->SetControllerPermition(array("*"));
        $this->SetViewPermition("AdminLogin", array("*"));
        $this->SetTemplateData("controllerName", $this->ControllerName);
    }

    public function AdminLogin() {
        $this->BlockAdmin();
        $this->SetTemplateData("LoginError", $this->_errorLogin && !$this->_isBadLogin ? true : false);
        $this->SetTemplateData("IsBadLogin", $this->_isBadLogin ? true : false);
        self::$User->UserLogout();
        $this->GetAutoLang();
        $this->GetAdminLangs();
    }

    public function UserLogin() {

        if (self::$User->UserLogin($_POST["UserName"], $_POST["UserPassowrd"])) {
            $this->_errorLogin = false;
            $module = new \Objects\Modules();
            $url = $module->GetModuleUrl("Admin", "SelectLang", "xadm");
            $this->Redirect($url);
        } else {
            $this->_isBadLogin = self::$User->IsBadLogin;
            $this->_errorLogin = true;
        }
    }

}
