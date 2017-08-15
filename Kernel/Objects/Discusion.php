<?php

namespace Objects;
class Discusion extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    public function  AddNewDiscusionItem($subject,$text,$showUserName,$parent,$discusionId,$historyId)
    {
        if(empty($historyId)) $historyId = 0;
        $user = new \Objects\Users();
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
        $domainsValues = new \Objects\UserDomains();
        $userDomain = new \Objects\UserDomains();
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
}
