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
        if (!\Kernel\Folders::FolderExists(LOG_PATH))
        {
            \Kernel\Folders::CreateFolder(ROOT_PATH, "Log");
        }
        if (!\Kernel\Folders::FolderExists(TEMP_PATH))
        {
            \Kernel\Folders::CreateFolder(ROOT_PATH, "Temp");
        }
        if (!\Kernel\Folders::FolderExists(TEMP_EXPORT_PATH))
        {
            \Kernel\Folders::CreateFolder(TEMP_PATH, "Export");
        }
        if (!\Kernel\Folders::FolderExists(TEMP_CAPTCHA_PATH))
        {
            \Kernel\Folders::CreateFolder(TEMP_PATH, "Captcha");
        }
            
        if (!\Kernel\Folders::FolderExists(TEMP_HTML_PATH))
        {
            \Kernel\Folders::CreateFolder(TEMP_PATH, "Html");
        }
        if (!\Kernel\Folders::FolderExists(RES_PATH))
        {
            \Kernel\Folders::CreateFolder(ROOT_PATH, "res");
        }
        if (!\Kernel\Folders::FolderExists(PDF_TEMPLATES_PATH))
        {
            \Kernel\Folders::CreateFolder(RES_PATH, "PDFTemplates");
        }
        
    }
    

}
