<?php

namespace Objects;
class ObjectHistory extends ObjectManager{
    public function __construct() {
        parent::__construct();
    }
    public function GetHistoryObject($objectName,$objectId)
    {
        return dibi::query("SELECT * FROM  ObjectHistory WHERE ObjectName = %s AND ObjectId = %i  AND ActiveItem = 1 ORDER BY CreateDate DESC ",$objectName,$objectId)->fetchAll();
    }
    public function RecoveryItemFromHistory($idHistory)
    {
        $res = dibi::query("SELECT * FROM  ObjectHistory WHERE Id = %i  ",$idHistory)->fetchAll();
        if (!empty($res))
        {
            $res = $this->GetFirstRow($res);
            if ($res->Action == DatabaseActions::$Update)
            {
                $objName =  $res->ObjectHistoryName;
                $xml = $res->OldData;
                $obj = new $objName;
                $this->InsertFromXml($obj,$xml);
            }
        }
    }
    
    public function DeactiveHistoryItem($objectName,$id =0)
    {
        if ($id == 0)
            dibi::query("UPDATE ObjectHistory SET ActiveItem = 0 WHERE ObjectHistoryName = %s",$objectName);
        else if($id >0)
        dibi::query("UPDATE ObjectHistory SET ActiveItem = 0 WHERE ObjectHistoryName = %s AND ObjectId = %i" ,$objectName,$id);
    }
    public function CreateHistoryItem($objectName,$objectId,$action,$userId,$IP,$oldData,$activeItem,$userName,$historyWebId)
    {
        $this->ObjectHistoryName = $objectName;
        $this->ObjectId = $objectId;
        $this->Action = $action;
        $this->UserId = $userId;
        $this->IP = $IP;
        $this->OldData = $oldData;
        $this->CreateDate = new \DateTime();
        $this->ActiveItem = $activeItem;
        $this->UserName = $userName;
        $this->HistoryWebId = $historyWebId;
        $this->SaveObject();

    }
}
