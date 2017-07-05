<?php

namespace Model;
use Utils\ArrayUtils;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class UserDomainsValues  extends DatabaseTable{
    public $DomainId;
    public $ItemId;
    public $ObjectId;
    public $Value;
    private $DomainValidateErrors = array();
    //private static $_instance = null;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsValues";
        $this->SetSelectColums(array("DomainId","ItemId","ObjectId","Value"));
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
    
    public function GetFiles($domainIdentificator,$data)
    {
        $filesList = array();
        $domain =  \Model\UserDomainsItems::GetInstance();
        $domainData = $domain->GetUserDomainItems($domainIdentificator);
        $domainData = ArrayUtils::ValueAsKey($domainData,"Identificator");
        $x = 0;
        foreach ($data as $key=>$value)
        {
            if (empty($domainData[$key])) continue;
            $row = $domainData[$key];
            if ($row["Type"] == "file")
            {
                $filesList[$x]["file"] = $value;
                $filesList[$x]["name"] = "";
                $x++;
            }
        }
        return $filesList;
    }
    
    public function GetObjectId($domainId,$itemId,$value){
       $res =dibi::query("SELECT * FROM UserDomainsValues WHERE DomainId = %i AND ItemId=%i AND Value = %s AND Deleted= 0",$domainId,$itemId,$value)->fetchAll();
       if (empty($res)) return 0;
       return $res[0]["ObjectId"];
        
    }
    
    public function IsValidValue($domainIdentificator,$data,$groupId = 0)
    {
        $isvalid = TRUE;
        $domain =  new UserDomainsItems();
        $domainData = array();
        if ($groupId == 0)
            $domainData = $domain->GetUserDomainItems($domainIdentificator);
        else 
            $domainData = $domain->GetUserDomainItems($domainIdentificator,$groupId);
        $values = $this->GetAllValuesByIdentificator($domainIdentificator);
        
        $domainData = ArrayUtils::ValueAsKey($domainData,"Identificator");
        $tmpValue = ArrayUtils::ValueAsKey($values,"Value");
        
        
        foreach ($data as $key=>$value)
        {
            if (empty($domainData[$key])) continue;
            $validateRow = $domainData[$key];
            $validate = $validateRow["Validate"];
            $type = $validateRow["Type"];
            
            $required = $validateRow["Required"] == "1" ? true:false;
            $maxLength = empty($validateRow["MaxLength"]) ? 0: $validateRow["MaxLength"];
            $minLength = empty($validateRow["MinLength"]) ? 0: $validateRow["MinLength"];
            $unique = ($validateRow["UniqueValue"] =="1") ? true:false;
            if ($required && empty($value) && !is_array($value))
            {
                $this->SetValidateError($key, "required");
                $isvalid = false;
            }
            if ($required && is_array($value))
            {
                $empty = true;
                for ($x = 0; $x< count($value);$x++ )
                {
                    if(!empty(trim($value[$x])))
                    {
                        $empty = false;
                    }
                }
                if ($empty)
               {
                    $isvalid = false;
                    $this->SetValidateError($key, "required");

               }
            }
            if ($maxLength > 0 && strlen($value)> $maxLength )
            {
                $this->SetValidateError($key, "maxlength");
                $isvalid = false;
            }
            if ($minLength > 0 && strlen($value) < $minLength)
            {
                $this->SetValidateError($key, "minlength");
                $isvalid = false;
            }
            if ($unique && !empty($value))
            {
                
                if (!empty($tmpValue[$value]))
                {
                    $isvalid = false;
                    $this->SetValidateError($key, "unique");
                }
            }
            if (!empty($value) &&  $type == "textbox" && !empty($validate) && ereg($validate,$value))
            {
                $isvalid = false;
                $this->SetValidateError($key, "textbox");
            }
            else if (!empty($value) && $type == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL))
            {
                $isvalid = false;
                $this->SetValidateError($key, "email");
            }
            else if (!empty($value) && $type =="number" && !filter_var($value, FILTER_VALIDATE_INT))
            {
                $isvalid = false;
                $this->SetValidateError($key, "number");
            }
        }
        return $isvalid;
    }
    public function GetValidateError()
    {
        return $this->DomainValidateErrors;
    }
    
    
    
    private function SetValidateError($item,$errorCode)
    {
        $ar = array();
        $ar["ItemName"] = $item;
        $ar["ErrorCode"] = $errorCode;
        $this->DomainValidateErrors[] = $ar;
    }
            
    
    
    public function  SaveDomainValue($DomainIdentifcator,$ObjectId,$data)
    {
        $domain =  \Model\UserDomains::GetInstance();
        $domainInfo = $domain->GetDomainInfo($DomainIdentifcator);

        $this->DomainId = $domainInfo["Id"];
        $this->ObjectId = $ObjectId;
        $this->DeleteByCondition("DomainId = ".$domainInfo["Id"]. " AND ObjectId = ".$ObjectId,true,false);
        
        foreach ($data as $row)
        {
            $this->Id = $row->ValueId;
            $this->ItemId = $row->ItemId;
            $this->Value = $row->Value;
            $this->SaveObject($this);
        }
    }
    
    public function SaveUserDomainData($data)
    {
        $objectId =  $data["Id"];
        $domainIdentificator = $data["DomainIdentificator"];
        $userDomain =  \Model\UserDomains::GetInstance();
        $udInfo = $userDomain->GetDomainInfo($domainIdentificator);
        $domainId = $udInfo["Id"];
        if(empty($objectId))
        {
            $objectId = $this->GenerateObjectId($domainId);
        }
        $userDomainItems = \Model\UserDomainsItems::GetInstance();
        $items = $userDomainItems->GetUserDomainItems($domainIdentificator);
        $items = ArrayUtils::ValueAsKey($items, "Identificator");
        unset($data["Id"]);
        unset($data["DomainIdentificator"]);
        unset($data["ModelName"]);
        foreach ($data as $key => $value)
        {
            $this->Id =  $this->SetId($domainId,$items[$key]["Id"],$objectId);
            $this->DomainId = $domainId;
           
            $this->ObjectId = $objectId;
            $this->ItemId = $items[$key]["Id"];
            $this->Value = $value;
            
            $this->SaveObject($this);   
        }
        return $objectId;
    }
    public function  Delete($id)
    {
        $this->DeleteByCondition("ObjectId =$id");
        
    }


    private function SetId($DomainId,$ItemId,$ObjectId)
    {
        if (empty($ObjectId)) return 0;
        $res = dibi::query("SELECT Id FROM UserDomainsValues WHERE DomainId = %i AND ItemId =%i AND ObjectId = %i",$DomainId,$ItemId,$ObjectId)->fetchAll();
        if (empty($res)) return 0;
        return $res[0]["Id"];
        
    }
    private function GenerateObjectId($domainId)
    {
        $res = dibi::query("SELECT DISTINCT ObjectId FROM UserDomainsValues WHERE DomainId = %i ORDER BY ObjectId DESC LIMIT 1",$domainId)->fetchAll();
        
        if (!empty($res))
        {
            $lastObjectId = $res[0]["ObjectId"];
            $lastObjectId = $lastObjectId+1;
            
            return $lastObjectId;
        }
        return 1;
    }
    
    public function GetDomainValue($DomainIdentifcator,$ObjectId)
    {
        $res = dibi::query("SELECT ValueId,DomainIdentificator,ItemId,ObjectId,Value,DomainId,ItemIdentificator FROM DOMAINVALUE WHERE DomainIdentificator = %s AND ObjectId =%i",$DomainIdentifcator,$ObjectId)->fetchAll();
        return $res;
    }
    
    public function GetDomainValueConditon($domainIdentificator,$objectId = 0,$conditionColumn="",$conditionValue="")
    {
        
        
        if ($objectId == 0)
        {
            if (!empty($conditionColumn) && !empty($conditionValue))
            {
                $res = dibi::query("SELECT ValueId,DomainIdentificator,ItemId,ObjectId,Value,DomainId,ItemIdentificator FROM DOMAINVALUE WHERE DomainIdentificator = %s  AND ItemIdentificator = %s AND Value =%s",$domainIdentificator,$conditionColumn,$conditionValue)->fetchAll();
                return $res;
            }
            else if (!empty($conditionColumn))
            {
                $res = dibi::query("SELECT ValueId,DomainIdentificator,ItemId,ObjectId,Value,DomainId,ItemIdentificator FROM DOMAINVALUE WHERE DomainIdentificator = %s  AND ItemIdentificator = %s",$domainIdentificator,$conditionColumn)->fetchAll();
                return $res;   
            }
            return $this->GetDomainValueList($domainIdentificator);
        }
        else 
        {
            if (!empty($conditionColumn) && !empty($conditionValue))
            {
                $res = dibi::query("SELECT ValueId,DomainIdentificator,ItemId,ObjectId,Value,DomainId,ItemIdentificator FROM DOMAINVALUE WHERE DomainIdentificator = %s  AND ItemIdentificator = %s AND Value =%s AND ObjectId =%i",$domainIdentificator,$conditionColumn,$conditionValue,$objectId)->fetchAll();
                return $res;
            }
            else if (!empty($conditionColumn))
            {
                $res = dibi::query("SELECT ValueId,DomainIdentificator,ItemId,ObjectId,Value,DomainId,ItemIdentificator FROM DOMAINVALUE WHERE DomainIdentificator = %s  AND ItemIdentificator = %s  AND ObjectId =%i",$domainIdentificator,$conditionColumn,$objectId)->fetchAll();
                return $res;
            }
            return $this->GetDomainValue($domainIdentificator,$objectId);
        }
        return array();
        
    }
    
    private function GetAllValuesByIdentificator($DomainIdentifcator)
    {
        $res = dibi::query("SELECT * FROM DOMAINVALUE WHERE DomainIdentificator = %s ",$DomainIdentifcator)->fetchAll();
        return $res;
    }
    
    public function GetDomainValueByDomainId($domainId,$objectId)
    {
        $res = dibi::query("SELECT DOMAINVALUE.*, UserDomainsItems.Identificator FROM DOMAINVALUE  
                 LEFT JOIN UserDomainsItems ON DOMAINVALUE.ItemId  = UserDomainsItems.Id 
                 WHERE DOMAINVALUE.DomainId = %i AND ObjectId =%i",$domainId,$objectId)->fetchAll();
        
        $outArray = array();
        foreach ($res as $row)
        {
            $outArray["Id"] = $row["ObjectId"];
            $identificator = $row["Identificator"];
            $value = $row["Value"];
            $outArray[$identificator] = $value;
        }
        return $outArray;
    }
    
    public function GetDomainValueList($domainId,$deleted = false)
    {
        $res =  null;
        if (!$deleted)
            $deleted= 0;
        else 
            $deleted= 1;
        
            $res = dibi::query("SELECT DOMAINVALUE.*,UserDomainsItems.Identificator FROM DOMAINVALUE 
                            LEFT JOIN UserDomainsItems ON DOMAINVALUE.ItemId  = UserDomainsItems.Id
                            WHERE DOMAINVALUE.DomainId =%i AND DOMAINVALUE.Deleted = %i  ORDER BY ObjectId DESC ",$domainId,$deleted)->fetchAll();
        
        $outArray = array();
        $lastObjectId = 0;
        $pos = -1;
        foreach ($res as $row)
        {
            $identificator = $row["Identificator"];
            if (empty($identificator))
                continue;
            $value = $row["Value"];
            if ($lastObjectId != $row["ObjectId"])
            {
                $pos++;    
            }
            
            $outArray[$pos][$identificator] = $value;
            $outArray[$pos]["ObjectId"] = $row["ObjectId"];
            $lastObjectId = $row["ObjectId"];
        }
        return $outArray;
    }
    
    public function DeleteAllValues($domainId)
    {
        $this->DeleteByCondition("DomainId = $domainId",true,false);
    }
    
    public function UserDomainItemsBySeoUrl($seoUrl, $usergroup, $langId, $webId, $itemIdentificator="",$preview = false)
    {
        if (!empty($itemIdentificator))
        {
            $itemIdentificator = " AND UserDomainsItems.Identificator = '$itemIdentificator' ";
        }
        $tableName =  !$preview ? "FRONTENDDETAIL" : "FRONTENDDETAILPREVIEW";
        $res = dibi::query("SELECT DISTINCT UserDomainsItems.*,$tableName.Data  FROM $tableName "
                . "LEFT JOIN Content ON $tableName.TemplateId = Content.Id "
                . "LEFT JOIN UserDomainsItems ON UserDomainsItems.DomainId = Content.DomainId AND UserDomainsItems.Deleted = 0 $itemIdentificator "
                
                    
                . "WHERE $tableName.SeoUrl = %s AND  $tableName.GroupId =%i AND $tableName.WebId = %i AND $tableName.LangId = %i AND  $tableName.AvailableOverSeoUrl = 1 ",$seoUrl, $usergroup, $webId, $langId)->fetchAll();
        return $res;
    }










    public function OnCreateTable() {
        $colDomainId = new DataTableColumn();
        $colDomainId->DefaultValue ="";
        $colDomainId->IsNull = false;
        $colDomainId->Length = 9;
        $colDomainId->Name ="DomainId";
        $colDomainId->Type = "INTEGER";
        $colDomainId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colDomainId);
        
        $colItemId = new DataTableColumn();
        $colItemId->DefaultValue ="";
        $colItemId->IsNull = false;
        $colItemId->Length = 9;
        $colItemId->Name ="ItemId";
        $colItemId->Type = "INTEGER";
        $colItemId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colItemId);
        
        $colObjectId = new DataTableColumn();
        $colObjectId->DefaultValue ="";
        $colObjectId->IsNull = false;
        $colObjectId->Length = 9;
        $colObjectId->Name ="ObjectId";
        $colObjectId->Type = "INTEGER";
        $colObjectId->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colObjectId);
        
        
        
        $colValue = new DataTableColumn();
        $colValue->DefaultValue ="";
        $colValue->IsNull = true;
        $colValue->Name ="Value";
        $colValue->Type = "text";
        $colValue->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colValue);
        
         
        
   
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
