<?php

namespace Controller;

use Objects\Webs;
use Utils\StringUtils;

class AdminApi extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->SetAjaxFunction("GetJavascriptWord", array("system", "Administrators"));
        $this->SetAjaxFunction("GetLangListByWeb", array("system", "Administrators"));
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

    public function GetLangListByWeb() {
        $id = $_GET["params"];
        $lang = new \Objects\Langs();
        return $lang->GetLangListByWeb($id);
    }

}
