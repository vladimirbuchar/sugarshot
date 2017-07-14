<?php

namespace Model;
use Dibi;
class DatabaseViews extends SqlDatabase {

    protected $SqlView = "";
    protected $MaterializedView = false;
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
    if ($this->MaterializedView  && SQLMODE == "mysql")
        {
            dibi::query("DROP TABLE IF EXISTS ".$this->ObjectName."_materialized");
            $columns = dibi::query("SHOW FULL COLUMNS FROM ".strtoupper($this->ObjectName))->fetchAll();
            $sql = $this->PrepareColmuns($columns);
            dibi::query("CREATE TABLE IF NOT EXISTS " . $this->ObjectName . "_materialized (" . $sql . ") DEFAULT CHARSET=utf8 ");
            $this->UpdateMaterializeView();
            
            
            
        }
    }
    
    private function PrepareColmuns($columns) {
        $sql = array();
        
        foreach ($columns as $row) {
            $dbnull = $row["Null"] == "YES" ? "NOT NULL" : ""; // db null
            
            $defaultValue = empty($row["Default"]) ? "": "DEFAULT " . $row["Default"];
            $sql[] =  $row["Field"]  ." " . $row["Type"] . " $dbnull "  . " " . $defaultValue;
        }
        $this->_columnsInfo = array();
        return implode(",", $sql);
    }
    public function UpdateMaterializeView()
    {
        dibi::query("TRUNCATE TABLE ".$this->ObjectName."_materialized");
        dibi::query("INSERT INTO  ".$this->ObjectName."_materialized SELECT * FROM ".strtoupper($this->ObjectName) );
        
    }
}
