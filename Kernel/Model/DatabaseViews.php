<?php

namespace Model;
use Dibi;
class DatabaseViews extends SqlDatabase {

    protected $SqlView = "";
    
    public function __construct()
    {
        $this->IsView = true;
        parent::__construct();
    }
    public function CreateView() {
        if (empty($this->SqlView) || empty($this->ObjectName))
        {
            return;
        }     
        dibi::query("CREATE OR REPLACE  VIEW ". strtoupper($this->ObjectName) ." AS $this->SqlView");
    }
}
