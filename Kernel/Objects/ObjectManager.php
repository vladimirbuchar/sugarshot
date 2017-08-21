<?php

namespace Objects;

class ObjectManager {

    /**
      @var  \Utils\SessionManager
     */
    protected static $SessionManager = null;

    public function __construct() {
        if (self::$SessionManager == null) {
            self::$SessionManager = new \Utils\SessionManager();
        }
    }

    protected function GetLangIdByWebUrl() {
        $langItem = new \Objects\Langs();
        $webInfo = $langItem->GetWebInfo(SERVER_NAME_LANG);
        return $webInfo[0]["Id"];
    }

    protected function GetActualWeb() {
        $web = new \Objects\Langs();
        $webInfo = $web->GetWebInfo(SERVER_NAME_LANG);
        if (count($webInfo) == 0)
            return 0;
        return $webInfo[0]["WebId"];
    }

}
