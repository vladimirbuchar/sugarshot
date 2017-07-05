<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class ContentAlternative  extends DatabaseTable{
    
    public $ContentId;
    public $AlternativeContentId;
    public $UserGroupId;
    //private static $_instance = null;
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentAlternative";
        $this->MultiWeb= true;
        $this->SetSelectColums(array("ContentId","AlternativeContentId","UserGroupId"));
        $this->SetDefaultSelectColumns();
    }
    /*public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/
    
    public function SaveAlternativeItem($objectId,$userGroupId,$alternativeItemId)
    {
        $this->DeleteByCondition("ContentId = $objectId AND UserGroupId = $userGroupId", true, false);
        $this->ContentId = $objectId;
        $this->UserGroupId = $userGroupId;
        $this->AlternativeContentId = $alternativeItemId;
        $this->SaveObject();
    }
    
    
    
    
    public function OnCreateTable() {
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="ContentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="AlternativeContentId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
       
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->Length = 9;
        $colContentType->Name ="UserGroupId";
        $colContentType->Type = "INTEGER";
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
