<?php

namespace Controller;

class Setup extends PageController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetViewSettings("Setup", array("*"));
    }

    public function Setup() {

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
            echo "Install zip - sudo apt-get install php-zip;sudo service apache2 restart;";
        }
        if (!$this->isSetupExtensions("mbstring")) {
            echo "Install mbstring - sudo apt-get install php7.0-mbstring;service apache2 restart;";
        }
        if (!$this->isSetupExtensions("SimpleXML")) {
            echo "Install xml - sudo apt-get install php7.0-xml;service apache2 restart;";
        }

        if (!$this->isSetupExtensions("gd")) {
            echo "Install gd - sudo apt-get install php-gd;service apache2 restart;";
        }
        
        if (!$this->isSetupExtensions("gd2")) {
            echo "Install gd2 - sudo apt-get install php-gd2;service apache2 restart;";
        }
    }

    private function isSetupExtensions($extensionsName) {
        return in_array($extensionsName, get_loaded_extensions());
    }

}
