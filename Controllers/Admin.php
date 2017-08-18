<?php

namespace Controller;

use Objects\Webs;
use Utils\StringUtils;

class Admin extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->SetTemplateData("controllerName", $this->ControllerName);
        $this->SetIgnoreUserPrivileges("Logout");
        $this->SetViewPermition("SelectLang", array("system", "Administrators"));
        $this->SetViewPermition("Logout", array("*"));
        
    }

    public function SelectLang() {
        $this->SetStateTitle($this->GetWord("word72"));
        $webs = new Webs();
        $webList = $webs->GetWebListByUser(self::$UserGroupId);
        if (count($webList) == 1) {
            $lang = new \Objects\Langs();
            $langList = $lang->GetLangListByWeb($webList[0]["Id"]);
            if (count($langList) == 1) {
                $this->OpenDefaultState($webList[0]["Id"], $langList[0]["Id"]);
            }
        }

        $this->SetTemplateData("ShowErrorNoWeb", FALSE);
        $this->SetTemplateData("webList", array());
        if (empty($webList)) {
            $this->SetTemplateData("ShowErrorNoWeb", TRUE);
        } else {
            $this->SetTemplateData("webList", $webList);
        }
    }

    public function Logout() {
        self::$User->UserLogout();
        $this->Redirect("/xswadmin/");
    }

    private function OpenDefaultState($webId, $langId) {
        $this->GoToState("WebEdit", "Tree", "xadm", $webId, $langId);
    }

}
