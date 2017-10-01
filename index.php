<?php

try {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    session_start();
    $now = time();
    if (!empty($_SESSION["expired"])) {
        $expired = $_SESSION["expired"];
        if ($now > $expired) {
            session_unset();
            session_destroy();
            session_start();
        }
    } else {
        session_unset();
        session_destroy();
        session_start();
    }

    $_SESSION["expired"] = $now + 900;
    include './autoload.php';
    include_once './settings_global.php';
    \Kernel\Page::GetConfigByDomain();
    include_once './settings.php';
    $ajaxMode = (!empty($_GET["ajax"]) || !empty($_POST["ajax"])) ? true : false;
    $fileUpload = (!empty($_GET["fileUpload"]) || !empty($_POST["fileUpload"])) ? true : false;
    $iscss = (!empty($_GET["css"]) || !empty($_POST["css"])) ? true : false;
    $isjs = (!empty($_GET["js"]) || !empty($_POST["js"])) ? true : false;
    $isxml = (!empty($_GET["xml"]) || !empty($_POST["xml"])) ? true : false;
    $isxmlImport = (!empty($_GET["xmlimport"]) || !empty($_POST["xml"])) ? true : false;
    $xmldownload = (!empty($_GET["xmldownload"]) || !empty($_POST["xmldownload"])) ? true : false;
    $checkXmlImport = (!empty($_GET["checkxmlimport"]) || !empty($_POST["checkxmlimport"])) ? true : false;
    $test = (!empty($_GET["test"]) || !empty($_POST["test"])) ? true : false;
    $runalltest = (!empty($_GET["runalltest"]) || !empty($_POST["runalltest"])) ? true : false;
    $timers = (!empty($_GET["timers"]) || !empty($_POST["timers"])) ? true : false;
    $showPhpInfo = (!empty($_GET["phpinfo"]) && $_GET["phpinfo"] == SECURITY_STRING);
    $setup = (!empty($_GET["setup"])) ? true : false;
    $multiuaploadfiles = (!empty($_GET["multiuaploadfiles"])) ? true : false;
    $updatemodel = (!empty($_GET["updatemodel"])) ? true : false;
    $setLongRequest = (!empty($_GET["longrequest"])) ? true : false;
    $runalltimers = (!empty($_GET["runalltimers"])) ? true : false;
    $iframe = (!empty($_GET["iframe"])) ? true : false;
    $robots = (!empty($_GET["robots"])) ? true : false;
    $sitemap = (!empty($_GET["sitemap"])) ? true : false;
    $adminer = (!empty($_GET["adminer"]) && !empty($_GET["security"]) && $_GET["security"] == SECURITY_STRING) ? true : false;
    $getmyip = (!empty($_GET["getmyip"]) && !empty($_GET["security"]) && $_GET["security"] == SECURITY_STRING) ? true : false;

    if ($updatemodel) {

        $options = array(
            'driver' => SQL_DRIVER,
            'host' => SQL_SERVER,
            'username' => SQL_LOGIN,
            'password' => SQL_PASSWORD,
            'database' => "",
            'charset' => CHARSET
        );
        \dibi::connect($options);
        try {
            \dibi::query("CREATE DATABASE IF NOT EXISTS " . SQL_DATABASE);
            \dibi::query("USE " . SQL_DATABASE);
        } catch (Exception $ex) {
            \Kernel\Page::ApplicationError($ex);
        }
    } else {
        $options = array(
            'driver' => SQL_DRIVER,
            'host' => SQL_SERVER,
            'username' => SQL_LOGIN,
            'password' => SQL_PASSWORD,
            'database' => SQL_DATABASE,
            'charset' => CHARSET
        );
        dibi::connect($options);
    }
    if ($showPhpInfo) {
        phpinfo();
        return;
    }

    if ($adminer && Kernel\Page::IsDeveloperIp()) {
        header('Location: /Utils/adminer-4.3.1.php');
    }
    if ($getmyip) {
        echo \Utils\Utils::GetIp();
    }

    if ($updatemodel) {
        Kernel\Page::StartUpdateModel();
        return;
    }

    if ($ajaxMode) {
        \Kernel\Page::SetOrigin();
        $controller = empty($_GET["Controller"]) ? "" : $_GET["Controller"];
        $functionName = empty($_GET["functionName"]) ? "" : $_GET["functionName"];
        $paramsMode = empty($_GET["paramsMode"]) ? "" : $_GET["paramsMode"];
        \Kernel\Page::ApiFunction($controller, $functionName, $paramsMode);
    } else if ($fileUpload) {
        \Kernel\Page::SetOrigin();
        echo \Utils\Files::FileUpload();
    } else if ($iscss) {
        \Kernel\Page::SetOrigin();
        echo \Kernel\Page::LoadCss();
    } else if ($isjs) {
        \Kernel\Page::SetOrigin();
        echo \Kernel\Page::LoadJs();
    } else if ($isxml) {
        echo \Kernel\Page::LoadXml();
    } else if ($isxmlImport) {
        \Kernel\Page::SetOrigin();
        \Kernel\Page::RequestLogin();
        \Kernel\Page::XmlImport();
        \Kernel\Page::RequestLogout();
    } else if ($xmldownload) {
        \Kernel\Page::XmlDownload();
    } else if ($checkXmlImport) {
        echo \Kernel\Page::CheckXmlImport();
    } else if ($test) {
        \Kernel\Page::SetOrigin();
        $className = $_GET["ClassName"];
        echo \Kernel\Page::RunTest($className);
    } else if ($runalltest) {
        \Kernel\Page::SetOrigin();
        echo \Kernel\Page::RunAllTest();
    } else if ($timers) {
        \Kernel\Page::SetOrigin();
        \Kernel\Page::RunTimer($_GET["timerName"]);
    } else if ($multiuaploadfiles) {
        \Kernel\Page::SetOrigin();
        echo Utils\Files::UploadFiles();
    } else if ($setLongRequest) {
        \Kernel\Page::SetOrigin();
        Kernel\Page::SetLongRequestParam($_POST["name"], $_POST["value"]);
    } else if ($runalltimers) {
        \Kernel\Page::SetOrigin();
        Kernel\Page::RunAllTimers();
    } else if ($iframe) {
        \Kernel\Page::SetOrigin();
        echo \Kernel\Page::GetIframeHtml($_GET["key"]);
    } else if ($robots) {
        echo \Kernel\Page::GetWebRobots();
    } else if ($sitemap) {
        echo \Kernel\Page::GetSitemap();
    } else {
        $controller = empty($_GET["Controller"]) ? "" : $_GET["Controller"];
        $view = empty($_GET["View"]) ? "" : $_GET["View"];
        $template = empty($_GET["Template"]) ? "" : $_GET["Template"];
        if ($setup) {
            \Kernel\Page::StartUpdateModel(true);
            $controller = "Setup";
            $view = "Setup";
            $template = "Setup";
        }

        \Kernel\Page::PageLoad($controller, $view, $template);
    }
} catch (Exception $ex) {
    \Kernel\Page::ApplicationError($ex);
}
?>
