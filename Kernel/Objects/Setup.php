<?php

namespace Objects;
class Setup extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    
    public function IsInstaled()
    {
        $empty = $this->TableIsEmpty();
        if ($empty) return false;
        return true;
    }
}
