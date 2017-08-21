<?php

namespace Controller;

class TemplatesApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetApiFunction("SetAdminLang", array("*"));
        $this->SetApiFunction("IsLoginUser", array("*"));
    }

    public function SetAdminLang() {
        self::$SessionManager->SetSessionValue("AdminUserLang", $_POST["params"]);
    }

    public function IsLoginUser() {
        return $this->IsLogin;
    }

}
