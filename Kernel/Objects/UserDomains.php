<?php

namespace Objects;
use Dibi;
use Utils\ArrayUtils;
use Utils\StringUtils;
class UserDomains extends ObjectManager{
    private $DomainValidateErrors = array();
    public function __construct() {
        parent::__construct();
    }
    public function GetDomainInfo($identificator)
    {
        $model = new \Model\UserDomains();
        return $model->GetFirstRow($model->SelectByCondition(" DomainIdentificator = '$identificator'"));
    }
    
    //UserDomainAddiction
    public function  SaveAddiction($id,$domainId,$name,$item1,$ruleName,$item1Value,$actionName,$itemXValue,$itemX,$priority)
    {
        $model = new \Model\UserDomainsAddiction();
        $model->Id = $id;
        $model->DomainId = $domainId;
        $model->AddictionName = $name;
        $model->Item1 = $item1;
        $model->RuleName = $ruleName;
        $model->Item1Value = $item1Value;
        $model->ActionName = $actionName;
        $model->ItemXValue = $itemXValue;    
        $model->ItemX = $itemX;
        $model->Priority = $priority;
        
        if (StringUtils::ContainsString($item1, "-"))
        {
            $ar = explode("-", $item1);
            $model->IsDomain1 = true;
            $model->DomainId1 = $ar[0];
            $model->ItemId1 = $ar[1];
        }
        if (StringUtils::ContainsString($itemX, "-"))
        {
            $ar = explode("-", $itemX);
            $model->IsDomainX = true;
            $model->DomainIdX = $ar[0];
            $model->ItemIdX = $ar[1];
        }
        $model->SaveObject();
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
    
    /// UserDomainsAutoComplete
    public function GetItemAutoComplected($itemId)
    {
        $model = new Model\UserDomainsAutoComplete();
        return $model->SelectByCondition("DomainItemId = $itemId AND Deleted = 0");
    }
    
    // userdomains  groups
    
    public function SaveGroup($id,$name,$domainId)
    {
        $model = new \Model\UserDomainsGroups();
        $model->Id = $id;
        $model->GroupName = $name;
        $model->DomainId = $domainId;
        return $model->SaveObject();
    }
    
    //user domain items
    public function GetUserDomainItems($identifcator,$groupId = 0,$elementId = "")
    {       

        if ($groupId == 0)
        {
            if ($elementId == "")
            {
                $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s  ",$identifcator)->fetchAll();
                return $res;
            }
            else 
                
            {
                $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s  AND Identificator =%s ",$identifcator,$elementId)->fetchAll();
                return $res;
            }
            
        }
        else 
        {
            if($elementId =="")
            {
                $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s  AND GroupId = %i ",$identifcator,$groupId)->fetchAll();
                return $res;
            }
            else 
            {
                $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s  AND GroupId = %i AND Identificator =%s ",$identifcator,$groupId,$elementId)->fetchAll();
                return $res;
            }
        }
    }
    public function GetUserDomainItemsOnlyMn($identifcator) 
    {
        $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s  AND (DomainSettings = 'mn' OR DomainSettings ='1n')",$identifcator)->fetchAll();
        return $res;
    }
    
    public function  GetUserDomainItemById($id,$mode = "")
    {
        
        if ($mode == "")
        {
            
            $res = dibi::query("SELECT DISTINCT  * FROM ITEMSINDOMAIN WHERE DomainId = %i  ",$id)->fetchAll();
            return $res;
        }
        else if ($mode =="filtr")
        {
            $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainId = %i  AND FiltrSettings <> 'HideInFiltr' AND FiltrSettings<> ''  ",$id)->fetchAll();
            return $res;
        }
        else if ($mode =="sort")
        {
            $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainId = %i AND AddToSort = 1  ",$id)->fetchAll();
            return $res;
        }
    }
    
    public function  GetUserDomainItemByIdentificator($identificator,$mode = "")
    {
        if ($mode == "")
        {
            $res = dibi::query("SELECT DISTINCT  * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s  ",$identificator)->fetchAll();
            return $res;
        }
        else if ($mode =="filtr")
        {
            $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s  AND FiltrSettings <> 'HideInFiltr' AND FiltrSettings<> ''  ",$identificator)->fetchAll();
            return $res;
        }
        else if ($mode =="sort")
        {
            $res = dibi::query("SELECT DISTINCT * FROM ITEMSINDOMAIN WHERE DomainIdentificator = %s AND AddToSort = 1  ",$identificator)->fetchAll();
            return $res;
        }
    }
    
    public function  GetUserDomainByTemplateId($id,$identificator = true)
    {
        $res = dibi::query("SELECT UserDomains.* FROM `Content`  JOIN UserDomains ON Content.DomainId = UserDomains.Id WHERE Content.Id = %i",$id)->fetchAll();
        if(!empty($res))
        {
            if ($identificator)
                return $res[0]["DomainIdentificator"];
            return $res[0]["Id"];
        }
        return "";
    }
    
    public function  GetUserDomainByTemplateIdentificator($id,$identificator = true)
    {
        $res = dibi::query("SELECT UserDomains.* FROM `Content`  JOIN UserDomains ON Content.DomainId = UserDomains.Id WHERE Content.Identificator = %s",$id)->fetchAll();
        if(!empty($res))
        {
            if ($identificator)
                return $res[0]["DomainIdentificator"];
            return $res[0]["Id"];
        }
        return "";
    }
    
    //UserDomainsItemsInGroups
    
    public function SaveItemInGroup($groupId,$items)
    {
        $model = new Model\UserDomainsGroups();
        $model ->DeleteByCondition("GroupId = $groupId");
        
        for($i = 0;$i< count($items);$i++)
        {
            $model->GroupId = $groupId;
            $model->ItemId = $items[$i][0];
            $model->SaveObject();
        }
        
    }
    public function GetUserItemInGroups($groupId)
    {
        $model = new Model\UserDomainsGroups();
        return $model->SelectByCondition("GroupId = $groupId AND Deleted = 0");
    }
    
    /// userdomain values
    public function GetFiles($domainIdentificator,$data)
    {
        $filesList = array();
        
        $domainData = $this->GetUserDomainItems($domainIdentificator);
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
        $domainData = array();
        if ($groupId == 0)
            $domainData = $this->GetUserDomainItems($domainIdentificator);
        else 
            $domainData = $this->GetUserDomainItems($domainIdentificator,$groupId);
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
        $model = \Model\UserDomainsValues::GetInstance();
        $domainInfo = $this->GetDomainInfo($DomainIdentifcator);
        $model->DomainId = $domainInfo["Id"];
        $model->ObjectId = $ObjectId;
        $model->DeleteByCondition("DomainId = ".$domainInfo["Id"]. " AND ObjectId = ".$ObjectId,true,false);
        
        foreach ($data as $row)
        {
            $model->Id = $row->ValueId;
            $model->ItemId = $row->ItemId;
            $model->Value = $row->Value;
            $model->SaveObject();
        }
    }
    
    public function SaveUserDomainData($data)
    {
        $model = new \Model\UserDomainsValues();
        $objectId =  $data["Id"];
        $domainIdentificator = $data["DomainIdentificator"];
        $udInfo = $this->GetDomainInfo($domainIdentificator);
        $domainId = $udInfo["Id"];
        if(empty($objectId))
        {
            $objectId = $this->GenerateObjectId($domainId);
        }
        
        $items = $this->GetUserDomainItems($domainIdentificator);
        $items = ArrayUtils::ValueAsKey($items, "Identificator");
        unset($data["Id"]);
        unset($data["DomainIdentificator"]);
        unset($data["ModelName"]);
        foreach ($data as $key => $value)
        {
            if (empty($items[$key]["Id"])) continue;
            $model->Id =  $this->SetId($domainId,$items[$key]["Id"],$objectId);
            $model->DomainId = $domainId;
            $model->ObjectId = $objectId;
            $model->ItemId = $items[$key]["Id"];
            $model->Value = $value;
            $model->SaveObject();   
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
        $model = new Model\UserDomainsValues();
        $model->DeleteByCondition("DomainId = $domainId",true,false);
    }
    
    
    public function UserDomainItemsBySeoUrl($seoUrl, $usergroup, $langId, $webId, $itemIdentificator="",$preview = false)
    {
        if (!empty($itemIdentificator))
        {
            $itemIdentificator = " AND UserDomainsItems.Identificator = '$itemIdentificator' ";
        }
        $tableName =  !$preview ? "FrontendDetail_materialized" : "FRONTENDDETAILPREVIEW";
        $res = dibi::query("SELECT DISTINCT UserDomainsItems.*,$tableName.Data  FROM $tableName "
                . "LEFT JOIN Content ON $tableName.TemplateId = Content.Id "
                . "LEFT JOIN UserDomainsItems ON UserDomainsItems.DomainId = Content.DomainId AND UserDomainsItems.Deleted = 0 $itemIdentificator "
                . "WHERE $tableName.SeoUrl = %s AND  $tableName.GroupId =%i AND $tableName.WebId = %i AND $tableName.LangId = %i AND  $tableName.AvailableOverSeoUrl = 1 ",$seoUrl, $usergroup, $webId, $langId)->fetchAll();
        return $res;
    }
    public function UserDomainItemByObjectId($objectId, $usergroup, $langId, $webId, $itemIdentificator="",$preview = false)
{
        if (!empty($itemIdentificator))
        {
            $itemIdentificator = " AND UserDomainsItems.Identificator = '$itemIdentificator' ";
        }
        $tableName =  !$preview ? "FrontendDetail_materialized" : "FRONTENDDETAILPREVIEW";
        $res = dibi::query("SELECT DISTINCT UserDomainsItems.*,$tableName.Data  FROM $tableName "
                . "LEFT JOIN Content ON $tableName.TemplateId = Content.Id "
                . "LEFT JOIN UserDomainsItems ON UserDomainsItems.DomainId = Content.DomainId AND UserDomainsItems.Deleted = 0 $itemIdentificator "
                . "WHERE $tableName.Id = %i AND  $tableName.GroupId =%i AND $tableName.WebId = %i AND $tableName.LangId = %i AND  $tableName.AvailableOverSeoUrl = 1 ",$objectId, $usergroup, $webId, $langId)->fetchAll();
        return $res;
    }
// userdomains
    
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
    
    
    
    
}
