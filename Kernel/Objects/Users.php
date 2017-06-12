<?php

namespace Objects;
class Users extends ObjectManager{
    public function __construct() {
        $this->ConnectedModel  ="Users";
        parent::__construct();
    }
}
