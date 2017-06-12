<?php 
    try{
        //include_once './Kernel/ExternalApi/PHPMailer-master/class.phpmailer.php';
        include_once './Kernel/ExternalApi/PHPExcel.php';
        include_once './vendor/autoload.php';
        include_once './vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        include_once './settings.php';
        
        $ajaxMode = (!empty($_GET["ajax"]) || !empty($_POST["ajax"]))? true: false;
        $fileUpload = (!empty($_GET["fileUpload"]) || !empty($_POST["fileUpload"]))? true: false;
        $fileExplorer = (!empty($_GET["fileExplorer"]) || !empty($_POST["fileExplorer"]))? true: false;
        $iscss = (!empty($_GET["css"]) || !empty($_POST["css"]))? true: false;
        $isjs = (!empty($_GET["js"]) || !empty($_POST["js"]))? true: false;
        $isxml = (!empty($_GET["xml"]) || !empty($_POST["xml"]))? true: false;
        $isxmlImport = (!empty($_GET["xmlimport"]) || !empty($_POST["xml"]))? true: false;
        $xmldownload = (!empty($_GET["xmldownload"]) || !empty($_POST["xmldownload"]))? true: false;
        $checkXmlImport = (!empty($_GET["checkxmlimport"]) || !empty($_POST["checkxmlimport"]))? true: false;
        $test = (!empty($_GET["test"]) || !empty($_POST["test"]))? true: false;
        $runalltest = (!empty($_GET["runalltest"]) || !empty($_POST["runalltest"]))? true: false;
        $timers = (!empty($_GET["timers"]) || !empty($_POST["timers"]))? true: false;
        $showPhpInfo = (!empty($_GET["phpinfo"]) && $_GET["phpinfo"] =="werafs18AWsa");
        $setup = (!empty($_GET["setup"]))? true:false;
        $multiuaploadfiles = (!empty($_GET["multiuaploadfiles"]))? true:false;
        $updatemodel  = (!empty($_GET["updatemodel"]))? true:false;
        $setLongRequest = (!empty($_GET["longrequest"]))? true:false;
        $runalltimers = (!empty($_GET["runalltimers"]))? true:false;
        if ($showPhpInfo)
        {
            phpinfo();
            return;
        }
        if ($updatemodel)
        {
            Kernel\Page::StartUpdateModel();
            return;
        }
        
        if($ajaxMode)
        {
            $controller = empty($_GET["Controller"]) ?"":$_GET["Controller"];
            $functionName = empty($_GET["functionName"]) ?"":$_GET["functionName"];
            $paramsMode = empty($_GET["paramsMode"]) ?"":$_GET["paramsMode"];
            
            \Kernel\Page::AjaxFunction($controller, $functionName, $paramsMode);
        }
        else if ($fileUpload)
        {
            echo \Kernel\Files::FileUpload();
        }
        else if ($fileExplorer)
        {
            echo \Kernel\Folders::FileExplorer();
        }
        else if ($iscss)
        {
            echo \Kernel\Page::LoadCss();
        }
        else if ($isjs)
        {
            echo \Kernel\Page::LoadJs();
        }
        else if ($isxml)
        {
            echo \Kernel\Page::LoadXml();
        }
        else if ($isxmlImport)
        {
            \Kernel\Page::RequestLogin();
            \Kernel\Page::XmlImport();
            \Kernel\Page::RequestLogout();
        }
        else if ($xmldownload)
        {
            \Kernel\Page::XmlDownload();
        }
        else if ($checkXmlImport)
        {
           echo \Kernel\Page::CheckXmlImport(); 
        }
        else if ($test)
        {
            $className = $_GET["ClassName"];
            echo \Kernel\Page::RunTest($className);
        }
        else if ($runalltest)
        {
            echo \Kernel\Page::RunAllTest();
        }
        else if ($timers) {
            \Kernel\Page::RunTimer($_GET["timerName"]);
        }
        else if ($multiuaploadfiles)
        {
            echo Kernel\Files::UploadFiles();
        }
        else if ($setLongRequest)
        {
            Kernel\Page::SetLongRequestParam($_POST["name"], $_POST["value"]);
        }
        else if ($runalltimers)
        {
            Kernel\Page::RunAllTimers();
        }
        else 
        {
            
            if (UPDATE_MODEL)
            {
                \Kernel\Page::StartUpdateModel();
            }
            $controller = empty($_GET["Controller"]) ?"":$_GET["Controller"];
            $view =empty($_GET["View"]) ?"":$_GET["View"]; 
            $template = empty($_GET["Template"]) ?"":$_GET["Template"]; 
            if ($setup)
            {
                $controller = "Setup";
                $view = "Setup";
                $template = "Setup";
            }
            
            \Kernel\Page::PageLoad($controller, $view,$template);
        } 
    }
    catch (Exception $ex)
    {
        \Kernel\Page::ApplicationError($ex);
    }
    
?>