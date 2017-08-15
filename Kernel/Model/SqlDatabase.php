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
            dibi::query("SET NAMES 'utf8'");
//            mysqli_query("");
            self::$_utf8Set = false;
        }
        if (self::$SessionManager == null) {
            self::$SessionManager = new \Utils\SessionManager();
        }
    }

    public static function GetInstance($resetInstance = TRUE) {
        if ($resetInstance)
            self::$_instance = null;

        if (self::$_instance == null) {
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    public function SelectByCondition($where = "", $sort = "", $columns = array(), $params = array()) {
        try {
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
            if ($this->TestQuery) {
                \dibi::test($query);
                die();
            }
            return $this->SelectQuery($query, $params);
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
            return null;
        }
        return null;
    }

    // SELECT DO DATABÁZE

    /** prvedení selectu do databze
     * @param $columns seznam sloupců array
     * @param $deleted bool smazané záznamy
     * @param $actualWeb  bool aktuální web
     * @param $actualLang bool aktuání jazyk
     * @param $sort string řazení 
     * @param $extWhere string další podmínky
     * @param $saveToSession zda se má $sort a $extWhere uložit do session - 
     * @param $parentId int id parenta
     */
    public function Select($columns = array(), $deleted = FALSE, $actualWeb = true, $actualLang = true, $sort = null, $extWhere = "", $saveToSession = false, $parentId = 0) {

        $query = "";
        $res = null;
        $whereDeleted = "";
        $whereWeb = "";
        $whereLang = "";
        $where = "";
        if ($saveToSession) {
            //break;
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
        $query = " SELECT ";
        if (empty($columns))
            $columns = $this->SelectColumns;
        if (empty($columns)) {
            $query .= " * ";
        } else {
            $cols = implode(",", $columns);
            $query .= " $cols ";
        }
        $query .= " FROM $this->ObjectName ";
        if (!empty($where)) {
            $query .= " WHERE $where";
            if (!empty($extWhere)) {
                $query .= " AND (" . $extWhere . ")";
            }
        } else if (!empty($extWhere)) {
            $query .= " WHERE $extWhere";
        }

        if ($sort != null) {

            if (!empty($sort))
                $query .= " ORDER BY " . $sort->ColumnName . " " . $sort->SortType;
        }
        try {

            $res = dibi::query($query)->fetchAll();
            if ($this->TestQuery)
                dibi::test($query);
        } catch (Exception $ex) {
            self::$SessionManager->UnsetKey($this->ObjectName . "_sort_type");
            self::$SessionManager->UnsetKey($this->ObjectName . "_sort_columnName");
            self::$SessionManager->UnsetKey($this->ObjectName . "_extWhere");
            dibi::test($query);
            \Kernel\Page::ApplicationError($ex);
        }


        return $res;
    }

    /* fukce pro přípravu where z listu */

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
     *   @param $colName strin jméno sloupce 
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

    protected function GetFirstRow($res) {
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

    public function GetMaxValue($columnName, $condition = "") {
        $where = "";
        if (!empty($condition)) {
            $where = " WHERE $condition";
        }
        $res = dibi::query("SELECT MAX($columnName) AS MaxVal FROM " . $this->ObjectName . $where)->fetchAll();
        if (empty($res))
            return 0;
        return $res[0]["MaxVal"];
    }

    public function GetCount($columnName = "", $condition = "") {
        $where = "";
        if (!empty($condition)) {
            $where = " WHERE $condition";
        }
        $query = "SELECT COUNT(*) $columnName FROM $this->ObjectName $where";
        return $this->SelectQuery($query);
    }

    protected function SetSelectColums($columns) {
        $this->SelectColumns = array_merge($this->SelectColumns, $columns);
    }

    protected function SetSqlParams($name, $value, $type) {
        
    }

    protected function SelectQuery($query, $params = array()) {
        return \dibi::query($query, $params)->fetchAll();
    }

    public function TransactionBegin() {
        \dibi::begin();
    }

    public function TransactionEnd() {
        \dibi::commit();
    }

    public function RollbackTransaction() {
        \dibi::rollback();
    }

}
