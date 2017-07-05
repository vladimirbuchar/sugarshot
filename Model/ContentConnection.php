<?php
namespace Model;
use Utils\ArrayUtils;
use Dibi;
use Types\PrivilegesType;
use Types\DataTableColumn;
use Types\AlterTableMode;
class ContentConnection  extends DatabaseTable{
    
    public $ObjectId;
    public $ObjectIdConnected;
    public $ConnectedType;
    public $SettingConnection;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->SaveHistory = FALSE;
        $this->ObjectName = "ContentConnection";
        $this->MultiLang = false;
        $this->MultiWeb= true;
        $this->SetSelectColums(array("ObjectId","ObjectIdConnected","ConnectedType","SettingConnection"));
        $this->SetDefaultSelectColumns();
       
    }
    /*public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/
    
    public function CreateConnection($ObjectId, $ObjectIdConnected,$ConnectedType,$Data)
    {
        dibi::begin();
        try{
        $contentVersion =  ContentVersion::GetInstance();
        if (!$contentVersion->HasPrivileges($ObjectId, PrivilegesType::$CanWrite))
            return;
        
        
        if ($ConnectedType =="gallery")
        {
            $content = Content::GetInstance();
            $content->GetObjectById($ObjectId,true);
            if ($content->GallerySettings == 0)
            {
                $content->GalleryId = 0;
                $content->SaveObject($content);
            }
                
            if ($content->GallerySettings == 1)
            {
                $content->GalleryId = 0;
                $content->SaveObject($content);
            }
            if ($content->GallerySettings == 2)
            {
                $dataCon = $this->SelectByCondition("ObjectId = $ObjectIdConnected AND ConnectedType='gallery'");
                
                if (!empty($dataCon))
                {
                    foreach ($dataCon as $row)
                    {
                        $this->ObjectId = $ObjectId;
                        $this->ObjectIdConnected = $row["ObjectIdConnected"];
                        $this->ConnectedType = $row["ConnectedType"];
                        $this->SettingConnection = $row["SettingConnection"];
                        $this->SaveObject($this);
                    }
                    $content->GallerySettings = 1;
                    $content->SaveObject($content);
                }
                else 
                {
                    $nContent = Content::GetInstance();
                    $nContent->GetObjectById($ObjectIdConnected,true);
                    $this->CreateConnection($ObjectId, $nContent->GalleryId, $ConnectedType, $Data);
                }
                return;
            }
            if ($content->GallerySettings == 3)
            {
                $content->GalleryId = $ObjectIdConnected;
                $content->SaveObject($content);
                return;
            }
        }
        if (empty($ObjectIdConnected)) $ObjectIdConnected =0;
        $this->ObjectId = $ObjectId;
        $this->ObjectIdConnected = $ObjectIdConnected;
        $this->ConnectedType = $ConnectedType;
        if ($ConnectedType == "link")
            $Data = $this->CreateUrl ($Data);
        $this->SettingConnection = $Data;
        $this->SaveObject($this);
        dibi::commit();
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
            if (empty($type))
            {
                $res = dibi::query("SELECT Name,Data,SeoUrl,ConnectedType,SettingConnection FROM CONNECTIONOBJECTS WHERE ObjectId = %i AND  (LangId = %i OR LangId =0 ) ",$objectId,$langId)->fetchAll();
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
    
    
    
    public function DisconnectObjects($ObjectId, $ConnectedType)
    {
        $contentVersion = ContentVersion::GetInstance();
        if (!$contentVersion->HasPrivileges($ObjectId, PrivilegesType::$CanWrite))
            return;
        $this->DeleteByCondition("ObjectId = ".$ObjectId." AND ConnectedType = '$ConnectedType'",true,false);
    }
    
    public function OnCreateTable() {
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name ="ObjectId";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = false;
        $colContentType->Length = 9;
        $colContentType->Name ="ObjectIdConnected";
        $colContentType->Type = "INTEGER";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = false;
        $colContentType->Length = 100;
        $colContentType->Name ="ConnectedType";
        $colContentType->Type = "VARCHAR";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        $colContentType = new DataTableColumn();
        $colContentType->DefaultValue =0;
        $colContentType->IsNull = true;
        $colContentType->Name ="SettingConnection";
        $colContentType->Type = "TEXT";
        $colContentType->DefaultValue = "";
        $colContentType->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colContentType);
        
        
        

    }

    
    public function InsertDefaultData() {
        
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