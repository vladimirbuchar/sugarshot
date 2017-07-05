<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class ContentSecurity  extends DatabaseTable{
    
    public $GroupId;
    public $SecurityType;
    public $ObjectId;
    public $Value;
    //private static $_instance = null;
    
    
    public function __construct()
    {
        
        parent::__construct();
        
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentSecurity";
        $this->SetSelectColums(array("GroupId","SecurityType","ObjectId","Value"));
        $this->SetDefaultSelectColumns();
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
    }
    */
    
    
    public function OnCreateTable() {
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="GroupId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="ObjectId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue ="";
        $colContentType->IsNull = false;
        $colContentType->Length = 50;
        $colContentType->Name ="SecurityType";
        $colContentType->Type = "varchar";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 1;
        $colContentType->Name ="Value";
        $colContentType->Type = "BOOLEAN";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        
 
    }
    
    public function CanPrivileges($objectId, $userGroup,$privilegesName)
    {
        
        $data = $this->SelectByCondition("Value = 1 AND ObjectId = $objectId AND  GroupId = $userGroup AND SecurityType = '$privilegesName'");
        if (empty($data)) return FALSE;
        return TRUE;
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
