<?php

namespace Model;

use Types\ExportSettings;
use Utils\ArrayUtils;
use Utils\Files;
use Dibi;
use PHPExcel;
use PHPExcel_IOFactory;

class SqlDatabase {

    /** sql object name */
    protected $ObjectName = "";

    /** @var boolean  */
    public $IsTable = false;

    /** @var boolean  */
    public $IsView = false;

    /** @var boolean  */
    public $IsFunction = false;

    /** @var array()  */
    protected $ExportColumns = array();

    /** @var array()  */
    private $_exportSettings = array();

    /** @var string */
    public $ParentColumn = "";

    /** @var boolean  */
    public $TestQuery = false;

    /** @var array()  */
    protected $SelectColumns = array();

    /** @var boolean  */
    private static $_utf8Set = true;

    /** @var object */
    private static $_instance = null;

    /**
      @var  \Utils\SessionManager
     */
    protected static $SessionManager = null;

    public function __construct() {

        if (self::$_utf8Set) {
            try {
                dibi::query("SET NAMES 'utf8'");
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex);
            }
            self::$_utf8Set = false;
        }
        if (self::$SessionManager == null) {
            self::$SessionManager = new \Utils\SessionManager();
        }
    }

    /**
     * @param boolean $resetInstance
     */
    public static function GetInstance($resetInstance = TRUE) {
        if ($resetInstance)
            self::$_instance = null;

        if (self::$_instance == null) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    /** function for select database 
     * @param string  $where WHERE
     * @param string $sort - ORDER BY
     * @param array() $columns - select columns
     * @param array $params
     * @return array
     */
    public function SelectByCondition($where = "", $sort = "", $columns = array(), $params = array()) {

        if (!empty($where))
            $where = " WHERE $where";
        if (!empty($sort))
            $sort = " ORDER BY $sort";
        $col = "*";
        if (empty($columns))
            $columns = $this->SelectColumns;
        if (!empty($columns)) {
            $col = implode(",", $columns);
        }
        if (empty($col))
            $col = "*";
        $query = "SELECT $col FROM " . $this->ObjectName . " $where $sort";
        return $this->DbQuery($query, $params);
    }

    /** SELECT to database 
     * @param array $columns 
     * @param boolean $deleted 
     * @param boolean $actualWeb 
     * @param boolean $actualLang
     * @param \Types\SortDatabase $sort
     * @param string $extWhere  
     * @param boolean $saveToSession 
     * @param integer $parentId int 
     * @return array
     */
    public function Select($columns = array(), $deleted = FALSE, $actualWeb = true, $actualLang = true, $sort = null, $extWhere = "", $saveToSession = false, $parentId = 0) {

        $whereDeleted = "";
        $whereWeb = "";
        $whereLang = "";
        $where = "";
        if ($saveToSession) {
            if ($sort != null) {
                self::$SessionManager->SetSessionValue($this->ObjectName . "_sort_type", $sort->SortType);
                self::$SessionManager->SetSessionValue($this->ObjectName . "_sort_columnName", $sort->ColumnName);
            } else if (!self::$SessionManager->IsEmpty($this->ObjectName . "_sort_type") && !self::$SessionManager->IsEmpty($this->ObjectName . "_sort_columnName")) {

                $sort = new SortDatabase(self::$SessionManager->GetSessionValue($this->ObjectName . "_sort_type"), !self::$SessionManager->GetSessionValue($this->ObjectName . "_sort_columnName"));
            }
            if ($extWhere == "clear") {
                $extWhere = "";
                self::$SessionManager->UnsetKey($this->ObjectName . "_extWhere");
            } else if (!empty($extWhere)) {
                self::$SessionManager->SetSessionValue($this->ObjectName . "_extWhere", $extWhere);
            } else if (!self::$SessionManager->IsEmpty($this->ObjectName . "_extWhere")) {
                $extWhere = self::$SessionManager->GetSessionValue($this->ObjectName . "_extWhere");
            }
        }
        if ($deleted)
            $whereDeleted = "Deleted = 1";
        else
            $whereDeleted = "Deleted = 0";
        if ($this->IsTable) {
            if ($actualWeb && $this->MultiWeb) {
                if (!empty($_GET["webid"]))
                    $whereWeb = " WebId = " . $_GET["webid"];
            }
        }
        if ($actualLang) {
            if (!empty($_GET["lang"])) {
                $whereLang = " LangId = " . $_GET["lang"];
            }
        }
        if (!empty($whereDeleted)) {
            if (empty($where))
                $where = $whereDeleted;
            else {
                $where = $where . " AND " . $whereDeleted;
            }
        }
        if (!empty($whereWeb)) {
            if (empty($where))
                $where = $whereWeb;
            else
                $where = $where . " AND " . $whereWeb;
        }
        if (!empty($whereLang)) {
            if (empty($where)) {
                $where = $whereLang;
            } else {
                $where = $where . " AND " . $whereLang;
            }
        }
        if (!empty($this->ParentColumn && $parentId > 0)) {
            $where = $where . " AND  $this->ParentColumn = " . $parentId;
        }
        if (!empty($extWhere)) {
            if (empty($where)) {
                $where = $extWhere;
            } else {
                $where .= " AND (" . $extWhere . ")";
            }
        }
        $sortQuery = "";
        if ($sort != null) {
            if (!empty($sort))
                $sortQuery .= " ORDER BY " . $sort->ColumnName . " " . $sort->SortType;
        }
        return $this->SelectByCondition($where, $sortQuery, $columns);
    }

    /** fukce pro přípravu where z array 
     * @param  string  $array
     * @return string
     */
    public function PrepareWhere($array) {
        if ($array == "clear")
            return "clear";
        $where = "";



        for ($i = 0; $i < count($array); $i++) {
            $col = $array[$i][0];
            $value = $array[$i][1];
            $i++;
            $andor = "";
            if (!empty($array[$i][1]))
                $andor = $array[$i][1];

            $i++;
            $likemode = $array[$i][1];
            if ($likemode == "%LIKE") {
                $likemode = "LIKE";
                $value = $value . "%";
            } else if ($likemode == "LIKE%") {
                $likemode = "LIKE";
                $value = "%" . $value;
            } else if ($likemode == "%LIKE%") {
                $likemode = "LIKE";
                $value = "%" . $value . "%";
            } else if ($likemode == "NOT %LIKE") {
                $likemode = "NOT LIKE";
                $value = $value . "%";
            } else if ($likemode == "NOT LIKE%") {
                $likemode = "NOT LIKE";
                $value = "%" . $value;
            } else if ($likemode == "NOT %LIKE%") {
                $likemode = "NOT LIKE";
                $value = "%" . $value . "%";
            }

            $where .= $col . " $likemode '" . $value . "'";

            if (($i + 1) < count($array)) {
                $where .= " $andor ";
            }
        }
        return $where;
    }

    /** ověříme zda objekt existuje
     *  @param $colName strin jméno sloupce 
     * @param $value hodnota
     * @ $noTestId int co se nemá testovat
     */
    public function ItemExists($colName, $value, $noTestId, $columnId = "Id", $langId = 0) {
        if (empty($value))
            return false;
        if ($langId == 0)
            $res = dibi::query("SELECT Id FROM $this->ObjectName WHERE $colName = %s AND $columnId <> %i", $value, $noTestId)->fetchAll();
        else
            $res = dibi::query("SELECT Id FROM $this->ObjectName WHERE $colName = %s AND $columnId <> %i AND LangId = %i", $value, $noTestId, $langId)->fetchAll();
        if (empty($res))
            return FALSE;
        return TRUE;
    }

    public function GetFirstRow($res) {
        if (!empty($res))
            return $res[0];
    }

    // EXPORT A IMPORT 
    /** metoda pro nastavení exportu 
     * @param $columnName jméno sloupce 
     * @param $showName zobrazené jméno 
     */
    public function SetExportSettings($columnName, $showName) {
        $this->TableExportSettings();
        if (in_array($columnName, $this->ExportColumns))
            return;
        $this->ExportColumns[] = $columnName;
        $exportSettings = new ExportSettings($columnName, $showName);
        $this->_exportSettings[] = $exportSettings;
    }

    protected function SetAllExportColumns() {
        if (empty($this->ExportColumns)) {
            $res = dibi::query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$this->ObjectName' AND TABLE_SCHEMA <> 'performance_schema'")->fetchAll();
            foreach ($res as $row) {
                $this->SetExportSettings($row["column_name"], $row["column_name"]);
            }
        }
    }

    /** export 
     * @param $mode - xls,xlsx a xml 
     */
    public function Export($mode) {
        $this->SetAllExportColumns();
        $time = rand(0, 999999999999);
        $this->SetExportSettings("Id", "Id");
        $fileName = "";
        $col = array();
        foreach ($this->_exportSettings as $row) {
            $col[] = $row->ColumnName;
        }
        $data = $this->Select($col);



        if ($mode == "xls" || $mode == "xlsx") {

            $objPHPExcel = new PHPExcel();
            $rowI = 1;
            $c = 0;
            foreach ($this->_exportSettings as $row) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($c, $rowI, $row->ShowName, FALSE);
                $c++;
            }
            $rowI++;

            foreach ($data as $row) {
                for ($column = 0; $column < count($col); $column++) {
                    $name = $col[$column];
                    $value = $row->$name;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($column, $rowI, $value, FALSE);
                }
                $rowI++;
            }
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            $fileName = './Temp/ExportFile' . $time . '.xlsx';
            $objWriter->save($fileName);
        } else if ($mode == "xml") {
            $xml = ArrayUtils::ArrayToXml($data);
            $fileName = './Temp/ExportFile' . $time . '.xml';
            Files::WriteFile($fileName, $xml);
        } else if ($mode == "xmlcdata") {
            $xml = ArrayUtils::ArrayToXml($data, true);
            $fileName = './Temp/ExportFile' . $time . '.xml';
            Files::WriteFile($fileName, $xml);
        }
        return str_replace("./", "/", $fileName);
    }

    protected function IsMySql() {
        return SQLMODE == "mysql";
    }

    public function GetMaxValue($columnName, $condition = "") {
        $where = empty($condition) ? "" : " WHERE $condition";
        $res = $this->DbQuery("SELECT MAX($columnName) AS MaxVal FROM " . $this->ObjectName . $where);
        return empty($res) ? 0 : $res[0]["MaxVal"];
    }

    public function GetCount($columnName = "", $condition = "") {
        $where = empty($condition) ? "" : " WHERE $condition";
        $res = $this->DbQuery("SELECT COUNT(*) $columnName FROM $this->ObjectName $where");
        return empty($res) ? 0 : $res[0][$columnName];
    }

    protected function SetSelectColums($columns) {
        $this->SelectColumns = array_merge($this->SelectColumns, $columns);
    }

    protected function DbQuery($query, $params = array()) {
        try {
            if ($this->TestQuery)
                dibi::test($query, $params);
            return \dibi::query($query, $params)->fetchAll();
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    public function TransactionBegin() {
        try {
            \dibi::begin();
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    public function TransactionEnd() {
        try {

            \dibi::commit();
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    public function RollbackTransaction() {
        try {
            \dibi::rollback();
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /** function for read mysql variables
     * @param string $variableName
     * @return string
     *  */
    protected function GetSqlVariable($variableName) {
        try {
            $res = \dibi::query("SHOW VARIABLES LIKE %s", $variableName)->fetchAll();
            return (empty($res)) ? "" : $res[0]["Value"];
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /** function for set mysql variable
     * @param string  $variableName 
     * @param string $variableValue
     */
    protected function SetSqlVariable($variableName, $variableValue) {
        try {
            \dibi::query("set global $variableName='$variableValue'");
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /** UTILS */
    protected function GetLangIdByWebUrl() {
        $langItem = new \Objects\Langs();
        $webInfo = $langItem->GetWebInfo(SERVER_NAME_LANG);
        return $webInfo[0]["Id"];
    }

    protected function GetActualWeb() {
        $web = new \Objects\Langs();
        $webInfo = $web->GetWebInfo(SERVER_NAME_LANG);
        if (count($webInfo) == 0)
            return 0;
        return $webInfo[0]["WebId"];
    }

    protected function GetWord($wordid) {

        $userLang = self::$SessionManager->GetSessionValue("AdminUserLang");
        if (!self::$SessionManager->IsEmpty("AdminWords$userLang"))
            return self::$SessionManager->GetSessionValue("AdminWords$userLang", $wordid);
        return "";
    }

}
