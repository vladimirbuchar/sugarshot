<?php

namespace Model;
use Utils\StringUtils;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserDomainsAddiction  extends DatabaseTable{
    public $DomainId;
    public $AddictionName;
    public $Item1;
    public $Item1Value;
    public $ItemX;
    public $ItemXValue;
    public $ActionName;
    public $RuleName;
    public $Priority; 
    
    public $IsDomain1;
    public $DomainId1;
    public $ItemId1;
    
    public $IsDomainX;
    public $DomainIdX;
    public $ItemIdX;
    //private static $_instance = null;
     
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsAddiction";      
        
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
    
        
    public function OnCreateTable() {
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="DomainId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 50;
        $colShowName->Name ="AddictionName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";  
        $colDomainId->IsNull = false;
        $colDomainId->Length = 20;
        $colDomainId->Name ="Item1";
        $colDomainId->Type = "varchar";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 255;
        $colShowName->Name ="Item1Value";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 20;
        $colDomainId->Name ="ItemX";
        $colDomainId->Type = "varchar";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 255;
        $colShowName->Name ="ItemXValue";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 50;
        $colShowName->Name ="ActionName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colShowName = new DataTableColumn();
        $colShowName->DefaultValue ="";
        $colShowName->IsNull = false;
        $colShowName->Length = 50;
        $colShowName->Name ="RuleName";
        $colShowName->Type = "varchar";
        $colShowName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colShowName);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =1;
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="Priority";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="IsDomain1";
        $colDomainId->Type = "BIT";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="DomainId1";
        $colDomainId->Length = 9;
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="ItemId1";
        $colDomainId->Length = 9;
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="IsDomainX";
        $colDomainId->Type = "BIT";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="DomainIdX";
        $colDomainId->Length = 9;
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue =0;
        $colDomainId->IsNull = true;
        $colDomainId->Name ="ItemIdX";
        $colDomainId->Length = 9;
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
    }
    
    public function  SaveAddiction($id,$domainId,$name,$item1,$ruleName,$item1Value,$actionName,$itemXValue,$itemX,$priority)
    {
        $this->Id = $id;
        $this->DomainId = $domainId;
        $this->AddictionName = $name;
        $this->Item1 = $item1;
        $this->RuleName = $ruleName;
        $this->Item1Value = $item1Value;
        $this->ActionName = $actionName;
        $this->ItemXValue = $itemXValue;    
        $this->ItemX = $itemX;
        $this->Priority = $priority;
        
        if (StringUtils::ContainsString($item1, "-"))
        {
            $ar = explode("-", $item1);
            $this->IsDomain1 = true;
            $this->DomainId1 = $ar[0];
            $this->ItemId1 = $ar[1];
        }
        if (StringUtils::ContainsString($itemX, "-"))
        {
            $ar = explode("-", $itemX);
            $this->IsDomainX = true;
            $this->DomainIdX = $ar[0];
            $this->ItemIdX = $ar[1];
        }
        $this->SaveObject();
    }
    
    public function GetAddictionDomain($domainId)
    {
        $res = dibi::query("SELECT UserDomainsAddiction.*,Item1Info.Identificator AS Item1Identificator,Item1Info.Type AS Item1Type,"
                . "ItemXInfo.Identificator AS ItemXIdentificator,ItemXInfo.Type AS ItemXType "
                . "FROM UserDomainsAddiction ".
                " LEFT JOIN UserDomainsItems Item1Info ON UserDomainsAddiction.Item1 = Item1Info.Id AND Item1Info.Deleted = 0 "
                . " LEFT JOIN UserDomainsItems ItemXInfo ON UserDomainsAddiction.ItemX = ItemXInfo.Id  AND ItemXInfo.Deleted = 0 "
                . "WHERE UserDomainsAddiction.DomainId =%i AND UserDomainsAddiction.Deleted = 0 ORDER BY Priority DESC",$domainId)->fetchAll();
        return $res;
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
