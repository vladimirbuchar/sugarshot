<?php
namespace Model;
 use Types\RuleType;
use Types\DataTableColumn;

class WordGroups  extends DatabaseTable implements \Inteface\iDataTable{
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
            $this->AddColumn(new DataTableColumn("Word$wordIndetificator", \Types\DataColumnsTypes::TEXT, "", true));
            $this->SaveNewColums();
        }
    }
    
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("GroupName", \Types\DataColumnsTypes::VARCHAR, "", FALSE, 50));
        $this->AddColumn(new DataTableColumn("Word".DEFAULT_LANG, \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("WordEN", \Types\DataColumnsTypes::TEXT, "", true));
        $this->AddColumn(new DataTableColumn("WordRU", \Types\DataColumnsTypes::TEXT, "", true));
    }
    

    public function InsertDefaultData() {
        $this->Setup();  
        
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
