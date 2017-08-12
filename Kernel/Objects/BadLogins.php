<?php

namespace Objects;
use Dibi;
class BadLogins extends ObjectManager{
    public function __construct() {
        parent::__construct();
        
    }
    
    public function AddBadLogin()
    {
        /** @var \Model\BadLogins */
        $model =  \Model\BadLogins::GetInstance();
        $model->DateEvent = new \DateTime;
        $model->SaveObject();
    }
    public function GetBadsLogins()
    {
        /** 
         * @var \Model\BadLogins 
         */
        $model =  \Model\BadLogins::GetInstance();
        $res = $model->GetCount("countBadLogins","TIMEDIFF(NOW(),DateEvent) <= '00:15:00'");
        if (empty($res)) return 0;
        return $res[0]["countBadLogins"];
    }
    
    public function RemoveAllBadLogins()
    {
        /** @var \Model\BadLogins */ 
        $model =  \Model\BadLogins::GetInstance();
        $model->TruncateTable();
    }
    
    
}
