<?php

namespace Controller;

use Utils\StringUtils;

class AdminApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("system", "Administrators"));
        $this->SetApiFunction("GetJavascriptWord", array("system", "Administrators"));
        $this->SetApiFunction("GetLangListByWeb", array("system", "Administrators"));
    }

    public function GetJavascriptWord($param) {
        $wordid = $param["wordid"];
        $wordid = StringUtils::RemoveString($wordid, '<!--{$');
        $wordid = StringUtils::RemoveString($wordid, '}-->');
        $word = $this->GetWord($wordid);
        return empty($word) ? $wordid : $word;
    }

    public function GetLangListByWeb($param) {
        $id = $param["web"];
        $lang = new \Objects\Langs();
        return $lang->GetLangListByWeb($id);
    }

}
