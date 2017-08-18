<?php
namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;
use Types\AlterTableMode;

class WordGroups  extends DatabaseTable{
    public $GroupName;
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "WordGroups";
    }
    
    public function AddColumnLang($wordIndetificator)
    {
        if (!empty($wordIndetificator))
        {
            $colWordEN = new DataTableColumn();
            $colWordEN ->DefaultValue ="";
            $colWordEN ->IsNull = true;
            $colWordEN ->Name ="Word$wordIndetificator";
            $colWordEN ->Type = "TEXT";
            $colWordEN ->Mode = AlterTableMode::$AddColumn;
            $this->AddColumn($colWordEN);
            $this->SaveNewColums();
        }
    }
    
    public function OnCreateTable() {
        $colGroupName = new DataTableColumn();
        $colGroupName->DefaultValue ="";
        $colGroupName->IsNull = false;
        $colGroupName->Length = 50;
        $colGroupName->Name ="GroupName";
        $colGroupName->Type = "varchar";
        $colGroupName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colGroupName);
        
        $colWordDefault = new DataTableColumn();
        $colWordDefault ->DefaultValue ="";
        $colWordDefault ->IsNull = true;
        $colWordDefault ->Name ="Word".DEFAULT_LANG;
        $colWordDefault ->Type = "TEXT";
        $colWordDefault ->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWordDefault);
        
        $colWordDefault = new DataTableColumn();
        $colWordDefault ->DefaultValue ="";
        $colWordDefault ->IsNull = true;
        $colWordDefault ->Name ="WordEN";
        $colWordDefault ->Type = "TEXT";
        $colWordDefault ->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWordDefault);
        
        $colWordDefault = new DataTableColumn();
        $colWordDefault ->DefaultValue ="";
        $colWordDefault ->IsNull = true;
        $colWordDefault ->Name ="WordRU";
        $colWordDefault ->Type = "TEXT";
        $colWordDefault ->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colWordDefault);
            
        
        
        
        
        
    }
    

    public function InsertDefaultData() {
        $admL = AdminLangs::GetInstance();
        $admL->Setup($admL);
        $this->Setup($this);  
        
    }

    
    public function SetValidate($mode = false) {
        $this->SetValidateRule("GroupName", RuleType::$NoEmpty,$this->GetWord("word90"));
        $this->SetValidateRule("GroupName", RuleType::$Unique,$this->GetWord("word91"));
    }
    public function TableMigrate()
    {
       
    }
    
    public function TableExportSettings()
    {
        $this->SetExportSettings("Id", "Id");
        $this->SetExportSettings("GroupName", $this->GetWord("word92"));
        $adminLang = AdminLangs::GetInstance();
        $adminLangData= $adminLang->Select(array(),FALSE,true, false);
        
        foreach ($adminLangData as $row)
        {
            $this->SetExportSettings("Word".$row->LangIdentificator, $row->LangName);
        }
    }

}
