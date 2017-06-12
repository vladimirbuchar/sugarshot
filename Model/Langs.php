<?php
namespace Model;
use Utils\StringUtils;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
use Types\RuleType;
use Types\DatabaseActions;
class Langs  extends DatabaseTable{
    public $LangName;
    public $RootUrl;
    public $Title;
    public $Keywords;
    public $Description;
    public $CategoryPage;
    public $LangIdentificator;
//    private static $_instance = null;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Langs";
        $this->MultiWeb = true;
        $this->SaveHistory = true;
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
    
    public function GetLangListByWeb($webid)
    {
        return $this->SelectByCondition("WebId = ".$webid." AND Deleted = 0");
    }
    
    public function BlockAdmin($web)
    {
        $res = $this->SelectByCondition("RootUrl ='".$web."'");
        if (!empty($res))
        {
            $webid= $res[0]["WebId"];
            $web =  Webs::GetInstance();
            $web->GetObjectById($webid,true);
            return $web->BlockAdmin;
        }
        return false;
    }
    
    public function GetRootUrl($langId)
    {
        $this->GetObjectById($langId,true);
        return StringUtils::EndWith($this->RootUrl,"/") ? $this->RootUrl : $this->RootUrl."/";
    }
    
    public function GetWebInfo($web)
    {
        
        if (self::$SessionManager->IsEmpty("WebInfo",$web))
        {
            $url[] = " RootUrl = '".SERVER_PROTOCOL.$web."'";
            $url[] = " RootUrl = '".SERVER_PROTOCOL."www.$web'";
            $url[] = " RootUrl = 'www.$web'";
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString($web,"www."))."'";  
            $url[] = " RootUrl = '".StringUtils::RemoveLastChar($web)."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString(StringUtils::RemoveLastChar($web),"www."))."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString($web,SERVER_PROTOCOL."www."))."'";  
            $url[] = " RootUrl = '".StringUtils::RemoveLastChar($web)."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString(StringUtils::RemoveLastChar($web),SERVER_PROTOCOL."www."))."'";  
            $url[] = " RootUrl = '". trim(StringUtils::RemoveString($web,SERVER_PROTOCOL))."'";  
            $url[] = " RootUrl = '".StringUtils::RemoveLastChar($web)."'";  
            $url[] = " RootUrl = '".$web."'";  
            $url[] = " RootUrl = '". StringUtils::RemoveString( $web,SERVER_PROTOCOL) ."'";  
            $url[] = " RootUrl = '".trim(StringUtils::RemoveString(StringUtils::RemoveLastChar($web),SERVER_PROTOCOL))."'";  
            
            $where = implode(" OR ", $url);
            $res = $this->SelectByCondition($where);
            self::$SessionManager->SetSessionValue("WebInfo",$res,$web);
            
            return $res;
        }
        return self::$SessionManager->GetSessionValue("WebInfo",$web);
        
    }
    
    public function OnCreateTable() {
        $colLangName = new DataTableColumn();
        $colLangName->DefaultValue ="";
        $colLangName->IsNull = false;
        $colLangName->Length = 50;
        $colLangName->Name ="LangName";
        $colLangName->Type = "varchar";
        $colLangName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colLangName);
        
        $colRootUrl = new DataTableColumn();
        $colRootUrl->DefaultValue ="";
        $colRootUrl->IsNull = false;
        $colRootUrl->Length = 50;
        $colRootUrl->Name ="RootUrl";
        $colRootUrl->Type = "varchar";
        $colRootUrl->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRootUrl);
        
        $colRootUrl = new DataTableColumn();
        $colRootUrl->DefaultValue ="";
        $colRootUrl->Length = 255;
        $colRootUrl->Name ="Title";
        $colRootUrl->Type = "text";
        $colRootUrl->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRootUrl);
        
        $colRootUrl = new DataTableColumn();
        $colRootUrl->DefaultValue ="";
        $colRootUrl->Name ="Keywords";
        $colRootUrl->Type = "text";
        $colRootUrl->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRootUrl);
        $colRootUrl = new DataTableColumn();
        $colRootUrl->DefaultValue ="";
        $colRootUrl->Name ="Description";
        $colRootUrl->Type = "text";
        $colRootUrl->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRootUrl);
        $colRootUrl = new DataTableColumn();
        $colRootUrl->DefaultValue ="";
        $colRootUrl->Name ="CategoryPage";
        $colRootUrl->Type = "text";
        $colRootUrl->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRootUrl);
        
        $deletedColumn = new DataTableColumn();
        $deletedColumn->DefaultValue = 0;
        $deletedColumn->Name = "IsSystem";
        $deletedColumn->Type = "BOOLEAN";
        $deletedColumn->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($deletedColumn);
        
        $colRootUrl = new DataTableColumn();
        $colRootUrl->DefaultValue ="";
        $colRootUrl->Length = 255;
        $colRootUrl->Name ="LangIdentificator";
        $colRootUrl->Type = "varchar";
        $colRootUrl->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colRootUrl);
    }
    
    public function CreateLangFolder($id)
    {
        $content = ContentVersion::GetInstance();
        $obj = $this->GetObjectById($id);
        
        $folderId = $content->GetIdByIdentificator("langfolder",$_GET["webid"]);
        
        $name = $obj->LangName;
        if ($folderId == 0)
        {
            $content->CreateContentItem($name, true,  "langfolder$id", "", "langfolder",false,$id,0,true,"langfolder",array(),"", 0, 0, "", "", "", 0,  0,  0,  99999, 0,false);
        }
        else 
        {
            $user = Users::GetInstance();
            $content->CreateVersion($folderId, $name, true, $user->GetUserId(), "langfolder$id", 0, false, $id, "", "", "", "", false, "");
        }
    }
    

    public function InsertDefaultData() {
        $this->Setup($this);
    }
    
    public function TableMigrate()
    {
        
        dibi::query("ALTER TABLE  `Langs` CHANGE  `Title`  `Title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE  `Keywords`  `Keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE  `Description`  `Description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
            CHANGE  `CategoryPage`  `CategoryPage` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;");
        
    }

    

    public function SetValidate($mode = false) {
        $this->SetValidateRule("LangName", RuleType::$NoEmpty,$this->GetWord("word85"));
        $this->SetValidateRule("RootUrl", RuleType::$NoEmpty,  $this->GetWord("word86"));
        $this->SetValidateRule("RootUrl", RuleType::$Unique,$this->GetWord("word87"));
        $this->SetValidateRule("LangIdentificator", RuleType::$Unique,$this->GetWord("word87"));
        $this->SetCallModelFunction("Langs","CreateLangFolder","",DatabaseActions::$Insert);   
    }
    public function TableExportSettings()
    {
        
    }
}
