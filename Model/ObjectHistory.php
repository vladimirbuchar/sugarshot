<?php

namespace Model;
use Types\RuleType;
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
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = false;
        $this->ObjectName = "ObjectHistory";
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ObjectHistoryName","ObjectId","Action","UserId","IP","OldData","CreateDate","ActiveItem","UserName"));
        $this->SetDefaultSelectColumns();
        
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
        
     
    }
    

    public function InsertDefaultData() {
        $this->Setup($this);
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
