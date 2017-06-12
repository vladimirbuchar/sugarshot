<?php

namespace Objects;
use Dibi;
class Crons extends ObjectManager{
    public function __construct() {
        parent::__construct();
        
    }
    
    public function RunCron()
    {
        /** 
         * @var \Model\Crons 
         */
        $model = \Model\Crons::GetInstance();
        
        
    }
    
    
    
}
