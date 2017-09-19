<?php

namespace Objects;

class Modules extends ObjectManager {

    public function __construct() {
        parent::__construct();
    }

    public function SetupModule($id) {
        $userGroupModule = new \Objects\Users();
        $systemGrouup = $userGroupModule->GetUserGroupByIdeticator("system");
        $systemId = $systemGrouup->Id;
        $userGroupModule->SetUserGroupModules($systemId, $id);
    }

    public function GetModuleUrl($moduleController, $moduleView, $prefix, $hashUrl = FALSE) {
        $url = "/$prefix/" . $moduleController . "/" . $moduleView . "/";
        if ($hashUrl)
            $url = StringUtils::EncodeString($url);
        return $url;
    }

    public function GetModuleByIdentificator($identificator) {
        $model = new \Model\Modules();
        return $model->GetFirstRow($model->SelectByCondition("ModuleIdentificator = '" . $identificator . "'"));
    }

    public function CanModuleShow($controller, $view, $userId) {

        $res = dibi::query("SELECT * FROM USERMODULESVIEW WHERE ModuleControler = %s AND ModuleView = %s AND UserGroupId =%i", $controller, $view, $userId)->fetchAll();
        if (count($res) == 0)
            return false;
        return true;
    }

}
