<?php
namespace Controller;
use Types\SortDatabase;
use Model\ObjectHistory;
abstract class SettingsController extends AdminController {
    
/** tuto třídu požívají číselníky */
    public function __construct() {
        
        parent::__construct();
        
        $this->SharedView = "List";
        if (self::$IsAjax)
        {
            $this->SetAjaxFunction("DeleteItem",array("system","Administrators"));
            $this->SetAjaxFunction("AddItem",array("system","Administrators"));
            $this->SetAjaxFunction("GetDetailItem",array("system","Administrators"));
            $this->SetAjaxFunction("CopyItem",array("system","Administrators"));
            $this->SetAjaxFunction("ExportData",array("system","Administrators"));
            $this->SetAjaxFunction("Import",array("system","Administrators"));
            $this->SetAjaxFunction("LoadTable",array("system","Administrators"));
            $this->SetAjaxFunction("RecoveryItem",array("system","Administrators"));
            $this->SetAjaxFunction("ShowHistory", array("system","Administrators"));
            $this->SetAjaxFunction("RecoveryFromHistory", array("system","Administrators"));
        }
    }
    /** metoda pro smazání objektu */
    public function  DeleteItem()
    {
        
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        $deletePernamently = false;  
         
        
        if (!empty($ajaxParametrs["DeletePernamently"]))
        {
            if ($ajaxParametrs["DeletePernamently"] == "true")
                $deletePernamently = true;
            
        }
        $id = $ajaxParametrs["Id"];
        $model = $model = "Model\\".$ajaxParametrs["ModelName"];
        $item = new $model();
        $item->DeleteObject($id,$deletePernamently);
    }
    /** metoda pro přidání položky*/
    public function AddItem()
    {
        
        $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return 0;
        if (empty($ajaxParametrs["ModelName"])) return 0;
        
        $model = "Model\\".$ajaxParametrs["ModelName"];
        unset($ajaxParametrs["ModelName"]);
        unset($ajaxParametrs["deleteId"]);
        unset($ajaxParametrs["recoveryId"]);
        unset($ajaxParametrs["copyId"]);
        $item =  $model::GetInstance();
        if (empty($ajaxParametrs["Id"]))
            $ajaxParametrs["Id"] = 0;
        
        $id = $item->AddItem($item,$ajaxParametrs);
        
        $out = array();
        $out["Id"] = $id;
        if ($item->IsError)
        {
            $out["Errors"] = $item->GetError();
        }
        return $out;
    }
    /** metoda pro zobrazení detailu položky */
    public function  GetDetailItem()
    {
        $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        $id = $ajaxParametrs["Id"];
        $model = "Model\\".$ajaxParametrs["ModelName"];
        
        $item = new $model();
        $out = (array)$item->GetObjectById($id);
        return $out;
    }
    /** metoda pro zkopírování položky*/
    public function  CopyItem()
    {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        $id = $ajaxParametrs["Id"];
        $model =  "Model\\".$ajaxParametrs["ModelName"];
        $item = new $model();
        return $item->CopyObject($id,true);
        
    }
    /** metoda  pro vyexportování dat z číselníku  */
    public function ExportData()
    {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        $modelName =  "Model\\".$ajaxParametrs["ModelName"];
        $model = new $modelName();
        $mode = $ajaxParametrs["ExportType"];
        return $model->Export($mode);
    }
    /** metoda pro naimportovaní dat do číselníku*/
    public function Import()
    {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        $modelName =  "Model\\".$ajaxParametrs["ModelName"];
        $model = new $modelName();
        $filePath = $ajaxParametrs["FilePath"];
        $mode = $ajaxParametrs["Mode"];
        return $model->ImportFile($filePath,$mode);
    }
    /** metoda pro znovu načtení tabulky  */
    public function LoadTable()
    {
        self::$SessionManager->UnsetKey("where");
        $ajaxParametrs = $this->PrepareAjaxParametrs();
        if (empty($ajaxParametrs)) return;
        
        $sort = null;
        $where = "";
        $saveToSession = true;
        if (!empty($ajaxParametrs["SortColumn"]) && !empty($ajaxParametrs["SortType"]))
            $sort = new SortDatabase ($ajaxParametrs["SortType"], $ajaxParametrs["SortColumn"]);
        $modelName =  "Model\\".$ajaxParametrs["ModelName"];
        $sessionId = $ajaxParametrs["ModelName"];
        $model = new $modelName();
        if (!empty($ajaxParametrs["SaveFiltrSortToSession"]))
        {
            $saveToSession = $ajaxParametrs["SaveFiltrSortToSession"] =="true" ? true:false;
        }
        
        $extWhere = self::$SessionManager->IsEmpty($sessionId. "_extWhere")  ?"" :self::$SessionManager->GetSessionValue($sessionId. "_extWhere");
        if (!empty($extWhere))
        {
            if($saveToSession)
            {
                self::$SessionManager->SetSessionValue("where", $extWhere, $sessionId);
            }
        }
        
        $showDeleteItem = false;
        if (!empty($ajaxParametrs["ShowItem"]))
        {
            if($ajaxParametrs["ShowItem"] == "DeleteItem")
                $showDeleteItem = true;
            else 
                $showDeleteItem = false;
        }
        $objectid= 0;
        if (!empty($_GET["objectid"]))
            $objectid = $_GET["objectid"];
        $outData = array();
        if ($model->IsTable)
        {
            $outData =  $model->Select(array(),$showDeleteItem, true, true,$sort,$where,$saveToSession,$objectid);
        }
        else if ($model->IsView)
        {
            $outData = $model->Select(array(),$showDeleteItem,true, true,$sort,$where,$saveToSession,$objectid);
        }
        $outData = $this->ReplaceHtmlWord($outData);
        return $outData;
    }
    /** metoda pro zobrazení smazaných dat z číselníku */
    public function RecoveryItem()
    {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        
        $id = $ajaxParametrs["Id"];
        $modelName =  "Model\\".$ajaxParametrs["ModelName"];
        $model = new $modelName();
        $item = new $model();
        $item->RecoveryObject($id);
    }
    /** metoda pro zobrazení historie změn v objektu */
    public function ShowHistory()
    {
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        $id = $ajaxParametrs["Id"];
        $modelName =  "Model\\".$ajaxParametrs["ModelName"];
        $model = new $modelName();
        $item =  ObjectHistory::GetInstance();
        return $item->GetHistoryObject($ajaxParametrs["ModelName"], $id);
    }
    /** metoda pro obnovení dat z historie*/
    public function RecoveryFromHistory()
    {
        
        $ajaxParametrs = array();
        if (!empty($_GET["params"]))
            $ajaxParametrs = $this->PrepareAjaxParametrs($_GET["params"]);
        if (empty($ajaxParametrs))$ajaxParametrs =  $this->PrepareAjaxParametrs($_POST["params"]);
        if (empty($ajaxParametrs)) return;
        $id = $ajaxParametrs["Id"];
        $item = ObjectHistory::GetInstance();
        return $item->RecoveryItemFromHistory($id);
        
    }

    
    
}