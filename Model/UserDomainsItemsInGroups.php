<?php

namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserDomainsItemsInGroups  extends DatabaseTable{
    public $ItemId;
    public $GroupId;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsItemsInGroups";
        
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
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="ItemId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="GroupId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
    }
    public function SaveItemInGroup($groupId,$items)
    {
        $this->DeleteByCondition("GroupId = $groupId");
        
        for($i = 0;$i< count($items);$i++)
        {
            $this->GroupId = $groupId;
            $this->ItemId = $items[$i][0];
            $this->SaveObject();
        }
        
    }
    
    public function GetUserItemInGroups($groupId)
    {
        return $this->SelectByCondition("GroupId = $groupId AND Deleted = 0");
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
