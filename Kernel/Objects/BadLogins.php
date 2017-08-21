<?php

namespace Objects;

class BadLogins extends ObjectManager {

    public function __construct() {
        parent::__construct();
    }

    public function AddBadLogin() {
        /** @var \Model\BadLogins */
        $model = \Model\BadLogins::GetInstance();
        $model->DateEvent = new \DateTime;
        $model->SaveObject();
    }

    public function GetBadsLogins() {
        /**
         * @var \Model\BadLogins 
         */
        $model = \Model\BadLogins::GetInstance();
        return $model->GetCount("countBadLogins", "TIMEDIFF(NOW(),DateEvent) <= '00:15:00'");
    }

    public function RemoveAllBadLogins() {
        /** @var \Model\BadLogins */
        $model = \Model\BadLogins::GetInstance();
        $model->TruncateTable();
    }
}
