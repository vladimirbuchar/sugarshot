<?php

namespace Controller;

class TemplatesApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetApiFunction("SetAdminLang", array("*"));
        $this->SetApiFunction("IsLoginUser", array("*"));
    }

    public function SetAdminLang($param) {
        self::$SessionManager->SetSessionValue("AdminUserLang", $param["selectlang"]);
    }

    public function IsLoginUser() {
        return $this->IsLogin;
    }

}
