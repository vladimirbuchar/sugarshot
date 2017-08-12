<?php

namespace Objects;
use Utils\ArrayUtils;

class Content extends ObjectManager {
    public function __construct() {
        parent::__construct();
    }
    
    public function SaveAlternativeItem($objectId,$userGroupId,$alternativeItemId)
    {
        /**
         * @var \Model\ContentAlternative
         */
        $model = \Model\ContentAlternative::GetInstance();
        $model->DeleteByCondition("ContentId = $objectId AND UserGroupId = $userGroupId", true, false);
        $model->ContentId = $objectId;
        $model->UserGroupId = $userGroupId;
        $model->AlternativeContentId = $alternativeItemId;
        $model->SaveObject();
    }
    
    public function CreateConnection($ObjectId, $ObjectIdConnected,$ConnectedType,$Data)
    {
        /**
         * @var \Model\ContentConnection
         */
        $model = \Model\ContentConnection:: GetInstance();
        $model->TransactionBegin();
        $model->TransactionEnd();
        
        try{
        $contentVersion =  \Model\ContentVersion::GetInstance();
        if (!$contentVersion->HasPrivileges($ObjectId, PrivilegesType::$CanWrite))
            return;
        
        
        if ($ConnectedType =="gallery")
        {
            $content = \Model\Content::GetInstance();
            $content->GetObjectById($ObjectId,true);
            if ($content->GallerySettings == 0)
            {
                $content->GalleryId = 0;
                $content->SaveObject();
            }
                
            if ($content->GallerySettings == 1)
            {
                $content->GalleryId = 0;
                $content->SaveObject();
            }
            if ($content->GallerySettings == 2)
            {
                $dataCon = $model->SelectByCondition("ObjectId = $ObjectIdConnected AND ConnectedType='gallery'");
                
                if (!empty($dataCon))
                {
                    foreach ($dataCon as $row)
                    {
                        $model->ObjectId = $ObjectId;
                        $model->ObjectIdConnected = $row["ObjectIdConnected"];
                        $model->ConnectedType = $row["ConnectedType"];
                        $model->SettingConnection = $row["SettingConnection"];
                        $model->SaveObject();
                    }
                    $content->GallerySettings = 1;
                    $content->SaveObject();
                }
                else 
                {
                    $nContent = \Model\Content::GetInstance();
                    $nContent->GetObjectById($ObjectIdConnected,true);
                    $this->CreateConnection($ObjectId, $nContent->GalleryId, $ConnectedType, $Data);
                }
                return;
            }
            if ($content->GallerySettings == 3)
            {
                $content->GalleryId = $ObjectIdConnected;
                $content->SaveObject();
                return;
            }
        }
        if (empty($ObjectIdConnected)) $ObjectIdConnected =0;
        $model->ObjectId = $ObjectId;
        $model->ObjectIdConnected = $ObjectIdConnected;
        $model->ConnectedType = $ConnectedType;
        if ($ConnectedType == "link")
            $Data = $this->CreateUrl ($Data);
        $model->SettingConnection = $Data;
        $model->SaveObject();
        $model->TransactionEnd();
        }
        catch (Exception $e)
        {
            dibi::rollback();
        }
    }
    
    private function CreateUrl($Data)
    {
        $xml = "";
        $xml .= "<link>";
        $xml .= "<linkName>";
        $xml .= $Data[0];
        $xml .= "</linkName>";
        $xml .= "<linkUrl>";
        $xml .= $Data[1];
        $xml .= "</linkUrl>";
        $xml .= "</link>";
        return $xml;
    }
    
     public function GetRelatedObject($objectId,$langId,$type="",$testPrivileges = FALSE)
    {
        $res = array();
        $queryType = "";
        if (!empty($type))
        {
            $arType = explode(",", $type);
            for ($i = 0;$i<count($arType);$i++)
            {
                $arType[$i] = " ConnectedType = '".$arType[$i]."' ";
            }
            $queryType = implode(" OR ", $arType);            
        }
        if (!$testPrivileges)
        {
            /**
             * @var \Model\ConnectionObjects
             */
            $connectionobjects = \Model\ConnectionObjects::GetInstance();
            if (empty($type))
            {
                $res = $connectionobjects->SelectByCondition("ObjectId = %i AND  (LangId = %i OR LangId =0 )","",array("Name","Data","SeoUrl","ConnectedType","SettingConnection"),array($objectId,$langId));
            }
            else 
            {
                $res = dibi::query("SELECT Name,Data,SeoUrl,ConnectedType,SettingConnection FROM CONNECTIONOBJECTS WHERE ObjectId = %i AND (LangId = %i OR LangId =0 ) AND ($queryType)",$objectId,$langId)->fetchAll();
            }
                
        }
        
        
        foreach ($res as $row)
        {
            if ($row["ConnectedType"] == "link")
            {
                $ar = ArrayUtils::XmlToArray($row["SettingConnection"]);
                $row["Name"] = $ar["linkName"];
            }   
        }
        return $res;
        
    }
    
    
}
