<?php
namespace Model;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UsersInGroup  extends DatabaseTable{
    public $UserId;
    public $GroupId;
    public $IsMainGroup;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UsersInGroup";
    }
    
   /* public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    } */
    
    public function AddUserToGroup($userId,$groupId, $isMain=FALSE)
    {
        $this->UserId = $userId;
        $this->GroupId = $groupId;
        $this->IsMainGroup = $isMain;
        $this->SaveObject($this);
    }
    public function GetMainUserGroup($userid)
    {
        $res = $this->SelectByCondition("UserId = $userid AND IsMainGroup = 1 AND Deleted = 0");
        if (empty($res)) return 0;
        return $res[0]["GroupId"];
    }
    public function GetMinorityUserGroup($userId)
    {
        return $this->SelectByCondition("UserId =  $userId AND Deleted= 0 AND IsMainGroup = 0");
    }
    
    public function OnCreateTable() {
        
        $colUserId = new DataTableColumn();
        $colUserId->DefaultValue ="";
        $colUserId->IsNull = false;
        $colUserId->Length = 9;
        $colUserId->Name ="UserId";
        $colUserId->Type = "INTEGER";
        $colUserId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserId);
        
        $colGroupId = new DataTableColumn();
        $colGroupId->DefaultValue ="";
        $colGroupId->IsNull = false;
        $colGroupId->Length = 9;
        $colGroupId->Name ="GroupId";
        $colGroupId->Type = "INTEGER";
        $colGroupId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colGroupId);
        
        $colIsMainGroup = new DataTableColumn();
        $colIsMainGroup->DefaultValue =0;
        $colIsMainGroup->IsNull = true;
        $colIsMainGroup->Length = 1;
        $colIsMainGroup->Name ="IsMainGroup";
        $colIsMainGroup->Type = "BOOLEAN";
        $colIsMainGroup->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colIsMainGroup);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "IsSystem";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
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
