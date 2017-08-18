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
    
     
}
