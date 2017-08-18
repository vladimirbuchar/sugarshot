<?php

namespace Controller;

class TemplatesApi extends Controller {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetAjaxFunction("SetAdminLang", array("*"));
        $this->SetAjaxFunction("IsLoginUser", array("*"));
    }

    public function SetAdminLang() {
        self::$SessionManager->SetSessionValue("AdminUserLang", $_POST["params"]);
    }

    public function IsLoginUser() {
        return $this->IsLogin;
    }

}
