<?php

namespace Model;
use Utils\StringUtils;
use Dibi;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
use Types\DatabaseActions;
class Modules  extends DatabaseTable{
    public $ModuleName;
    public $ModuleControler;
    public $ModuleView;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Modules";
        $this->MultiWeb = true;
        $this->SetSelectColums(array("ModuleName","ModuleControler","ModuleView"));
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
    public function  SetupModule($id)
    {
        $userGroupModule =  UserGroupsModules::GetInstance();
        $system =  UserGroups::GetInstance();
        $systemGrouup= $system->GetUserGroupByIdeticator("system");
        $systemId = $systemGrouup->Id;
        $userGroupModule->SetUserGroupModules($systemId, $id);        
    }
    
    public function GetModuleUrl($moduleController,$moduleView,$prefix,$hashUrl = FALSE)
    {
        $url = "/$prefix/".$moduleController."/".$moduleView."/";
        if ($hashUrl)
            $url = StringUtils::EncodeString($url);
        return $url;
    }
    
    public function GetModuleByIdentificator($identificator)
    {
        return  $this->GetFirstRow($this->SelectByCondition("ModuleIdentificator = '".$identificator."'"));
    }
    
    public function CanModuleShow($controller,$view,$userId)
    {
        
        $res = dibi::query("SELECT * FROM USERMODULESVIEW WHERE ModuleControler = %s AND ModuleView = %s AND UserGroupId =%i",$controller,$view,$userId)->fetchAll();
        if(count($res) == 0) return false;
        return true;
    }
            
    


    
    
    public function OnCreateTable() {
        $colModuleName = new DataTableColumn();
        $colModuleName->DefaultValue ="";
        $colModuleName->IsNull = false;
        $colModuleName->Length = 50;
        $colModuleName->Name ="ModuleName";
        $colModuleName->Type = "varchar";
        $colModuleName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colModuleName);
        
        $colModuleIdentificator = new DataTableColumn();
        $colModuleIdentificator->DefaultValue ="";
        $colModuleIdentificator->IsNull = false;
        $colModuleIdentificator->Length = 50;
        $colModuleIdentificator->Name ="ModuleControler";
        $colModuleIdentificator->Type = "varchar";
        $colModuleIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colModuleIdentificator);
        
        $colModuleGroup = new DataTableColumn();
        $colModuleGroup->DefaultValue ="";
        $colModuleGroup->IsNull = false;
        $colModuleGroup->Length = 50;
        $colModuleGroup->Name ="ModuleView";
        $colModuleGroup->Type = "varchar";
        $colModuleGroup->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colModuleGroup);
        
        
    }

    public function InsertDefaultData() {
        $this->Setup($this);   
    }

    

    public function SetValidate($mode = false) {
        $this->SetValidateRule("ModuleName", RuleType::$NoEmpty,$this->GetWord("word150"));
        $this->SetValidateRule("ModuleControler", RuleType::$NoEmpty,$this->GetWord("word151"));
        $this->SetValidateRule("ModuleView", RuleType::$NoEmpty,$this->GetWord("word152"));
        $this->SetCallModelFunction("Modules","SetupModule",array(),DatabaseActions::$Insert);
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
