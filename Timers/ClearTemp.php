<?php

class ClearTemp extends \Kernel\Timers implements \Inteface\iTimer{
    public function __construct()
    {
        $this->TimerName = "ClearTemp";
    }
    public function RunTimer()
    {
        \Utils\Folders::DeleteObjects(TEMP_PATH,array("Export","Captcha","Html"));
    }
    
        
}
