<?php

namespace Model;
use Utils\ArrayUtils;
use Dibi;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserGroups  extends DatabaseTable{
    public $GroupName;
    public $IsSystemGroup;
    public $GroupIdentificator;
    public $UserDefaultState;
    //private static $_instance = null;
            
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserGroups";
        $this->SetSelectColums(array("GroupName","IsSystemGroup","GroupIdentificator","UserDefaultState"));
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
    public function GetUserGroupDetail($id)
    {
        $res = dibi::query("SELECT * FROM USERGROUPDETAIL WHERE Id = %i",$id)->fetchAll();
        $res = ArrayUtils::ColummToArray($res, "ModuleId","UserWebId");
        return $res;
    }
    
    public function GetUserGroupByIdeticator($identificator)
    {
        $res = $this->SelectByCondition("GroupIdentificator = '$identificator'");
        return $this->GetFirstRow($res);
    }
    
    public function GetAnonymousGroup()
    {
        
        if (self::$SessionManager->IsEmpty("AnonymousInfo"))
        {
            $res = $this->GetUserGroupByIdeticator("anonymous");
            self::$SessionManager->SetSessionValue("AnonymousInfo", $res);
            
        }
        return self::$SessionManager->GetSessionValue("AnonymousInfo");
        
    }
    
    public function GetSystemGroups()
    {
        $user = Users::GetInstance();
        $condition = "IsSystemGroup = 1 AND Deleted = 0";
        if ($user->IsSystemUser())
          return $this->SelectByCondition($condition);
        return $this->SelectByCondition($condition." AND GroupName <> 'system'");
    }
    public function GetNoSystemGroups()
    {
        $condition = "IsSystemGroup = 0 AND Deleted = 0";
        return $this->SelectByCondition($condition);

    }
    
    public function ChangeSystemGroupToAdmin()
    {
        $user = Users::GetInstance();
        if ($user->IsSystemUser())
        {
            $res = $this->SelectByCondition("GroupIdentificator = 'Administrators'");
            return $res[0]["Id"];
        }
        return 0;
        
    }
    
    
    
    public function GetUserGroups($removeIdentificator = array())
    {
        if (empty($removeIdentificator))
            return $this->SelectByCondition ("Deleted = 0");
        $where = "";
        
        for ($i = 0; $i< count($removeIdentificator);$i++)
        {
            $identificator = $removeIdentificator[$i];
            if (empty($identificator)) continue;
            if (empty($where)) $where =" GroupIdentificator <>'".$identificator."' ";
            else $where .=" AND GroupIdentificator <> '".$identificator."'";
        }
        $user = Users::GetInstance();
        if ($user->IsSystemUser())
            return $this->SelectByCondition("($where) AND Deleted = 0 ");   
        
        return $this->SelectByCondition("($where) AND Deleted = 0 AND   GroupIdentificator <>'system'");   
    }
    
            
    public function OnCreateTable() {
        
        $colUserGroupName = new DataTableColumn();
        $colUserGroupName->DefaultValue ="";
        $colUserGroupName->IsNull = false;
        $colUserGroupName->Length = 50;
        $colUserGroupName->Name ="GroupName";
        $colUserGroupName->Type = "varchar";
        $colUserGroupName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserGroupName);
        
        $colIsSystemGroup = new DataTableColumn();
        $colIsSystemGroup->DefaultValue =0;
        $colIsSystemGroup->IsNull = false;
        $colIsSystemGroup->Length = 1;
        $colIsSystemGroup->Name ="IsSystemGroup";
        $colIsSystemGroup->Type = "BOOLEAN";
        $colIsSystemGroup->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colIsSystemGroup);
        
        $colGroupIdentificator = new DataTableColumn();
        $colGroupIdentificator->DefaultValue ="";
        $colGroupIdentificator->IsNull = true;
        $colGroupIdentificator->Length = 50;
        $colGroupIdentificator->Name ="GroupIdentificator";
        $colGroupIdentificator->Type = "varchar";
        $colGroupIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colGroupIdentificator);
        
        $colGroupIdentificator = new DataTableColumn();
        $colGroupIdentificator->DefaultValue ="";
        $colGroupIdentificator->IsNull = true;
        $colGroupIdentificator->Length = 50;
        $colGroupIdentificator->Name ="UserDefaultState";
        $colGroupIdentificator->Type = "varchar";
        $colGroupIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colGroupIdentificator);
        
 
    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("GroupName", RuleType::$NoEmpty,$this->GetWord("word90"));
        $this->SetValidateRule("GroupName", RuleType::$Unique,$this->GetWord("word91"));
    }
    public function TableMigrate()
    {
        
    }
    
    public function TableExportSettings()
    {
        
    }

}
