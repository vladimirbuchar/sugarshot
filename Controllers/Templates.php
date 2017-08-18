<?php

namespace Controller;

class Templates extends Controllers {

    public function __construct() {


        parent::__construct();

        $this->TestUserPrivileges = false;
        $this->SetControllerPermition(array("*"));
        $this->GetAutoLang();
        $this->AddStyle("/Styles/Core.css");
        $this->SetTemplateData("controllerName", $this->ControllerName);
        $this->SetViewPermition("AdminNoLogin", array("*"));
        $this->SetViewPermition("Index", array("*"));
        $this->SetViewPermition("AdminLogin", array("system", "Administrators"));
        $this->SetViewPermition("AdminLoginSmallTemplate", array("system", "Administrators"));
        $this->SetViewPermition("Setup", array("*"));
    }

    public function Index() {
        $this->IncludeState();
    }

    public function AdminNoLogin() {

        $this->IncludeState();
    }

    public function Setup() {

        $this->IncludeState();
        $this->GetAdminLangs();
    }

    public function AdminLogin() {

        if (!empty($_GET["webid"]))
            $this->SetTemplateData("webId", $_GET["webid"]);
        else
            $this->SetTemplateData("webId", 0);

        if (!empty($_GET["langid"]))
            $this->SetTemplateData("langId", $_GET["langid"]);
        else
            $this->SetTemplateData("langId", 0);

        if (!empty($_GET["objectid"]))
            $this->SetTemplateData("objectId", $_GET["objectid"]);
        else
            $this->SetTemplateData("objectId", 0);

        if (!empty($_GET["parentid"])) {
            $this->SetTemplateData("parentid", $_GET["parentid"]);
        } else
            $this->SetTemplateData("parentid", "");
        $this->SetTemplateData("isFrontEnd", $this->IsFrontend ? "true" : "false");

        if (self::$User->IsLoginUser()) {
            $this->IncludeState();
            $this->SetTemplateData("UserName", self::$User->GetFullUserName());
        } else {
            $this->Redirect("/xswadmin/");
        }
        $this->GetAdminLangs();
    }

    public function AdminLoginSmallTemplate() {
        if (!empty($_GET["webid"]))
            $this->SetTemplateData("webId", $_GET["webid"]);
        else
            $this->SetTemplateData("webId", 0);

        if (!empty($_GET["langid"]))
            $this->SetTemplateData("langId", $_GET["langid"]);
        else
            $this->SetTemplateData("langId", 0);

        if (!empty($_GET["objectid"]))
            $this->SetTemplateData("objectId", $_GET["objectid"]);
        else
            $this->SetTemplateData("objectId", 0);


        if (self::$User->IsLoginUser()) {
            $this->IncludeState();
            $this->SetTemplateData("UserName", self::$User->GetFullUserName());
        } else {
            $this->Redirect("/xswadmin/");
        }
    }

}
