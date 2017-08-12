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

class DatabaseTable extends SqlDatabase{

    /** id položky - vkládá se do každé tabulky*/
    public $Id;
    /** smazaná položky - dává se do každé tabulky*/
    public $Deleted;
    /** system object - dává se do každé tabulky*/
    public $IsSystem;
    
    /** položka je dostupná v aktuIální webu*/
    protected $MultiWeb = false;
    /**položka je dostupná ve všech jazycích daného webu*/ 
    protected $MultiLang = false;
    /**seznam chyb při insertu/update */

    /** informace zda došlo k chybě insertu/update */
    public $IsError = false;
    
    /** informace zda se má ukládat historie řádků*/ 
    protected $SaveHistory = false;
    /**ukládané webid do historie*/
    public $HistoryWebId;
    /** jedná se o insert */
    public $IsInsert = false;
    /**informace zda již byla tabulka v db vyvořea */
    public $WasCreated = FALSE;
    /** sloupec, kam se ukláda parent objektu */
    
    /** id aktuálního jazyka*/
    public $LangId = 0;
    protected $IgnoreValidate = false;
    
    /** seznam pravidel které se musí dodžet při insertu/update */
    private $_rules = array();
    /** seznam sloupců k validaci*/
    private $_columnValidate = array();
    private $_columnsInfo = array();
    private $_callModelFunction = array();
    private $_parametrsColumn = array();
    private $_errors = array();
    //private $_afterInsertAction = array();
    private $_ignoredSave =array("TableName","_columnsInfo","Deleted","WasCreated","MultiWeb","MultiLang","_errors","IsError","WasExternalSet","_rules","_columnValidate","_exportSettings","_exportColumns","SaveHistory","_callModelFunction","_parametrsColumn","IsInsert","_afterInsertAction" ,"ParentColumn","IsBadLogin","_tableSupportMove","IsTable","IsView","DomainValidateErrors","IgnoreValidate","ExportColumns","ObjectName","IsFunction","_ignoredSave","TestQuery","_copyMode","_instance","_sqlParams","_utf8Set","SelectColumns","_readOnlyObject");
    private  $_copyMode = false;
    private $_readOnlyObject = false;
    

    public function __construct() {
        $this->IsTable = true;
        parent::__construct();
        
            
        
    }
    
    protected  function AddIgnoreTosave($colName)
    {
        $this->_ignoredSave[] = $colName;
    }


    /*DATABASE KEYS*/
    /**
     * @param string $keyType type key
     * @category Databasekeys
     */
    protected function CreteKey($keyName,$keyType,$keyColumns)
    {
        if (!$this->KeyExists($keyName))
        {
            
            $columns = implode(",", $keyColumns);
            if ($keyType == KeyType::$INDEX)
                dibi::query("ALTER TABLE `$this->ObjectName` ADD INDEX `$keyName` ($columns)");
           else if ($keyType == KeyType::$UNIQUE)
               dibi::query("ALTER TABLE `$this->ObjectName` ADD UNIQUE `$keyName` ($columns)");
           else if ($keyType == KeyType::$FULLTEXT)
           dibi::query("ALTER TABLE `$this->ObjectName` ADD FULLTEXT `$keyName` ($columns)");
               
        }
    }
    
    private function KeyExists($keyName)
    {
        $res = dibi::query("SHOW INDEX FROM $this->ObjectName WHERE Key_name = %s",$keyName)->fetchAll();
        if (empty($res)) return false;
        return true;
    }
    
    /** DATABASE KEYS END */

    /*public function SetAfterInsertAction($function)
    {
        $this->_afterInsertAction[] = $function;
    }*/

    
    // CHYHBY
    /** nastavení chybové hlášky
     * @param type $errorMessage - text chybové hlášky
     */
    private function SetError($errorMessage) {
        $this->IsError = true;
        Files::WriteLogFile($errorMessage);
        $this->_errors[] = $errorMessage;
    }
    /** vrací pole s chybovýma hlaškama
     */
    public function GetError() {
        
        return $this->_errors;
    }
    
    // KONEC CHYBY
    
    // INSERT UPDATE
    /** používa se pri vkládání do db z číselníku 
     * @param $obj objekt
     * @param $parametrs parametry ve tvru $aray["columnName"] = value
     */
    public function AddItem($obj, $parametrs) {
        foreach ($parametrs as $key => $value) {
            $key = trim($key);
            if(empty($key))
                continue;
            $obj->$key = trim(" ".$value);
        }
        $id = $obj->SaveObject($obj);
        return $id;
    }
    /** metoda volá AddItem */ 
    public function AddMoreItem($obj, $items) {
        foreach ($items as $item) {
            $insert = array();
            foreach ($item as $key => $value) {
                if (is_array($value))
                    $value = "";
                $insert[$key] = trim(" ".$value);
            }
            $this->AddItem($obj, $insert);
            
        }
    }
    /** metoda pro insert z xml
     * @param $obj object
     * @param $xml string 
     */
    public function InsertFromXml($obj, $xml) {
        $inArray = ArrayUtils::XmlToArray($xml);
        $this->AddItem($obj, $inArray["item"]);
        
    }
    /** samaotné uložení do db 
     * @param $saveData pole ve tvaru $array[columnn name] = value
     * @return vložené id
     */ 
    public function SaveObject($saveData = null) {
        try{
            if ($this->_readOnlyObject)
                return 0;
            if ($saveData == null)
                $saveData = $this;
            $id = $saveData->Id;
            $insert = true;
            $res = array();
            $historyWebId = 0;
            
            // provedeme test jestli objekt existuje poku ano jedná se o update
            if (!empty(($id))) {
                $res = $this->GetObjectById($id);
                if (!empty($res))
                {
                    $insert = FALSE;
                    // nelze aktulizovat smazaný objekt
                    if ($res->Deleted)
                    {
                        $this->SetError($this->GetWord("word119"));
                    }
                }   
            }
            
        $saveData->SetValidate($insert);
        
        $parametrs = array();
        $data = array();
        
        // příprava data včetně validace
        foreach ($saveData as $item => $value) {
            if ($item == "" || in_array($item, $this->_ignoredSave))
                continue;
            if ($item == "HistoryWebId") {
                $historyWebId = $value;
                continue;
            }
            
            $value = $this->IsValidColumn($item, $value, $id);
            if (in_array($item, $this->_parametrsColumn))
            {
                $parametrs[$item] = $value;
            }
            $data[$item] = $value;
        }
        
        // pokrčujeme jen v připadě, že nedošlo k chybě
        
        if (!$this->IsError) {
            unset($data["WebId"]);
            if ($saveData->MultiWeb) {
                    if (!empty($_GET["webid"]))
                        $data["WebId"] = $_GET["webid"];
                    
                    else if ($this->ObjectName == "objecthistory" && empty($data["webId"])) {
                        $data["WebId"] = $historyWebId;
                    }
                }
                if ($saveData->MultiLang)
                {
                    
                    if ($saveData->LangId == 0)
                        $data["LangId"] = empty($_GET["langid"]) ?$this->GetLangIdByWebUrl() :$_GET["langid"] ;
                }
                else 
                {
                    unset($data["LangId"]);
                }
             $action = DatabaseActions::$NoneAction;   
            if ($insert) {
                // INSERT 
                $this->IsInsert = true;
                $action = DatabaseActions::$Insert;
                 unset($data["Id"]);
                 try{
                    dibi::query("INSERT INTO $this->ObjectName ", $data);
                 }
                 catch(Exception $e )
                 {
                      dibi::test("INSERT INTO $this->ObjectName ", $data);
                     echo $e;;die();
                 }
                 
                $id = dibi::getInsertId();
                $parametrs["Id"] = $id;
            } else {
                // UPDATE
                $action = DatabaseActions::$Update;
                $this->IsInsert = false;
                dibi::query("UPDATE $this->ObjectName SET ", $data, " WHERE Id = %i", $id);
                $parametrs["Id"] = $id;
            }
            $this->SaveObjectHistory($id, $action, $res);
            /** provedeme after akce z ostních tříd */
            $this->IgnoreValidate = false;
            $this->CallModelFunction($action,$parametrs);
            return $id;
        } else
        {
            
            $write ="";
            foreach ($this->GetError() as $error)
            {
                $write .=$error."\n";
            }
            Files::WriteLogFile("ERROR $write");
            return 0;
        }
        }
        catch (Exception $ex)
        {
            $this->_copyMode = FALSE;
            Files::WriteLogFile($ex);
            return 0;
        }
    }
    // PRACE SE SLOUPCI Z DB 
    /** 
     * metoda pro vložení defaultních sloupců do db
     */
    private function AddDefaultsColums() {
        $idColumn = new DataTableColumn();
        $idColumn->IsAutoIncrement = true;
        $idColumn->IsNull = false;
        $idColumn->Name = "Id";
        $idColumn->Type = "INTEGER";
        $idColumn->Key = KeyType::$PrimaryKey;
        $this->_columnsInfo[] = $idColumn;

        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "Deleted";
        $deletedColumn->Type = "BOOLEAN";
        $this->_columnsInfo[] = $deletedColumn;
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "IsSystem";
        $deletedColumn->Type = "BOOLEAN";
        $this->_columnsInfo[] = $deletedColumn;

        if ($this->MultiWeb) {
            $idWeb = new DataTableColumn();
            $idWeb->IsAutoIncrement = false;
            $idWeb->DefaultValue = 0;
            $idWeb->IsNull = true;
            $idWeb->Length = 9;
            $idWeb->Name = "WebId";
            $idWeb->Type = "INTEGER";
            $this->_columnsInfo[] = $idWeb;
        }
        if ($this->MultiLang) {
            $idLang = new DataTableColumn();
            $idLang->IsAutoIncrement = false;
            $idLang->DefaultValue = 0;
            $idLang->IsNull = true;
            $idLang->Length = 9;
            $idLang->Name = "LangId";
            $idLang->Type = "INTEGER";
            $this->_columnsInfo[] = $idLang;
        }
    }
    /** metoda pro přidání sloupce do db
     * @param $column sloupec
     */
    protected function AddColumn($column) {
        if (!$this->ColumnExists($column->Name))
            $this->_columnsInfo[] = $column;
    }
    /** metoda pro vytvoření dotozu pro vytvoření sloupce
     * @param $createTable zda se jedná o vytvaření tabulky
     * @return string 
     *  */
    private function PrepareColmuns($createTable = FALSE) {
        $sql = array();
        
        foreach ($this->_columnsInfo as $row) {
            $autoincrement = $row->IsAutoIncrement ? "AUTO_INCREMENT" : ""; // auto increment
            $dbnull = $row->IsNull ? "" : "NOT NULL"; // db null
            // výchozí hodnota
            $isValidDefault = true; 
            if (empty(trim($row->DefaultValue)))
                $isValidDefault = false;
            if (is_int($row->DefaultValue)) {
                if ($row->DefaultValue == 0)
                    $isValidDefault = true;
            }
            $defaultValue = ($isValidDefault) ? "DEFAULT " . $row->DefaultValue : ""; 
            
            // typ sloupce
            if ($row->Type == "INTEGER") {
                if ($row->Length >= 255)
                    $row->Length = 255;
            }
            if ($row->Type =="BIT")
            {
                $row->Length = 1;
            }
            
            // pro alter table
            $mode = "";
            if (!$createTable)
                $mode = empty($row->Mode) ? "" : $row->Mode;
            
            // delka retezce
            $length = ($row->Type == "BOOLEAN" || $row->Type == "TEXT"  || $row->Type =="LONGTEXT" || $row->Type == "DATETIME" ) ? "" : "(" . $row->Length . ")";
            if (!$this->ColumnExists($row->Name))
                $sql[] = $mode . " [" . $row->Name . "] " . $row->Type . " " . $length . " " . $autoincrement . " $dbnull " . $row->Key . " " . $defaultValue;
        }

        $this->_columnsInfo = array();
        return implode(",", $sql);
    }
    /**
     * vložení nového slouce do db
     */
        public function SaveNewColums() {
        $sql = $this->PrepareColmuns();
        if (!empty($sql)) {
            try {
                dibi::query("ALTER TABLE $this->ObjectName $sql");
            } catch (Exception $ex) {
                dibi::test("ALTER TABLE $this->ObjectName $sql");
                \Kernel\Page::ApplicationError($ex);
            }
        }
    }
    /** metoda na otestování zda sloupec v db existuje
     * @param $columnName 
     */
    public function ColumnExists($columnName) {
        $res = dibi::query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".SQL_DATABASE."' AND  TABLE_NAME='$this->ObjectName' AND column_name='$columnName'")->fetchAll();
        
        if (empty($res))
        {
            return FALSE;
        }

        return TRUE;
    }
    
    // PRACE SE SLOUPCI KONEC
    
    // PRACE S TABULKOU
    
    /** metoda pro vytvoření tabulky v databázi */ 
    public function CreateTable() {
        if (!$this->TableExists($this->ObjectName)) {
            $this->WasCreated = true;
            $this->AddDefaultsColums();
            $sql = $this->PrepareColmuns(TRUE);
            if (!empty($sql)) {
                try {
                    dibi::query("CREATE TABLE IF NOT EXISTS " . $this->ObjectName . " (" . $sql . ") DEFAULT CHARSET=utf8 ");
                } catch (Exception $ex) {
                    \Kernel\Page::ApplicationError($ex);
                }
            }
        }
    }
    /** otestování zda existuje tabulka v db
     * @param tableName
     * @return boolean
     */
    private function TableExists($tableName) {
        $res = dibi::query("SHOW FULL TABLES LIKE %s", $tableName)->fetchAll();
        if (empty($res))
            return FALSE;
        return true;
    }
    /** provedení TRUNCATE TABLE*/
    public function TruncateTable() {
        
        if ($this->SaveHistory)
        {
            $res = dibi::query("SELECT * FROM $this->ObjectName")->fetchAll();
            foreach ($res as $row) {
                $action = DatabaseActions::$Truncate;
                $this->SaveObjectHistory($row["Id"], $action, $row);
            }
            $history = ObjectHistory::GetInstance();
            $history->DeactiveHistoryItem($this->ObjectName);
        }
        dibi::query("TRUNCATE TABLE $this->ObjectName");
    }
    // PRACE S TABULKOU KONEC

    
    
    /** získání objektu dle id  
     * @param $id int 
     * @param $setObject bool
     */
    public function GetObjectById($id,$setObject = false, $columns = array()) {
        if (empty($columns))
            $columns = $this->SelectColumns;
        $col = "*";
        if (!empty($columns))
        {
            $col = implode(",", $columns);
        }
        $res = dibi::query("SELECT $col FROM $this->ObjectName WHERE Id = %i", $id)->fetchAll();
        $out = $this->GetFirstRow($res);
        if ($setObject && !empty($out))
        {
            foreach ($out as $key =>$value)
            {
                $this->$key = $value;
            }
        }
        return $out;
    }
    
    

    
    // SELECT KONEC
    
    // MOVE COPY
    
    public function MoveItem($moveId,$destId,$parentColumn)
    {
        dibi::query("UPDATE $this->ObjectName SET $parentColumn = $destId WHERE Id = $moveId");
    } 
    public function CopyObject($id,$autoSave = true)
    {
        $this->GetObjectById($id,true);
        $this->Id = 0;
        if ($autoSave)
        {
            $this->_copyMode = true;
            $id =  $this->SaveObject();
            $this->_copyMode = false;
            return $id;
        }
        return -1;
    }
    
   
    
    
    
    
    
    
    //DELETE  A OBNOVENI
    /** smazání objektů dle podmínky
@param string $condition
     *      */
    public function DeleteByCondition($condition,$deletePernamently = false,$saveHistory = true)
    {
        if (!empty($condition))
        {   
            // vyjedeme všechny objekty na základě podmínky
            $res = $this->SelectByCondition($condition,"",array("Id"));
            if (empty($res)) return;
            foreach ($res as $row)
            {
                $this->DeleteObject($row["Id"],$deletePernamently,$saveHistory);
            }
        }
    }
    /** metoda pro smazaní objektu 
     * @param $id - to co chceme smazat 
     * @param $deletePernamently - použít MySQL příkaz DELETE
     * @param $saveHistory uložit do historie
     */
    public function DeleteObject($id, $deletePernamently = false,$saveHistory = true) {
        if ($this->IsSystemObject($id))
            return false;
        
        if (!$deletePernamently && $this->ColumnExists("Deleted") ) {
            
            if ($saveHistory && $this->SaveHistory)
            {
                $action = DatabaseActions::$DeleteItem;
                $this->SaveObjectHistory($id, $action);
            }
            dibi::query("UPDATE $this->ObjectName SET Deleted = 1 WHERE Id = $id");
        } else {
            if (!$deletePernamently)
                return false;
            if($saveHistory && $this->SaveHistory)
            {
                $action = DatabaseActions::$DeletePernamently;
                $this->SaveObjectHistory($id, $action);
                $history = ObjectHistory::GetInstance();
                $history->DeactiveHistoryItem($this->ObjectName, $id);
            }
            
            dibi::query("DELETE FROM $this->ObjectName  WHERE Id = $id");
        }
        
        
        return true;
    }
    
    public  function IsSystemObject($id)
    {
        if ($this->ColumnExists("IsSystem"))
        {
            $obj = $this->GetObjectById($id);
            return $obj->IsSystem;
        }
        
        return false;
    }
    
    /**metoda pro obnovení objektu 
     * @param $id int id objektu 
     *  */
    public function RecoveryObject($id) {
        $this->SaveObjectHistory($id, DatabaseActions::$RecoveryItem);
        dibi::query("UPDATE $this->ObjectName SET Deleted = 0 WHERE Id = $id");
    }
    
    public function Clean()
    {
        if ($this->ColumnExists("Deleted") && $this->ColumnExists("Id"))
        {
            $res = dibi::query("SELECT Id FROM $this->ObjectName WHERE Deleted = 1")->fetchAll();
            foreach ($res as $row)
            {
                try{
                $id = $row["Id"];
                $this->SaveObjectHistory($id, DatabaseActions::$CleanItem);
                dibi::query("DELETE  FROM $this->ObjectName WHERE Id = %i",$id);
                }
                catch(Exception $e)
                {
                    
                }
                
            }
        }
    }
    
    
    
    //DELETE KONEC
    // VALIDACE HODNOT 
    /** nastavení sloupce který se má validovat
     * @param $column - slouoec k validaci
     * @param $rule pravidlo 
     * @param $errorMessage chybová hláška 
     */
    public function SetValidateRule($column, $rule, $errorMessage="") {
        
        $this->_columnValidate[] = $column;
        // seourl se musí provést jako první 
        if ($rule == RuleType::$SeoString)
        {
            $ar =new ModelRule($column, $rule, $errorMessage);
            array_unshift($this->_rules,$ar);
        }
        else 
            $this->_rules[] = new ModelRule($column, $rule, $errorMessage);
        
    }
    
    /** ověření zda sloupec validní 
     * @param $columnName jméno sloupce
     * @parma $value hodnota
     * @param $noTestId netestovaná hodnota
     */

    private function IsValidColumn($columnName, $value, $noTestId) {
        if ($this->IgnoreValidate)
            return true;
        
        if (in_array($columnName, $this->_columnValidate)) {
            foreach ($this->_rules as $row) {
                if ($row->Column == $columnName) {
                    if ($row->RuleType == RuleType::$NoEmpty) {
                        if (empty($value)) {
                            $this->SetError($row->ErrorMessage."#$columnName");
                        }
                    }
                    if ($row->RuleType == RuleType::$Unique) {
                        if ($this->ItemExists($row->Column, $value, $noTestId)) {
                            if ($this->_copyMode)
                            {
                                $value = "";
                            }
                            else
                            {
                                $this->SetError($row->ErrorMessage);
                                Files::WriteLogFile("Exists:".$row->Column."-". $value."-". $noTestId);
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
                    if ($row->RuleType == RuleType::$Hash)
                    {
                       $value = StringUtils::HashString($value);
                    }
                    if ($row->RuleType == RuleType::$SeoString)
                    {
                        $value = StringUtils::SeoString($value);   
                    }
                    if ($row->RuleType == RuleType::$RemoveEntity)
                    {
                        if (is_string($value))
                            $value = html_entity_decode($value);
                    }
                    if ($row->RuleType == RuleType::$UserIp)
                    {
                        $value = $_SERVER["REMOTE_ADDR"];
                    }
                    if ($row->RuleType ==  RuleType::$ActualDateTime)
                    {
                        $value = mktime(date("H"),date("i"),date("s"),date("n"),date("j"),date("Y") );
                    }
                    if ($row->RuleType == RuleType::$UserId)
                    {
                        $u =  Users::GetInstance();
                        $value = $u->GetUserId();
                    }

                }
            }
        }
        
        return $value;
    }
    
    // VALIDACE KONEC

    
    /** metoda pro import do modelu
     * @param $filePath string cesta k souboru 
     * @param $mode string deleteAndInsert,insert, update, insertUpdate
     */
    public function ImportFile($filePath, $mode) {
        $this->SetAllExportColumns();
        $insertArray = array();
        $classN = "Model\\".$this->ObjectName;
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
    // EXPORT A IMPORT KONEC
    
    // OSTATNI
    /** metoda pro vytvoření historie
     * @param $id id objektu
     * @param $action akce
     * @param $data
     * 
     */
    
    private function SaveObjectHistory($id, $action, $data = null) {
        if ($this->SaveHistory) {
            $className = "Model\\".$this->ObjectName;
            $obj = new $className();
            $users =  Users::GetInstance();
            $ohistory = ObjectHistory::GetInstance();
            
            if ($data == null) {
                $data = $obj->GetObjectById($id);
            }
            $oldData = ArrayUtils::DibiRowToXml((array) $data);
            $webId = !empty($data["WebId"])? $data["WebId"]:0;
            $ohistory->CreateHistoryItem($this->ObjectName, $id, $action, $users->GetUserId(), \Utils\Utils::GetIp(), $oldData, true, $users->GetUserName(),$webId);
        }
    }
    
    protected function SetParametrsColumn($columnName)
    {
        $this->_parametrsColumn[] =$columnName;
    }
    /** nastavení metody, které se má provést po db operaci
     * @param $class třída
     * @param $function 
     * @param $parametrs
     * @param $type - kdy se má metoda zavolat
     *  */
    public function SetCallModelFunction($class, $function, $parametrs, $type) {
        $callFunction = new CallModelFunction($class, $function, $parametrs, $type);
        $this->_callModelFunction[] = $callFunction;
    }
    
    /** call function after UPDATE or INSERT
     * @param type $name Description 

     *      */
    private function CallModelFunction($action,$parametrs =null) {
        foreach ($this->_callModelFunction as $row) {
            if ($row->Type == $action) {
                $className = "Model\\".$row->Class;
                $class = new $className();
                $function = $row->Function;
                if (empty($parametrs))
                    $class->$function();
                else 
                {
                    $param = implode(",", $parametrs);
                    eval($class->$function($param));
                }
                    
            }
        }
    }
    
    /** nsert default data when is table is create
        @param object  $oob 
     */
    protected function Setup($obj)
    {
        $this->InitialDatabase($obj->ObjectName.".sql");
    }
    private function  InitialDatabase($name)
    {
        $filePath = ROOT_PATH."Setup/$name";
        if (Files::FileExists($filePath))
        {
            $sql = \Kernel\Files::ReadFile($filePath);
            $sql = trim($sql);  
            $this->QueryWithMysqli($sql);
        }  
    }
    private  function QueryWithMysqli($query)
    {
        $con=mysqli_connect(SQL_SERVER,SQL_LOGIN,SQL_PASSWORD,SQL_DATABASE);
        mysqli_query($con, "SET NAMES 'utf8'");
        mysqli_query($con,$query);
        mysqli_close($con);
    }
    
    /** insert row to table from xml 
     * @param  string  $xmlString  ml string 
     * param object $obj
     */
    protected function AddFromXmlMore($xmlString,$obj)
    {
        $xmlData = simplexml_load_string($xmlString);
        foreach ($xmlData as $row)
        {
            foreach ($row as $key =>$value)
            {
                $obj->$key = trim($value);    
            }
            $obj->SaveObject($obj);
        }   
    }
    
    /** 
     * test table is empty
    */
    protected function TableIsEmpty()
    {
        $res = \dibi::query("SELECT Id FROM $this->ObjectName");
        if (empty($res)) return true;
        return false;
    }
    protected function SetDefaultSelectColumns()
    {
        $selectComlums[] = "Id";
        $selectComlums[] = "Deleted";
        $selectComlums[] = "IsSystem";
        if ($this->MultiLang)
            $selectComlums[] = "LangId";
        if ($this->MultiWeb)
            $selectComlums[] = "WebId";
        $this->SetSelectColums($selectComlums);
    }
    
    public function UpdateMaterializedView($materializedView)
    {
        $className = "Model\\$materializedView";
        $view = new $className();
        $view->UpdateMaterializeView();
    }
            
            
}
