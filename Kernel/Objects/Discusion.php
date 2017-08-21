<?php

namespace Objects;
class Discusion extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    public function  AddNewDiscusionItem($subject,$text,$showUserName,$parent,$discusionId,$historyId)
    {
        $model = \Model\DiscusionItems::GetInstance();
        if(empty($historyId)) $historyId = 0;
        $user = new \Objects\Users();
        if ($user->UserHasBlockDiscusion())
            return;
        \dibi::query("UPDATE DiscusionItems SET IsLast = 0 WHERE VersionId = %i",$historyId);
        $model->SubjectDiscusion = $subject;
        $model->TextDiscusion = $text;
        $model->ShowUserName = $showUserName;
        $model->IsLast = true;
        $model->ParentIdDiscusion = $parent;
        $model->DiscusionId = $discusionId;
        $model->VersionId = $historyId;
        $badWords = $this->CheckBadWords();
        if ($badWords)
        {
            $id = $model->SaveObject();    
            if ($historyId == 0)
            {
                $model->GetObjectById($id,true);
                $model->VersionId = $id;
                $model->SaveObject();
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
        $res = \dibi::query("SELECT * FROM DISCUSIONITEMSLIST WHERE DiscusionId = %i  ORDER BY Id DESC $limit",$id)->fetchAll();
        return $res;
    }
    
     public function GetHistoryItemDetail($id)
    {
         $model = \Model\DiscusionItems::GetInstance();
        $out =  $model->SelectByCondition("VersionId = $id AND IsLast = 0");
        foreach ($out as $row)
        {
            $row["DateTime"] = date("m-d-Y H:m:s",$row["DateTime"]);
        }
        return $out;
    }
    
   
    
    private function CheckBadWords()
    {
        $domainsValues = new \Objects\UserDomains();
        $domainInfo = $domainsValues->GetDomainInfo("BadWords");
        $badWords = $domainsValues->GetDomainValueList($domainInfo["Id"],false);
        foreach ($badWords as $row)
        {
            $badWord = $row["BadWord"];
            if (strpos($this->SubjectDiscusion,$badWord) !== FALSE ||  strpos($this->TextDiscusion,$badWord) !== FALSE)
                    return false;
        }
        return true;    
    }
}
