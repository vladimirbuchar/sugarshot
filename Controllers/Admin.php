<?php

namespace Controller;

use Objects\Webs;
use Model\Langs;
use Utils\StringUtils;

class Admin extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        if ($this->IsPostBack || $this->IsGet) {
            $this->SetIgnoreUserPrivileges("Logout");
            $this->SetViewPermition("SelectLang", array("system", "Administrators"));
            $this->SetViewPermition("Logout", array("*"));
            $this->SetTemplateData("controllerName", $this->ControllerName);
        }
        if (self::$IsAjax) {
            $this->SetAjaxFunction("GetJavascriptWord", array("system", "Administrators"));
            $this->SetAjaxFunction("GetLangListByWeb", array("system", "Administrators"));
        }
    }

    public function SelectLang() {
        $this->SetStateTitle($this->GetWord("word72"));
        $webs = new Webs();
        $webList = $webs->GetWebListByUser(self::$UserGroupId);
        if (count($webList) == 1) {
            $lang =  Langs::GetInstance();
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

    public function GetLangListByWeb() {
        $id = $_GET["params"];
        $lang = Langs::GetInstance();
        return $lang->GetLangListByWeb($id);
    }

    public function OpenDefaultState($webId, $langId) {
        $this->GoToState("WebEdit", "Tree", "xadm", $webId, $langId);
    }

    public function GetJavascriptWord() {
        $wordid = $_POST["params"];
        if (empty($wordid))
            return "";
        $wordid = StringUtils::RemoveString($wordid, '<!--{$');
        $wordid = StringUtils::RemoveString($wordid, '}-->');
        $word = $this->GetWord($wordid);
        return empty($word) ? $wordid : $word;
    }

}
