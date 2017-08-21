<?php

namespace Model;

use Utils;
use Dibi;
use Types\RuleType;
use Types\ModelRule;
use Types\DatabaseActions;
use Utils\StringUtils;
use Utils\ArrayUtils;
use DateTime;
use Utils\Files;
use Types\CallModelFunction;
use Types\DataTableColumn;
use Types\KeyType;

class DatabaseTable extends SqlDatabase {

    /**  @var int - primary key in table */
    public $Id;

    /** @var bool */
    public $Deleted;

    /** @var bool */
    public $IsSystem;

    /** @var bool */
    protected $MultiWeb = false;

    /** @var bool */
    protected $MultiLang = false;

    /** @var bool error in insert or update */
    public $IsError = false;

    /** @var bool error in insert or update */
    protected $SaveHistory = false;

    /** @var int */
    public $HistoryWebId;

    /** @var bool */
    public $IsInsert = false;

    /** @var bool */
    public $WasCreated = FALSE;

    /** @var int */
    public $LangId = 0;

    /** @var bool */
    protected $IgnoreValidate = false;

    /** @var array rule list in insert or update */
    private $_rules = array();

    /** @var  array */
    private $_columnValidate = array();

    /** @var  array */
    private $_columnsInfo = array();

    /** @var  array */
    private $_callModelFunction = array();

    /** @var  array */
    private $_parametrsColumn = array();

    /** @var  array */
    private $_errors = array();

    /** @var  array */
    private $_ignoredSave = array("TableName", "_columnsInfo", "Deleted", "WasCreated", "MultiWeb", "MultiLang", "_errors", "IsError", "WasExternalSet", "_rules", "_columnValidate", "_exportSettings", "_exportColumns", "SaveHistory", "_callModelFunction", "_parametrsColumn", "IsInsert", "_afterInsertAction", "ParentColumn", "IsBadLogin", "_tableSupportMove", "IsTable", "IsView", "DomainValidateErrors", "IgnoreValidate", "ExportColumns", "ObjectName", "IsFunction", "_ignoredSave", "TestQuery", "_copyMode", "_instance", "_sqlParams", "_utf8Set", "SelectColumns", "_readOnlyObject");

    /** @var bool */
    private $_copyMode = false;

    /** @var bool */
    private $_readOnlyObject = false;

    public function __construct() {
        $this->IsTable = true;
        parent::__construct();
    }

    /**
     * @param  string $colName
     * 
     */
    protected function AddIgnoreTosave($colName) {
        $this->_ignoredSave[] = $colName;
    }

    /**
     * @param string $keyName
     * @param string  $keyType 
     * @param array $keyColumns 
     * @category Databasekeys
     */
    protected function CreteKey($keyName, $keyType, $keyColumns) {
        if (!$this->KeyExists($keyName)) {
            $columns = implode(",", $keyColumns);
            if ($keyType == KeyType::$INDEX) {
                try {
                    dibi::query("ALTER TABLE `$this->ObjectName` ADD INDEX `$keyName` ($columns)");
                } catch (Exception $ex) {
                    \Kernel\Page::ApplicationError($ex);
                }
            } else if ($keyType == KeyType::$UNIQUE) {
                try {
                    dibi::query("ALTER TABLE `$this->ObjectName` ADD UNIQUE `$keyName` ($columns)");
                } catch (Exception $ex) {
                    \Kernel\Page::ApplicationError($ex);
                }
            } else if ($keyType == KeyType::$FULLTEXT)
                try {
                    dibi::query("ALTER TABLE `$this->ObjectName` ADD FULLTEXT `$keyName` ($columns)");
                } catch (Exception $ex) {
                    \Kernel\Page::ApplicationError($ex);
                }
        }
    }

    /**
     * @param string $keyName
     * @category Databasekeys
     * @return boolean
     */
    private function KeyExists($keyName) {
        try {
            $res = dibi::query("SHOW INDEX FROM $this->ObjectName WHERE Key_name = %s", $keyName)->fetchAll();
            return empty($res) ? FALSE : TRUE;
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /**
     * @param string $errorMessage 
     */
    public function SetError($errorMessage) {
        $this->IsError = true;
        $this->_errors[] = $errorMessage;
    }

    /**
     * @return array
     */
    public function GetError() {

        return $this->_errors;
    }

    /**
     * @param object $obj 
     * @param array() $parametrs   $array["columnName"] = value
     * @return integer
     */
    public function AddItem($obj, $parametrs) {
        foreach ($parametrs as $key => $value) {
            $key = trim($key);
            if (empty($key))
                continue;
            $obj->$key = trim(" " . $value);
        }
        $id = $obj->SaveObject();
        return $id;
    }

    /**
     * @param  objekt $obj 
     * @param  array $items   $array[0]["columnName"] = value
     * @return integer
     */
    public function AddMoreItem($obj, $items) {
        foreach ($items as $item) {
            $insert = array();
            foreach ($item as $key => $value) {
                if (is_array($value))
                    $value = "";
                $insert[$key] = trim(" " . $value);
            }
            $this->AddItem($obj, $insert);
        }
    }

    /**
     * @param $obj object
     * @param $xml string 
     * @return  integer 
     */
    public function InsertFromXml($obj, $xml) {
        $inArray = ArrayUtils::XmlToArray($xml);
        return $this->AddItem($obj, $inArray["item"]);
    }

    /** insert or update item in database 
     * @return int insert/update id item 0 = error
     */
    public function SaveObject() {

        if ($this->_readOnlyObject)
            return 0;

        $id = $this->Id;
        $insert = true;
        $res = array();
        $historyWebId = 0;
        if (!empty(($id))) {
            $res = $this->GetObjectById($id);
            if (!empty($res)) {
                $insert = FALSE;
                if ($res->Deleted) {
                    $this->SetError($this->GetWord("word119"));
                }
            }
        }

        $this->SetValidate($insert);

        $parametrs = array();
        $data = array();


        foreach ($this as $item => $value) {
            if ($item == "" || in_array($item, $this->_ignoredSave))
                continue;
            if ($item == "HistoryWebId") {
                $historyWebId = $value;
                continue;
            }

            $value = $this->IsValidColumn($item, $value, $id);
            if (in_array($item, $this->_parametrsColumn)) {
                $parametrs[$item] = $value;
            }
            $data[$item] = $value;
        }



        if (!$this->IsError) {
            unset($data["WebId"]);
            if ($this->MultiWeb) {
                if (!empty($_GET["webid"]))
                    $data["WebId"] = $_GET["webid"];

                else if ($this->ObjectName == "objecthistory" && empty($data["webId"])) {
                    $data["WebId"] = $historyWebId;
                }
            }
            if ($this->MultiLang) {

                if ($this->LangId == 0)
                    $data["LangId"] = empty($_GET["langid"]) ? $this->GetLangIdByWebUrl() : $_GET["langid"];
            }
            else {
                unset($data["LangId"]);
            }
            $action = DatabaseActions::NONE_ACTION;
            if ($insert) {
                // INSERT 
                $this->IsInsert = true;
                $action = DatabaseActions::INSERT;
                unset($data["Id"]);
                try {
                    dibi::query("INSERT INTO $this->ObjectName ", $data);
                    $id = dibi::getInsertId();
                } catch (Exception $e) {
                    Files::WriteLogFile($e);
                }
                $parametrs["Id"] = $id;
            } else {
                // UPDATE
                $action = DatabaseActions::UPDATE;
                $this->IsInsert = false;
                try {
                    dibi::query("UPDATE $this->ObjectName SET ", $data, " WHERE Id = %i", $id);
                } catch (Exception $e) {
                    Files::WriteLogFile($e);
                }
                $parametrs["Id"] = $id;
            }
            $this->SaveObjectHistory($id, $action, $res);
            $this->IgnoreValidate = false;
            $this->CallModelFunction($action, $parametrs);
            return $id;
        } else {

            $write = "";
            foreach ($this->GetError() as $error) {
                $write .= $error . "\n";
            }
            Files::WriteLogFile("ERROR SAVE OBJECT $write");
            return 0;
        }
    }

    private function AddDefaultsColums() {
        $this->_columnsInfo[] = new DataTableColumn("Id", \Types\DataColumnsTypes::INTEGER, "", false, 11, true, KeyType::$PrimaryKey);
        $this->_columnsInfo[] = new DataTableColumn("Deleted", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1);
        $this->_columnsInfo[] = new DataTableColumn("IsSystem", \Types\DataColumnsTypes::BOOLEAN, 0, true, 1);
        if ($this->MultiWeb) {
            $this->_columnsInfo[] = new DataTableColumn("WebId", \Types\DataColumnsTypes::INTEGER, 0, true, 9);
        }
        if ($this->MultiLang) {
            $this->_columnsInfo[] = new DataTableColumn("LangId", \Types\DataColumnsTypes::INTEGER, 0, true, 9);
        }
    }

    /**
     * @param string $column column name
     */
    protected function AddColumn($column) {
        if (!$this->ColumnExists($column->Name))
            $this->_columnsInfo[] = $column;
    }

    /**
     * @param boolean $createTable is table create 
     * @return string 
     *  */
    private function PrepareColmuns($createTable = FALSE) {
        $sql = array();
        foreach ($this->_columnsInfo as $row) {
            $autoincrement = $row->IsAutoIncrement ? "AUTO_INCREMENT" : ""; // auto increment
            $dbnull = $row->IsNull ? "" : "NOT NULL"; // db null
            // default value
            $isValidDefault = true;
            if (empty(trim($row->DefaultValue)))
                $isValidDefault = false;
            if (is_int($row->DefaultValue)) {
                if ($row->DefaultValue == 0)
                    $isValidDefault = true;
            }
            $defaultValue = ($isValidDefault) ? "DEFAULT " . $row->DefaultValue : "";

            // column type
            if ($row->Type == \Types\DataColumnsTypes::INTEGER || $row->Type == \Types\DataColumnsTypes::VARCHAR) {
                if ($row->Length >= 255)
                    $row->Length = 255;
            }
            if ($row->Type == \Types\DataColumnsTypes::BIT) {
                $row->Length = 1;
            }

            // for alter table
            $mode = "";
            if (!$createTable)
                $mode = empty($row->Mode) ? "" : $row->Mode;

            // length string 
            $length = ($row->Type == \Types\DataColumnsTypes::BOOLEAN || $row->Type == \Types\DataColumnsTypes::TEXT || $row->Type == \Types\DataColumnsTypes::LONGTEXT || $row->Type == \Types\DataColumnsTypes::DATETIME ) ? "" : "(" . $row->Length . ")";
            if (!$this->ColumnExists($row->Name))
                $sql[] = $mode . " [" . $row->Name . "] " . $row->Type . " " . $length . " " . $autoincrement . " $dbnull " . $row->Key . " " . $defaultValue;
        }

        $this->_columnsInfo = array();
        return implode(",", $sql);
    }

    /**
     * create column in database
     */
    public function SaveNewColums() {
        $sql = $this->PrepareColmuns();
        if (!empty($sql)) {
            try {
                dibi::query("ALTER TABLE $this->ObjectName $sql");
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex, dibi::test("ALTER TABLE $this->ObjectName $sql"));
            }
        }
    }

    /** test column exist in table 
     * @param string $columnName 
     * @return boolean
     */
    public function ColumnExists($columnName) {
        try {
            $res = dibi::query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . SQL_DATABASE . "' AND  TABLE_NAME='$this->ObjectName' AND column_name='$columnName'")->fetchAll();
            return empty($res) ? FALSE : TRUE;
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /**
     * create table in database
     */
    public function CreateTable() {
        if (!$this->TableExists($this->ObjectName)) {
            $this->WasCreated = true;
            $this->AddDefaultsColums();
            $sql = $this->PrepareColmuns(TRUE);
            if (!empty($sql)) {
                try {
                    dibi::query("CREATE TABLE IF NOT EXISTS " . $this->ObjectName . " (" . $sql . ") DEFAULT CHARSET=utf8 ");
                } catch (Exception $ex) {
                    \Kernel\Page::ApplicationError($ex, dibi::test("CREATE TABLE IF NOT EXISTS " . $this->ObjectName . " (" . $sql . ") DEFAULT CHARSET=utf8 "));
                }
            }
        }
    }

    /** test table is exist in database 
     * @param string $tableName
     * @return boolean
     */
    private function TableExists($tableName) {
        try {
            $res = dibi::query("SHOW FULL TABLES LIKE %s", $tableName)->fetchAll();
            return empty($res) ? FALSE : TRUE;
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex, dibi::test("CREATE TABLE IF NOT EXISTS " . $this->ObjectName . " (" . $sql . ") DEFAULT CHARSET=utf8 "));
        }
    }

    /**
     * function for truncate table in databse 
     */
    public function TruncateTable() {

        if ($this->SaveHistory) {
            $res = dibi::query("SELECT * FROM $this->ObjectName")->fetchAll();
            foreach ($res as $row) {
                $action = DatabaseActions::TRUNCATE;
                $this->SaveObjectHistory($row["Id"], $action, $row);
            }
            $history = new \Objects\ObjectHistory();
            $history->DeactiveHistoryItem($this->ObjectName);
        }
        try {
            dibi::query("TRUNCATE TABLE $this->ObjectName");
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /** read item by id 
     * @param int $id  
     * @param bool $setObject 
     * @param array() $columns - select columns 
     * @retrun array
     */
    public function GetObjectById($id, $setObject = false, $columns = array()) {
        if (empty($columns))
            $columns = $this->SelectColumns;
        $col = "*";
        if (!empty($columns)) {
            $col = implode(",", $columns);
        }
        $res = array();
        try {
            $res = dibi::query("SELECT $col FROM $this->ObjectName WHERE Id = %i", $id)->fetchAll();
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
        if (empty($res)) return null;
        $out = $res[0];
        if ($setObject && !empty($out)) {
            foreach ($out as $key => $value) {
                $this->$key = $value;
            }
        }
        return $out;
    }

    /** function for change item parent  
     * @param int $moveId source item 
     * @param int $destId new parent 
     * @param string $parentColumn column in database
     */
    public function MoveItem($moveId, $destId, $parentColumn) {
        try {
            dibi::query("UPDATE $this->ObjectName SET $parentColumn = $destId WHERE Id = $moveId");
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /** function for copy object 
     * @param int id object 
     * @param  bool $autoSave  new object 
     * @retun integer
     */
    public function CopyObject($id) {
        $this->GetObjectById($id, true);
        $this->Id = 0;
        $this->_copyMode = true;
        $id = $this->SaveObject();
        $this->_copyMode = false;
        return $id;
    }

    /**
     * function for delete data  
     * @param string $condition delete condition
     * @param bool $deletePernamently 
     * @param bool $saveHistory
     */
    public function DeleteByCondition($condition, $deletePernamently = false, $saveHistory = true) {
        if (!empty($condition)) {
            // vyjedeme všechny objekty na základě podmínky
            $res = $this->SelectByCondition($condition, "", array("Id"));
            if (empty($res))
                return;
            foreach ($res as $row) {
                $this->DeleteObject($row["Id"], $deletePernamently, $saveHistory);
            }
        }
    }

    /** funcion for delete one object 
     * @param $id -  
     * @param $deletePernamently - 
     * @param $saveHistory 
     * @return boolean
     */
    public function DeleteObject($id, $deletePernamently = false, $saveHistory = true) {
        if ($this->IsSystemObject($id))
            return false;

        if (!$deletePernamently && $this->ColumnExists("Deleted")) {

            if ($saveHistory && $this->SaveHistory) {
                $action = DatabaseActions::DELETE_ITEM;
                $this->SaveObjectHistory($id, $action);
            }
            try {
                dibi::query("UPDATE $this->ObjectName SET Deleted = 1 WHERE Id = %i", $id);
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex);
            }
        } else {
            if (!$deletePernamently)
                return false;
            if ($saveHistory && $this->SaveHistory) {
                $action = DatabaseActions::DELETE_PERNAMENTLY;
                $this->SaveObjectHistory($id, $action);
                $history = new \Objects\ObjectHistory();
                $history->DeactiveHistoryItem($this->ObjectName, $id);
            }
            try {
                dibi::query("DELETE FROM $this->ObjectName  WHERE Id = %i", $id);
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex);
            }
        }
        return true;
    }

    /**
     * function for test is object system
     * @param int $id
     * @return boolean
     */
    public function IsSystemObject($id) {
        if ($this->ColumnExists("IsSystem")) {
            $out = $this->GetObjectById($id, false, array("IsSystem"));
            return $out["IsSystem"];
        }
        return false;
    }

    /* function for recovery objects from history 
     * @param $id int
     *  */

    public function RecoveryObject($id) {
        $this->SaveObjectHistory($id, DatabaseActions::RECOVERY_ITEM);
        try {
            dibi::query("UPDATE $this->ObjectName SET Deleted = 0 WHERE Id = %i", $id);
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /** method for deleted all deleted items (column deleted = 1) in table  */
    public function Clean() {
        if ($this->ColumnExists("Deleted") && $this->ColumnExists("Id")) {
            $res = array();
            try {
                $res = dibi::query("SELECT Id FROM $this->ObjectName WHERE Deleted = 1")->fetchAll();
            } catch (Exception $ex) {
                \Kernel\Page::ApplicationError($ex);
            }
            foreach ($res as $row) {

                $id = $row["Id"];
                $this->SaveObjectHistory($id, DatabaseActions::CLEAN_ITEM);
                try {
                    dibi::query("DELETE  FROM $this->ObjectName WHERE Id = %i", $id);
                } catch (Exception $e) {
                    \Kernel\Page::ApplicationError($ex);
                }
            }
        }
    }

    /** method for set validate databse columnt 
     * @param string $column  - column name 
     * @param \Types\RuleType $rule rule name 
     * @param string $errorMessage
     */
    public function SetValidateRule($column, $rule, $errorMessage = "") {
        $this->_columnValidate[] = $column;
        // seourl must be first !!
        if ($rule == RuleType::$SeoString) {
            $ar = new ModelRule($column, $rule, $errorMessage);
            array_unshift($this->_rules, $ar);
        } else
            $this->_rules[] = new ModelRule($column, $rule, $errorMessage);
    }

    /** column validate and set value
     * @param string $columnName 
     * @parma object $value 
     * @param integer $noTestId 
     * @return bool
     */
    private function IsValidColumn($columnName, $value, $noTestId) {
        if ($this->IgnoreValidate)
            return true;

        if (in_array($columnName, $this->_columnValidate)) {
            foreach ($this->_rules as $row) {
                if ($row->Column == $columnName) {
                    if ($row->RuleType == RuleType::$NoEmpty) {
                        if (empty($value)) {
                            $this->SetError($row->ErrorMessage . "#$columnName");
                        }
                    }
                    if ($row->RuleType == RuleType::$Unique) {
                        if ($this->ItemExists($row->Column, $value, $noTestId)) {
                            if ($this->_copyMode) {
                                $value = "";
                            } else {
                                $this->SetError($row->ErrorMessage);
                            }
                        }
                    }
                    if ($row->RuleType == RuleType::$NoUpdate) {
                        $res = $this->GetObjectById($noTestId);
                        if (!empty($res)) {
                            if ($res->$columnName != $value) {
                                $value = $res->$columnName;
                            }
                        }
                    }
                    if ($row->RuleType == RuleType::$ToUpper) {
                        $value = strtoupper($value);
                    }
                    if ($row->RuleType == RuleType::$Hash) {
                        $value = StringUtils::HashString($value);
                    }
                    if ($row->RuleType == RuleType::$SeoString) {
                        $value = StringUtils::SeoString($value);
                    }
                    if ($row->RuleType == RuleType::$RemoveEntity) {
                        if (is_string($value))
                            $value = html_entity_decode($value);
                    }
                    if ($row->RuleType == RuleType::$UserIp) {
                        $value = $_SERVER["REMOTE_ADDR"];
                    }
                    if ($row->RuleType == RuleType::$ActualDateTime) {
                        $value = mktime(date("H"), date("i"), date("s"), date("n"), date("j"), date("Y"));
                    }
                    if ($row->RuleType == RuleType::$UserId) {
                        $u = new \Objects\Users();
                        $value = $u->GetUserId();
                    }
                }
            }
        }

        return $value;
    }

    /** function for import data from excel or xml 
     * @param string $filePath
     * @param $mode string $mode values deleteAndInsert,insert, update, insertUpdate
     */
    public function ImportFile($filePath, $mode) {
        $this->SetAllExportColumns();
        $insertArray = array();
        $classN = "Model\\" . $this->ObjectName;
        $obj = new $classN();
        if (Files::IsExcel("/" . $filePath)) {
            $firstLineHeader = true;
            $outData = Files::ReadExcel(ROOT_PATH . $filePath);
            $inArray = array();
            $i = 0;
            foreach ($outData as $row) {
                if ($i == 0 && $firstLineHeader) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < count($this->ExportColumns); $c++) {
                    $column = $this->ExportColumns[$c];
                    $inArray[$i][$column] = $row[$c];
                }
                $i++;
            }
            $insertArray = $inArray;
        } else if (Files::IsXml("/" . $filePath)) {

            $xmlData = Files::ReadFile(ROOT_PATH . $filePath);
            $xml = simplexml_load_string($xmlData);
            $row = 0;
            foreach ($xml as $xmlItem) {

                foreach ($this->ExportColumns as $key) {
                    $insertArray[$row][$key] = trim(" " . $xmlItem->$key . " ");
                }
                $row++;
            }
        }
        if (!empty($insertArray)) {
            $newArray = array();
            if ($mode == "deleteAndInsert") {
                $this->TruncateTable();
            } else if ($mode == "insert" || $mode == "update") {
                $newAr = array();
                foreach ($insertArray as $row) {
                    $id = $row["Id"];
                    $exists = $this->ItemExists("Id", $id, 0);
                    if ($exists && $mode == "update") {
                        $newAr[] = $row;
                    } else if (!$exists && $mode == "insert") {
                        $newAr[] = $row;
                    }
                }
                $insertArray = $newAr;
            }
            $this->AddMoreItem($obj, $insertArray);
        }
    }

    /** save item to history 
     * @param int $id 
     * @param \Types\DatabaseActions $action 
     * @param object $data
     * 
     */
    private function SaveObjectHistory($id, $action, $data = null) {
        if ($this->SaveHistory) {
            $className = "Model\\" . $this->ObjectName;
            $obj = new $className();
            $users = new \Objects\Users();
            $ohistory = new \Objects\ObjectHistory();

            if ($data == null) {
                $data = $obj->GetObjectById($id);
            }
            $oldData = ArrayUtils::DibiRowToXml((array) $data);
            $webId = !empty($data["WebId"]) ? $data["WebId"] : 0;
            $ohistory->CreateHistoryItem($this->ObjectName, $id, $action, $users->GetUserId(), \Utils\Utils::GetIp(), $oldData, true, $users->GetUserName(), $webId);
        }
    }

    /**
     * @var string $columnName
     *  */
    protected function SetParametrsColumn($columnName) {
        $this->_parametrsColumn[] = $columnName;
    }

    /** nastavení metody, které se má provést po db operaci
     * @param string $class
     * @param string $function 
     * @param object $parametrs
     * @param \Types\DatabaseActions $type
     */
    public function SetCallModelFunction($class, $function, $parametrs, $type) {
        $callFunction = new CallModelFunction($class, $function, $parametrs, $type);
        $this->_callModelFunction[] = $callFunction;
    }

    /** call function after UPDATE or INSERT
     * @param type $name Description 
     * @param array $parametrs
     */
    private function CallModelFunction($action, $parametrs = null) {
        foreach ($this->_callModelFunction as $row) {
            if ($row->Type == $action) {
                $className = "Model\\" . $row->Class;
                $class = new $className();
                $function = $row->Function;
                if (empty($parametrs))
                    $class->$function();
                else {
                    $param = implode(",", $parametrs);
                    eval($class->$function($param));
                }
            }
        }
    }

    /** nsert default data when is table is create
      @param object  $obj
     */
    protected function Setup() {
        $filePath = ROOT_PATH . "Setup/" . $this->ObjectName . ".sql";
        if (Files::FileExists($filePath)) {
            $sql = \Utils\Files::ReadFile($filePath);
            $sql = trim($sql);
            $this->QueryWithMysqli($sql);
        }
    }

    /**
     * run direct mysql query
     * @param  string $query
     */
    private function QueryWithMysqli($query) {
        try {
            $sql_mode = $this->GetSqlVariable("sql_mode");
            $this->SetSqlVariable("sql_mode","");
            $con = mysqli_connect(SQL_SERVER, SQL_LOGIN, SQL_PASSWORD, SQL_DATABASE);
            mysqli_query($con, "SET NAMES 'utf8'");
            mysqli_query($con, $query);
            $this->SetSqlVariable("sql_mode",$sql_mode);
            mysqli_close($con);
        } catch (Exception $e) {
            \Kernel\Page::ApplicationError($ex);
        }
    }
    
    /**
     * test table is empty
     */
    public function TableIsEmpty() {
        try {
            $res = \dibi::query("SELECT Id FROM $this->ObjectName");
            return empty($res) ? true : false;
        } catch (Exception $e) {
            \Kernel\Page::ApplicationError($ex);
        }
    }

    /**
     * add default columns to select 
     */
    protected function SetDefaultSelectColumns() {
        $selectComlums[] = "Id";
        $selectComlums[] = "Deleted";
        $selectComlums[] = "IsSystem";
        if ($this->MultiLang)
            $selectComlums[] = "LangId";
        if ($this->MultiWeb)
            $selectComlums[] = "WebId";
        $this->SetSelectColums($selectComlums);
    }

    /**
     * update materialized view 
     * @param strign  $materializedView view name 
     */
    public function UpdateMaterializedView($materializedView) {
        $className = "Model\\$materializedView";
        $view = new $className();
        $view->UpdateMaterializeView();
    }

    /** migrations columns in database
     * @param string $query
     */
    protected function RunTableMigrate($query) {
        $dbMigrations = new DbMigrations();
        $res = $dbMigrations->SelectByCondition("QueryMigrations = '$query'", "", array("Id"));
        if (empty($res)) {
            try{
                \Dibi::query($query);
            }
            catch (Exception $ex)
            {
                \Kernel\Page::ApplicationError($ex);
            }
            
            $dbMigrations->QueryMigrations = $query;
            $dbMigrations->SaveObject();
        }
    }

}
