<?php

namespace Model;

use Dibi;

class DatabaseViews extends SqlDatabase {

    /** @var string query with sql view */
    protected $SqlView = "";

    /** @var boolean - create special table  */
    protected $MaterializedView = false;

    public function __construct() {
        $this->IsView = true;
        parent::__construct();
    }

    /** function for create view in database */
    public function CreateView() {
        if (empty($this->SqlView) || empty($this->ObjectName)) {
            return;
        }
        try {
            dibi::query("CREATE OR REPLACE  VIEW " . strtoupper($this->ObjectName) . " AS $this->SqlView");
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
        if ($this->MaterializedView && SQLMODE == "mysql") {
            try {
                dibi::query("DROP TABLE IF EXISTS " . $this->ObjectName . "_materialized");
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex);
            }
            $columns = array();
            try {
                $columns = dibi::query("SHOW FULL COLUMNS FROM " . strtoupper($this->ObjectName))->fetchAll();
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex);
            }

            $sql = $this->PrepareColmuns($columns);
            try {
                dibi::query("CREATE TABLE IF NOT EXISTS " . $this->ObjectName . "_materialized (" . $sql . ") DEFAULT CHARSET=utf8 ");
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex);
            }
            $this->UpdateMaterializeView();
        }
    }

    /**
     * function for prepare columns 
     * @param  array $columns
     * @return  string 
     */
    private function PrepareColmuns($columns) {
        $sql = array();

        foreach ($columns as $row) {
            $dbnull = $row["Null"] == "YES" ? "NOT NULL" : ""; // db null
            $defaultValue = empty($row["Default"]) ? "" : "DEFAULT " . $row["Default"];
            $sql[] = $row["Field"] . " " . $row["Type"] . " $dbnull " . " " . $defaultValue;
            
        }   
        $this->_columnsInfo = array();
        return implode(",", $sql);
    }

    /** function for updaste special table when is view materialized 
     */
    public function UpdateMaterializeView() {
        try {
            dibi::query("TRUNCATE TABLE " . $this->ObjectName . "_materialized");
            $this->QueryWithMysqli("INSERT INTO  " . $this->ObjectName . "_materialized SELECT * FROM " . strtoupper($this->ObjectName));
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

}
