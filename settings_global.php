<?php 

$protocol = "http://";
if ($_SERVER["HTTPS"] == "on")
{
    $protocol = "https://";
}
GlobalSettings::DeclareConst("SERVER_PROTOCOL", $protocol);
GlobalSettings::DeclareConst("HTTP_HOST",$_SERVER["HTTP_HOST"]);
GlobalSettings::DeclareConst("SQLMODE","mysql");
GlobalSettings::DeclareConst("UPDATE_MODEL",FALSE);    
GlobalSettings::DeclareConst("AUTO_SETUP",FALSE);      
GlobalSettings::DeclareConst("DEVELOPER_IP","");
GlobalSettings::DeclareConst("VERSION",mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
GlobalSettings::DeclareConst("ENABLE_COMPATIBILITY_MODE",FALSE);
GlobalSettings::DeclareConst("COMPATIBILIOTY_VERSION","1.0");
GlobalSettings::DeclareConst("TEMPLATEMODE","Smarty");
GlobalSettings::DeclareConst("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"]."/");
GlobalSettings::DeclareConst("CONTROLLER_PATH", ROOT_PATH."Controllers/");
GlobalSettings::DeclareConst("VIEWS_PATH",ROOT_PATH."Views/"); 
GlobalSettings::DeclareConst("VIEWS_PATH_PLUGINS",ROOT_PATH."Views/Plugins/"); 
GlobalSettings::DeclareConst("LOG_PATH",ROOT_PATH."Log/");
GlobalSettings::DeclareConst("KERNEL_PATH",ROOT_PATH."Kernel/");
GlobalSettings::DeclareConst("COMPONENTS_PATH",KERNEL_PATH."Components/");
GlobalSettings::DeclareConst("COMPONENTS_PATH_PLUGINS",KERNEL_PATH."Components/Plugins/");
GlobalSettings::DeclareConst("HTML_COMPONENTS_PATH",KERNEL_PATH."HtmlComponents/");
GlobalSettings::DeclareConst("TYPES_PATH",KERNEL_PATH."Types/");
GlobalSettings::DeclareConst("MODEL_PATH",ROOT_PATH."Model/");
GlobalSettings::DeclareConst("MODEL_PATH_PLUGINS",ROOT_PATH."Model/Plugins/");
GlobalSettings::DeclareConst("MODEL_VIEWS_PATH",ROOT_PATH."_dal/views/");
GlobalSettings::DeclareConst("MODEL_VIEWS_PATH_PLUGINS",ROOT_PATH."_dal/views/Plugins/");
GlobalSettings::DeclareConst("MODEL_FUNCTIONS_PATH",ROOT_PATH."_dal/functions/");
GlobalSettings::DeclareConst("MODEL_FUNCTIONS_PATH_PLUGINS",ROOT_PATH."_dal/functions/Plugins/");
GlobalSettings::DeclareConst("RES_PATH",ROOT_PATH."res/");
GlobalSettings::DeclareConst("FILE_REPOSITORY_PATH",RES_PATH."FileRepository/");
GlobalSettings::DeclareConst("PDF_TEMPLATES_PATH",RES_PATH."PDFTemplates/");
GlobalSettings::DeclareConst("TEMPLATE_PATH",VIEWS_PATH.TEMPLATEMODE. "/Templates/");
GlobalSettings::DeclareConst("TEMP_PATH",ROOT_PATH."Temp/");
GlobalSettings::DeclareConst("TEMP_HTML_PATH",TEMP_PATH."Html/");
GlobalSettings::DeclareConst("TEMP_EXPORT_PATH",TEMP_PATH."Export/");
GlobalSettings::DeclareConst("TEMP_CAPTCHA_PATH",TEMP_PATH."Captcha/");
GlobalSettings::DeclareConst("TEST_PATH",ROOT_PATH."Tests/");
GlobalSettings::DeclareConst("TIMERS_PATH",ROOT_PATH."Timers/");


/** log settings*/
GlobalSettings::DeclareConst("ERROR_LOG_FILENAME","Errors.log");
GlobalSettings::DeclareConst("WRITE_TO_LOG",TRUE);
GlobalSettings::DeclareConst("DEFAULT_TEMPLATE_NAME","Index");
GlobalSettings::DeclareConst("DEFAULT_CONTROLER_NAME","IndexWeb");
GlobalSettings::DeclareConst("DEFAULT_VIEW_NAME","IndexPage");
GlobalSettings::DeclareConst("MODULE_CONTROLLER","Modules");
GlobalSettings::DeclareConst("ENGLISCH_ALPHABET","abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ");
GlobalSettings::DeclareConst("SERVER_NAME",SERVER_PROTOCOL.$_SERVER["HTTP_HOST"]."/"); 
if (!empty($_GET["lang"]))
    GlobalSettings::DeclareConst("SERVER_NAME_LANG",SERVER_NAME.$_GET["lang"]."/");
else 
    GlobalSettings::DeclareConst("SERVER_NAME_LANG",SERVER_NAME);
GlobalSettings::DeclareConst("DEFAULT_LANG", "CS");

GlobalSettings::DeclareConst("IGNORE_ALTERNATIVE_CONTENT", TRUE);

class GlobalSettings
{
    public static function DeclareConst($name, $value)    
    {
        if (!defined($name))
        {
            define($name, $value);
        }
    }
    
}