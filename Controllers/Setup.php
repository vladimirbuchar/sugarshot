<?php

namespace Controller;
use Dibi;

class Setup extends PageController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetViewPermition("Setup", array("*"));
    }

    public function Setup() {
        $this->SetStateTitle($this->GetWord("word887"));
        // create folders
        if (!\Utils\Folders::FolderExists(LOG_PATH))
        {
            \Utils\Folders::CreateFolder(ROOT_PATH, "Log");
        }
        if (!\Utils\Folders::FolderExists(TEMP_PATH))
        {
            \Utils\Folders::CreateFolder(ROOT_PATH, "Temp");
        }
        if (!\Utils\Folders::FolderExists(TEMP_EXPORT_PATH))
        {
            \Utils\Folders::CreateFolder(TEMP_PATH, "Export");
        }
        if (!\Utils\Folders::FolderExists(TEMP_CAPTCHA_PATH))
        {
            \Utils\Folders::CreateFolder(TEMP_PATH, "Captcha");
        }
            
        if (!\Utils\Folders::FolderExists(TEMP_HTML_PATH))
        {
            \Utils\Folders::CreateFolder(TEMP_PATH, "Html");
        }
        if (!\Utils\Folders::FolderExists(RES_PATH))
        {
            \Utils\Folders::CreateFolder(ROOT_PATH, "res");
        }
        if (!\Utils\Folders::FolderExists(PDF_TEMPLATES_PATH))
        {
            \Utils\Folders::CreateFolder(RES_PATH, "PDFTemplates");
        }
        
    }
    

}
