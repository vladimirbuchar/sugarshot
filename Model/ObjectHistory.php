<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class ObjectHistory  extends DatabaseTable{
    public $ObjectHistoryName;
    public $ObjectId;
    public $Action;
    public $UserId;
    public $IP;
    public $OldData;
    public $CreateDate;
    public $ActiveItem;
    public $UserName;
    //private static $_instance = null;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = false;
        $this->ObjectName = "ObjectHistory";
        $this->MultiWeb = true;
        
    }
    /*
    public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/
    public function GetHistoryObject($objectName,$objectId)
    {
        return dibi::query("SELECT * FROM  ObjectHistory WHERE ObjectName = %s AND ObjectId = %i  AND ActiveItem = 1 ORDER BY CreateDate DESC ",$objectName,$objectId)->fetchAll();
    }
    
    public function RecoveryItemFromHistory($idHistory)
    {
        $res = dibi::query("SELECT * FROM  ObjectHistory WHERE Id = %i  ",$idHistory)->fetchAll();
        if (!empty($res))
        {
            $res = $this->GetFirstRow($res);
            if ($res->Action == DatabaseActions::$Update)
            {
                $objName =  $res->ObjectHistoryName;
                $xml = $res->OldData;
                $obj = new $objName;
                $this->InsertFromXml($obj,$xml);
            }
        }
    }
    
    public function DeactiveHistoryItem($objectName,$id =0)
    {
        if ($id == 0)
            dibi::query("UPDATE ObjectHistory SET ActiveItem = 0 WHERE ObjectHistoryName = %s",$objectName);
        else if($id >0)
        dibi::query("UPDATE ObjectHistory SET ActiveItem = 0 WHERE ObjectHistoryName = %s AND ObjectId = %i" ,$objectName,$id);
    }
    
    public function CreateHistoryItem($objectName,$objectId,$action,$userId,$IP,$oldData,$activeItem,$userName,$historyWebId)
    {
        $this->ObjectHistoryName = $objectName;
        $this->ObjectId = $objectId;
        $this->Action = $action;
        $this->UserId = $userId;
        $this->IP = $IP;
        $this->OldData = $oldData;
        $this->CreateDate = new \DateTime();
        $this->ActiveItem = $activeItem;
        $this->UserName = $userName;
        $this->HistoryWebId = $historyWebId;
        $this->SaveObject();

    }


    
    
    
    
    public function OnCreateTable() {
        $colObjectName = new DataTableColumn();
        $colObjectName->DefaultValue ="";
        $colObjectName->IsNull = false;
        $colObjectName->Length = 100;
        $colObjectName->Name ="ObjectHistoryName";
        $colObjectName->Type = "varchar";
        $colObjectName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colObjectName);
        
        $colObjectId = new DataTableColumn();
        $colObjectId->DefaultValue ="";
        $colObjectId->IsNull = false;
        $colObjectId->Length = 9;
        $colObjectId->Name ="ObjectId";
        $colObjectId->Type = "INTEGER";
        $colObjectId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colObjectId);
        
        $colAction = new DataTableColumn();
        $colAction->DefaultValue ="";
        $colAction->IsNull = false;
        $colAction->Length = 100;
        $colAction->Name ="Action";
        $colAction->Type = "varchar";
        $colAction->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colAction);
        
        $colUserId = new DataTableColumn();
        $colUserId->IsNull = true;
        $colUserId->Length = 9;
        $colUserId->Name ="UserId";
        $colUserId->Type = "INTEGER";
        $colUserId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserId);
        
        
                $colUserId = new DataTableColumn();
        $colUserId->IsNull = true;
        $colUserId->Length = 50;
        $colUserId->Name ="UserName";
        $colUserId->Type = "varchar";
        $colUserId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserId);
        
        $colIP = new DataTableColumn();
        $colIP->DefaultValue ="";
        $colIP->IsNull = false;
        $colIP->Length = 100;
        $colIP->Name ="IP";
        $colIP->Type = "varchar";
        $colIP->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colIP);
        
        $colOldData = new DataTableColumn();
        $colOldData->DefaultValue ="";
        $colOldData->IsNull = true;
        $colOldData->Length = 100;
        $colOldData->Name ="OldData";
        $colOldData->Type = "TEXT";
        $colOldData->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colOldData);
        
        $colCreateDate = new DataTableColumn();
        $colCreateDate->DefaultValue ="";
        $colCreateDate->IsNull = false;
        $colCreateDate->Length = 100;
        $colCreateDate->Name ="CreateDate";
        $colCreateDate->Type = "DATETIME";
        $colCreateDate->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colCreateDate);
        
        $colActiveItem = new DataTableColumn();
        $colActiveItem->DefaultValue =TRUE;
        $colActiveItem->IsNull = true;
        $colActiveItem->Name ="ActiveItem";
        $colActiveItem->Type = "BOOLEAN";
        $colActiveItem->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colActiveItem);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "IsSystem";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
    }
    

    public function InsertDefaultData() {
        
    }

    public function SetValidate($mode = false) {
        
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
