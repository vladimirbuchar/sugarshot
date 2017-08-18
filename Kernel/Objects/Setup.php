<?php

namespace Objects;
class Setup extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    
    public function IsInstaled()
    {
        $model = new \Model\Setup();
        $empty = $model->TableIsEmpty();
        if ($empty) return false;
        return true;
    }
}
