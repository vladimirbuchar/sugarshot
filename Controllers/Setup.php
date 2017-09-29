<?php

namespace Controller;

class Setup extends PageController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetViewSettings("Setup", array("*"));
    }

    public function Setup() {
        
        $this->SetTemplateData("NormalPage", true);
        $this->SetTemplateData("pageHeader", "");
        $this->SetTemplateData("pageHtml", "");
        $pageHtml = "";
        $this->SetStateTitle($this->GetWord("word887"));
        
        if (!\Utils\Folders::FolderExists(LOG_PATH)) {
            \Utils\Folders::CreateFolder(ROOT_PATH, "Log");
        }
        
        if (!\Utils\Folders::FolderExists(TEMP_PATH)) {
            \Utils\Folders::CreateFolder(ROOT_PATH, "Temp");
        }

        if (!\Utils\Folders::FolderExists(TEMP_EXPORT_PATH)) {
            \Utils\Folders::CreateFolder(TEMP_PATH, "Export");
        }


        if (!\Utils\Folders::FolderExists(TEMP_CAPTCHA_PATH)) {
            \Utils\Folders::CreateFolder(TEMP_PATH, "Captcha");
        }


        if (!\Utils\Folders::FolderExists(TEMP_HTML_PATH)) {
            \Utils\Folders::CreateFolder(TEMP_PATH, "Html");
        }


        if (!\Utils\Folders::FolderExists(RES_PATH)) {
            \Utils\Folders::CreateFolder(ROOT_PATH, "res");
        }


        if (!\Utils\Folders::FolderExists(PDF_TEMPLATES_PATH)) {
            \Utils\Folders::CreateFolder(RES_PATH, "PDFTemplates");
        }
        
        if (!$this->isSetupExtensions("zip")) {
            $pageHtml.=  "Install zip - sudo apt-get install php-zip;sudo service apache2 restart; <br />";
        }
        
        if (!$this->isSetupExtensions("mbstring")) {
            $pageHtml.= "Install mbstring - sudo apt-get install php7.0-mbstring;service apache2 restart; <br />";
        }
        
        if (!$this->isSetupExtensions("SimpleXML")) {
            $pageHtml.= "Install xml - sudo apt-get install php7.0-xml;service apache2 restart; <br />";
        }

        if (!$this->isSetupExtensions("gd")) {
            $pageHtml.= "Install gd - sudo apt-get install php-gd;service apache2 restart; <br />";
        }
        
        if (!$this->isSetupExtensions("gd2")) {
            $pageHtml.= "Install gd2 - sudo apt-get install php-gd2;service apache2 restart; <br />";
        }
        \dibi::query("set global sql_mode=''");
        $this->SetTemplateData("pageHtml", $pageHtml);
        
    }

    private function isSetupExtensions($extensionsName) {
        return in_array($extensionsName, get_loaded_extensions());
    }

}
