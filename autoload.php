<?php 
include_once './Kernel/ExternalApi/PHPExcel.php';
include_once './vendor/autoload.php';
include_once './vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
spl_autoload_register('xAutoLoader');
function xAutoLoader($className) 
{
    $root =  $_SERVER["DOCUMENT_ROOT"];
    $kernel = $root."/Kernel/";
    $components = $kernel."Components/";
    $htmlComponents = $kernel."HtmlComponents/";
    $types = $kernel."Types/";
    $utils = $kernel."Utils/";
    
    $controllersKernel = $kernel."Controllers/";
    $modelKernel = $kernel."Model/";
    $objectKernel = $kernel."Objects/";
    $model = $root."/Model/";
    $controllers = $root."/Controllers/";
    $modelView = $root."/_dal/views/";
    $modelFunctions = $root."/_dal/functions/";
    $test = $root."/Tests/";
    $timers = $root."/Timers/";
    $templateFunction = $kernel."TemplateFunction/";
    $sendFormFunction = $kernel."SendFormFunction/";
    $className = explode('\\', $className);
    $className =end($className);
    

    if (file_exists($kernel.$className.".php"))
    {
        require_once $kernel.$className.".php";    
    }
    if (file_exists($components.$className.".php"))
    {
        require_once $components.$className.".php";
    }
    if (file_exists($htmlComponents.$className.".php"))
    {
        require_once $htmlComponents.$className.".php";
    }
    if (file_exists($types.$className.".php"))
    {
        require_once $types.$className.".php";
    }
    if (file_exists($model.$className.".php"))
    {
        require_once $model.$className.".php";
    }
    if (file_exists($controllers.$className.".php"))
    {
        require_once $controllers.$className.".php";
    }
    if (file_exists($modelView.$className.".php"))
    {
        require_once $modelView.$className.".php";
    }
    if (file_exists($modelFunctions.$className.".php"))
    {
        require_once $modelFunctions.$className.".php";
    }   
    if (file_exists($kernel."Plugins/".$className.".php"))
    {
        require_once $kernel."Plugins/".$className.".php";    
    }
    if (file_exists($components."Plugins/".$className.".php"))
    {
        require_once $components."Plugins/".$className.".php";
    }
    if (file_exists($htmlComponents."Plugins/".$className.".php"))
    {
        require_once $htmlComponents."Plugins/".$className.".php";
    }
    if (file_exists($types."Plugins/".$className.".php"))
    {
        require_once $types."Plugins/".$className.".php";
    }
    if (file_exists($model."Plugins/".$className.".php"))
    {
        require_once $model."Plugins/".$className.".php";
    }
    if (file_exists($controllers."Plugins/".$className.".php"))
    {
        require_once $controllers."Plugins/".$className.".php";
    }
    if (file_exists($modelView."Plugins/".$className.".php"))
    {
        require_once $modelView."Plugins/".$className.".php";
    }
    if (file_exists($modelFunctions."Plugins/".$className.".php"))
    {
        require_once $modelFunctions."Plugins/".$className.".php";
    }
    if (file_exists($test.$className.".php"))
    {
        require_once $test.$className.".php";
    }
    if (file_exists($timers.$className.".php"))
    {
        require_once $timers.$className.".php";
    }
    if (file_exists($utils.$className.".php"))
    {
        require_once $utils.$className.".php";
    }
    if (file_exists($controllersKernel.$className.".php"))
    {
        require_once $controllersKernel.$className.".php";
    }
    if (file_exists($modelKernel.$className.".php"))
    {
        require_once $modelKernel.$className.".php";
    }
    if (file_exists($objectKernel.$className.".php"))
    {
        require_once $objectKernel.$className.".php";
    }
    if (file_exists($templateFunction.$className.".php"))
    {
        require_once $templateFunction.$className.".php";
    }
    if (file_exists($sendFormFunction.$className.".php"))
    {
        require_once $sendFormFunction.$className.".php";
    }
}