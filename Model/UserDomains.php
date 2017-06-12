<?php

namespace Model;
use Dibi;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserDomains  extends DatabaseTable{
    public $DomainName;
    public $DomainIdentificator;
    public $Template;
    public $Domain;
    public $EditValue;
    public $IsSystem;
    public $ShowNameInSubDomain;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomains";
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
    public function GetDomainInfo($identificator)
    {
        return $this->GetFirstRow($this->SelectByCondition(" DomainIdentificator = '$identificator'"));
    }
    public function GenerateShowName($id, $arrayList,$prefix ="",$idColumn="Id")
    { 
        $this->GetObjectById($id,true);
        $showname = $this->ShowNameInSubDomain;
        if (empty($showname)) return $arrayList;
        foreach ($arrayList as &$row)
        {
            $tmp = $showname;
            foreach ($row as $k => $v)
            {
                if ($k=="ObjectId")
                {
                    $row[$idColumn] = $id."-".$v;
                }
                $tmp = str_replace("{".$k."}", $v, $tmp);
            }
            $row["ShowName"] = trim($prefix." ".$tmp);
        }
        
        
        return $arrayList;
    }
    
    
            
    
    

    
    public function OnCreateTable() {
        $colDomainName = new DataTableColumn();
        $colDomainName->DefaultValue ="";
        $colDomainName->IsNull = false;
        $colDomainName->Length = 255;
        $colDomainName->Name ="DomainName";
        $colDomainName->Type = "varchar";
        $colDomainName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainName);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue ="";
        $colDomainIdentificator->IsNull = false;
        $colDomainIdentificator->Length = 50;
        $colDomainIdentificator->Name ="DomainIdentificator";
        $colDomainIdentificator->Type = "varchar";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Length = 9;
        $colDomainIdentificator->Name ="Template";
        $colDomainIdentificator->Type = "INTEGER";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Length = 9;
        $colDomainIdentificator->Name ="Domain";
        $colDomainIdentificator->Type = "INTEGER";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Name ="EditValue";
        $colDomainIdentificator->Type = "BOOLEAN";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue =0;
        $colDomainIdentificator->Name ="IsSystem";
        $colDomainIdentificator->Type = "BOOLEAN";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        //
        $colDomainIdentificator = new DataTableColumn();
        $colDomainIdentificator->DefaultValue ="";
        $colDomainIdentificator->IsNull = false;
        $colDomainIdentificator->Length = 255;
        $colDomainIdentificator->Name ="ShowNameInSubDomain";
        $colDomainIdentificator->Type = "varchar";
        $colDomainIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainIdentificator);
        
        
    }
    

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("DomainName", RuleType::$NoEmpty,$this->GetWord("word185"));
        $this->SetValidateRule("DomainIdentificator", RuleType::$NoEmpty,$this->GetWord("word186"));
        $this->SetValidateRule("DomainIdentificator", RuleType::$Unique,$this->GetWord("word187"));   
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
