<?php
namespace Model;
use Kernel\GlobalClass;
class ExportSetting extends GlobalClass{
    private $_function = "";
    private $_folderName ="";
    public function __construct($function,$folderName)
    {
        $this->_function = $function;
        $this->_folderName = $folderName;
    }
    
    public function CallExport()
    {
        if (method_exists($this, $this->_function))
        {
            $f = $this->_function;
            return $this->$f();
        }
    }
    
    
    private function ExportWords()
    {
        return $this->GetExportByModel("WordGroups");
    }
    private function ExportAdminLang()
    {
        return $this->GetExportByModel("AdminLangs");
    }
    private function ExportWebs()
    {
         $this->GetExportByModel("Webs");
         $this->GetExportByModel("UserGroupsWeb");
    }
    
    private function  ExportLangs()
    {
        return $this->GetExportByModel("Langs");
    }
    private function ExportUserGroups()
    {
        return $this->GetExportByModel("UserGroups");
    }
    private function ExportUser()
    {
        $this->GetExportByModel("Users");
        $this->GetExportByModel("UsersInGroup");
    }
    
    private function ExportModules()
    {
        $this->GetExportByModel("Modules");    
        $this->GetExportByModel("UserGroupsModules");
    }
    
    private function ExportUserDomains()
    {
        $this->GetExportByModel("UserDomains");
        $this->GetExportByModel("UserDomainsItems");
        $this->GetExportByModel("UserDomainsValues");   
        $this->GetExportByModel("UserDomainsAutoComplete");   
        $this->GetExportByModel("UserDomainsGroups");   
        $this->GetExportByModel("UserDomainsItemsInGroups");   
        $this->GetExportByModel("UserDomainsItemsInGroups");   
    }
    
    private function ExportContent()
    {
        $this->GetExportByModel("Content");
        $this->GetExportByModel("ContentAlternative");
        $this->GetExportByModel("ContentConnection");
        $this->GetExportByModel("ContentData");
        $this->GetExportByModel("ContentSecurity");
        $this->GetExportByModel("ContentVersion");
        $this->GetExportByModel("DiscusionItems");
        $zipFolder = TEMP_EXPORT_PATH.$this->_folderName."/";
        \Utils\Folders::CreateFolder($zipFolder, "res");
        \Utils\Folders::CopyFolder(RES_PATH, $zipFolder."res");
        
    }
    
    private function GetExportByModel($modelName)
    {
        $modelNameTemp = "Model\\".$modelName;
        $model = new $modelNameTemp();
        $file =  $model->Export("xmlcdata");
        $newName = TEMP_EXPORT_PATH.$this->_folderName."/".$modelName.".xml";
        rename($this->ServerMap($file), $newName);
        return $newName;
    }
    
        
    
    
    
    
    
}
