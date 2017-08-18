<?php

namespace Objects;
use \Types\DatabaseActions;
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
            $res = $res[0];
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
        $model = new \Objects\ObjectHistory();
        $model->ObjectHistoryName = $objectName;
        $model->ObjectId = $objectId;
        $model->Action = $action;
        $model->UserId = $userId;
        $model->IP = $IP;
        $model->OldData = $oldData;
        $model->CreateDate = new \DateTime();
        $model->ActiveItem = $activeItem;
        $model->UserName = $userName;
        $model->HistoryWebId = $historyWebId;
        $model->SaveObject();

    }
}
