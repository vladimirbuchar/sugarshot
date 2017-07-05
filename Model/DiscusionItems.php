<?php

namespace Model;
use Dibi;
use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class DiscusionItems  extends DatabaseTable{
    public $SubjectDiscusion;
    public $TextDiscusion;
    public $UserId;
    public $ShowUserName;
    public $DateTime;
    public $UserIp;
    public $IsLast;
    public $VersionId;
    public $ParentIdDiscusion;
    public $DiscusionId;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "DiscusionItems";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("SubjectDiscusion","TextDiscusion","UserId","ShowUserName","DateTime","UserIp","IsLast","VersionId","ParentIdDiscusion","DiscusionId"));
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
    }*/
    
    public function  AddNewDiscusionItem($subject,$text,$showUserName,$parent,$discusionId,$historyId)
    {
        if(empty($historyId)) $historyId = 0;
        $user = Users::GetInstance();
        if ($user->UserHasBlockDiscusion())
            return;
        dibi::query("UPDATE DiscusionItems SET IsLast = 0 WHERE VersionId = %i",$historyId);
        $this->SubjectDiscusion = $subject;
        $this->TextDiscusion = $text;
        $this->ShowUserName = $showUserName;
        $this->IsLast = true;
        $this->ParentIdDiscusion = $parent;
        $this->DiscusionId = $discusionId;
        $this->VersionId = $historyId;
        $badWords = $this->CheckBadWords();
        if ($badWords)
        {
            $id = $this->SaveObject($this);    
            if ($historyId == 0)
            {
                $this->GetObjectById($id,true);
                $this->VersionId = $id;
                $this->SaveObject($this);
            }
        }
    }
    public function GetDiscusionItems($id,$limit = 0)
    {
        if ($limit > 0)
        {
            $limit = " LIMIT 0,$limit";
        }
        else 
        {
            $limit = "";
        }
        $res = dibi::query("SELECT * FROM DISCUSIONITEMSLIST WHERE DiscusionId = %i  ORDER BY Id DESC $limit",$id)->fetchAll();
        return $res;
    }
    
    
    
    public function GetHistoryItemDetail($id)
    {
        $out =  $this->SelectByCondition("VersionId = $id AND IsLast = 0");
        foreach ($out as $row)
        {
            $row["DateTime"] = date("m-d-Y H:m:s",$row["DateTime"]);
        }
        return $out;
    }
    
   
    
    private function CheckBadWords()
    {
        $domainsValues = \Model\UserDomainsValues::GetInstance();
        $userDomain = \Model\UserDomains::GetInstance();
        $domainInfo = $userDomain->GetDomainInfo("BadWords");
        $badWords = $domainsValues->GetDomainValueList($domainInfo["Id"],false);
        foreach ($badWords as $row)
        {
            $badWord = $row["BadWord"];
            if (strpos($this->SubjectDiscusion,$badWord) !== FALSE ||  strpos($this->TextDiscusion,$badWord) !== FALSE)
                    return false;
        }
        return true;    
    }
    
    
    
    
    
    
    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =9;
        $colLangName->Name ="DiscusionId";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =9;
        $colLangName->Name ="VersionId";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =9;
        $colLangName->Name ="ParentIdDiscusion";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =1;
        $colLangName->Name ="IsLast";
        $colLangName->Type = "BOOLEAN";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =255;
        $colLangName->Name ="SubjectDiscusion";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangIdentificator = new DataTableColumn();
        $colLangIdentificator->DefaultValue ="";
        $colLangIdentificator->IsNull = false;
        $colLangIdentificator->Name ="TextDiscusion";
        $colLangIdentificator->Type = "TEXT";
        $colLangIdentificator->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangIdentificator);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =9;
        $colLangName->Name ="UserId";
        $colLangName->Type = "INT";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =255;
        $colLangName->Name ="ShowUserName";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =20;
        $colLangName->Name ="DateTime";
        $colLangName->Type = "int";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length =255;
        $colLangName->Name ="UserIp";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
         
    }
    

    public function InsertDefaultData() {
        
    }

    
    public function SetValidate($mode = false) {
                $this->SetValidateRule("UserIp", RuleType::$UserIp);
        $this->SetValidateRule("DateTime", RuleType::$ActualDateTime);
        $this->SetValidateRule("UserId", RuleType::$UserId);
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
