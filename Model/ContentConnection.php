<?php
namespace Model;
use Utils\ArrayUtils;
use Dibi;
use Types\PrivilegesType;
use Types\DataTableColumn;
use Types\AlterTableMode;
class ContentConnection  extends DatabaseTable{
    
    public $ObjectId;
    public $ObjectIdConnected;
    public $ConnectedType;
    public $SettingConnection;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentConnection";
        $this->MultiLang = false;
        $this->MultiWeb= true;
        $this->SetSelectColums(array("ObjectId","ObjectIdConnected","ConnectedType","SettingConnection"));
        $this->SetDefaultSelectColumns();
       
    }

    
   
    
    
    public function DisconnectObjects($ObjectId, $ConnectedType)
    {
        $contentVersion = ContentVersion::GetInstance();
        if (!$contentVersion->HasPrivileges($ObjectId, PrivilegesType::$CanWrite))
            return;
        $this->DeleteByCondition("ObjectId = ".$ObjectId." AND ConnectedType = '$ConnectedType'",true,false);
    }
    
    public function OnCreateTable() {
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name ="ObjectId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name ="ObjectIdConnected";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = false;
        $colContentType->Length = 100;
        $colContentType->Name ="ConnectedType";
        $colContentType->Type = "VARCHAR";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = true;
        $colContentType->Name ="SettingConnection";
        $colContentType->Type = "TEXT";
        $colContentType->DefaultValue = "";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        
        

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