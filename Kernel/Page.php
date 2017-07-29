<?php

namespace Kernel;

use Utils\StringUtils;
use Controller\Templates;
use Model\Users;
use Utils\Utils;
use Types\xWebExceptions;
use Model\ContentVersion;
use Smarty;
use Dibi;

/** hlavní třída, která se volá jako první z indexu */
class Page {
        
    /** $controllerName - jméno použitého controlleru
     * $viewName - použité view
     * $templateName - templata
     */
    private static $_componentsScript;
    private static $_componentCss;
    private static $_emptyComponents;
    public static $MoveCssToHeader = true;
    public static $MoveJsToHeader = true;
    private static $_words = array();



    /**  */
    //endregion
    public static function PageLoad($controllerName, $viewName, $templateName) {
        try {
            // pokud je nějaká hodnota prázdná zkusíme nastavit defaultuní
            if (empty($templateName))
                $templateName = DEFAULT_TEMPLATE_NAME;
            if (empty($controllerName))
                $controllerName = DEFAULT_CONTROLER_NAME;
            if (empty($viewName))
                $viewName = DEFAULT_VIEW_NAME;
            if ($controllerName == MODULE_CONTROLLER) {
                $controllerName = $viewName;
            }
            
                
            if (empty($templateName) || empty($controllerName) || empty($viewName))
                throw new Exception(xWebExceptions::$NoControlerFunctionMode);
                Page::TemplateData($templateName, $controllerName, $viewName);
        } catch (Exception $ex) {
            
            Page::ApplicationError($ex);
            exit;
        }
    }

    /** metoda volaná při ajaxu
      $controllerName - jméno controlleru
     * $functionName - jméno volané funkce
     * $mode - mód  GET,POST,JSON
     *      */
    public static function AjaxFunction($controllerName, $functionName, $mode) {
        try {
            if (empty($controllerName) || empty($functionName) || empty($mode)) {
                return;
            }
            $controllerPath = CONTROLLER_PATH . $controllerName . ".php";
            if (!Files::FileExists($controllerPath)) {
                $controllerPath = CONTROLLER_PATH . "Plugins/" . $controllerName . ".php";
                //Files::WriteLogFile($controllerName." ". $functionName);
            }
            require_once $controllerPath;
            $controllerName = "Controller\\" . $controllerName;
            $controller = new $controllerName();
            $out = "";
            
            if ($controller->IsAjaxFunction($functionName) && !$controller->GetNoAccess()) {
                
                $mode = strtolower($mode);
//                echo $mode;
                if ($mode == "postobject") { 
                    $params = $_POST["params"];
                    $out = $controller->$functionName($params);
                }
                else if ($mode == "getobject")
                {
                    $params = $_GET["params"];
                    $out = $controller->$functionName($params);
                }
                else if ($mode == "jsonobject")
                {
                    $params = $_GET["params"];
                    $out = $controller->$functionName($params);
                }
                else if ($mode == "longrequest" || $mode == "longrequestjson")
                {
                    $params = self::GetLongRequest();
                    $out = $controller->$functionName($params);
                    self::ClearLongRequest();
                }
                
                
                else {
                    $out = $controller->$functionName();
                }
                if ($mode == "get" || $mode == "post" || $mode == "postobject" || $mode == "getobject" || $mode =="postjson" || $mode =="longrequest") {
                    echo self::RenderXWebComponent($out);
                } else if ($mode == "json" || $mode == "jsonobject" || $mode == "longrequestjson") {
                    echo json_encode($out);
                }
            }
        } catch (Exception $ex) {
            Page::ApplicationError($ex);
            exit;
        }
    }

    /** metoda pro zobrazení contentu stránky
     * $templateName jméno template
     * $controllerName jméno použitého controlleru
     * $viewName jméno použitého view
     *  */
    private static function TemplateData($templateName, $controllerName, $viewName) {
        
        $frontend = false;
        if (empty($_GET["ajax"])) {
            $frontend = empty($_GET) || !empty($_GET["seourl"]) || !empty($_GET["renderHtml"]) || !empty($_GET["lang"]) ? true : false;
        }
        $tmpHtmlFileName = TEMP_HTML_PATH . StringUtils::GenerateRandomString() . time() . ".html";
        $templatepPath = TEMPLATE_PATH . $templateName . ".html";
        $templateSystem = null;
        
        if (self::IsSmarty()) {
            include_once './Kernel/ExternalApi/smarty/Smarty.class.php';
            $templateSystem = new Smarty();

            $templateSystem->left_delimiter = '<!--{';
            $templateSystem->right_delimiter = '}-->';
            $templateSystem->cache_lifetime = 600;
        }
        
        
         $html = "";
        if ($frontend)
        {
            
            $html = self::GetHtmlFromCache();
            $html = self::RenderXWebComponent($html);
            
        }
        
        if (empty($html))
        {
            try{
            $template = new Templates();
            }
 catch (Exception $e)
 {
     echo $e;
     die();
 }
            
            /*     if ($template->UseHttps() && !$template->IsHttps()) {
            $template->HttpsRedirect();
         }*/
        
        
        

        if (!empty($template->SharedView))
            $templatepPath = TEMPLATE_PATH . $template->SharedView . ".html";

        $template->$templateName();
        $templateData = $template->GetTemplateData();
        $templateData["CanShowState"] = TRUE;

        // zkontrolujeme zda máme práva na použití controlleru
        if (!Page::CheckPermintionController($template)) {
            $templateData["CanShowState"] = false;
        }

        // zkontrolujeme práva na view
        if (!$template->GetViewPermition($templateName, "Templates") || $template->GetNoAccess()) {
            $templateData["CanShowState"] = false;
        }

        // načtení js a css z template
        $templateStyle = $template->GetStyles();
        $templateJs = $template->GetScripts();
        


        // jdeme na zobrazený stav (view)
        $className = $controllerName;
        $controllerName = 'Controller\\' . $controllerName;
        $controller = new $controllerName();

        // kontrola práv
        if (!Page::CheckPermintionController($controller) || $controller->GetNoAccess()) {
            $templateData["CanShowState"] = false;
        }
        if (!empty($_POST["phpFunction"])) {
            $functionName = $_POST["phpFunction"];
            if ($controller->IsCommandFunction($functionName))
                $controller->$functionName();
        }
        
        if (!$controller->GetViewPermition($viewName)) {
            $templateData["CanShowState"] = false;
        }
        
        if ($controller->IsAdmin())
        {
            if (Files::FileExists(ROOT_PATH . "Scripts/ViewScripts/$viewName.js"))
                $controller->AddScript("/Scripts/ViewScripts/$viewName.js");
            if (Files::FileExists(ROOT_PATH . "Scripts/ViewScripts/Plugins/$viewName.js"))
                $controller->AddScript("/Scripts/ViewScripts/Plugins/$viewName.js");
        }
        $controller->$viewName();
        
        $controllerData = $controller->GetTemplateData();
        $controllerData["ExitQuestion"] = false;
        if ($controller->ExitQuestion)
            $controllerData["ExitQuestion"] = true;
        
        $controllerStyles = $controller->GetStyles();
        $controllerJs = $controller->GetScripts();
        
        $styles = array();
        if (!empty($templateStyle) && !empty($controllerStyles))
            $styles = array_merge($templateStyle, $controllerStyles);
        else if (!empty($templateStyle))
            $styles = $templateStyle;
        elseif (!empty($controllerStyles))
            $styles = $controllerStyles;

        $js = array();
        if (!empty($templateJs) && !empty($controllerJs)) {
            $js = array_merge($templateJs, $controllerJs);
        } else if (!empty($templateJs)) {
            $js = $templateJs;
        } else if (!empty($controllerJs)) {
            $js = $controllerJs;
        }
        $js = \Utils\ArrayUtils::Distinct($js);
        $styles = \Utils\ArrayUtils::Distinct($styles);
        $templateData = array_merge($templateData, $controllerData);
        $templateData["styles"] = $styles;
        $templateData["scripts"] = $js;
        
        

        if (empty($templateData["stateTitle"]))
            $templateData["stateTitle"] = "";

        $templateData["ControllerName"] = $className;

        if (!empty($_GET["webid"]))
            $templateData["WebId"] = $_GET["webid"];
        else
            $templateData["WebId"] = 0;

        if (!empty($_GET["langid"]))
            $templateData["LangId"] = $_GET["langid"];
        else
            $templateData["LangId"] = 0;
        if (self::IsSmarty()) {
        

            if (!empty($templateData)) {
                if (!empty($controller->SharedView)) {
                    
                    $templateData["stateName"] = VIEWS_PATH . TEMPLATEMODE . "/Shared/" . $controller->SharedView . ".html";
                    if (Files::FileExists(ROOT_PATH . "Scripts/ViewScripts/$controller->SharedView.js"))
                        $controller->AddScript("/Scripts/ViewScripts/$controller->SharedView.js");
                }

                foreach ($templateData as $key => $value) {
                    $templateSystem->assign($key, $value, false);
                }
            }
            $html = $templateSystem->fetch($templatepPath);
        }
        
        // zobrazení stránky
        if (!Page::NoRenderComponentState($controllerName, $viewName)) {
            $html = Page::RenderXWebComponent($html);
        }
        
       if (StringUtils::ContainsString($html, "<headerScript/>") && self::$MoveJsToHeader) {
            $scriptHtml = "";
            $script = array();
            preg_match_all("/<script\b[^>]*>(.*?)<\/script>/is", $html, $script);
            $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
            $script = $script[0];
            if (!empty(self::$_componentsScript)) {
                $script = array_merge($script, self::$_componentsScript);
            }

            for ($x = 0; $x < count($script); $x++) {
                $scriptHtml .= $script[$x] . "\n";
            }
            $html = str_replace("<headerScript/>", $scriptHtml, $html);
        } else {
            $html = str_replace("<headerScript/>", "", $html);
        }
        if (StringUtils::ContainsString($html, "<headerCss/>") && self::$MoveCssToHeader) {
            $scriptHtml = "";
            $script = array();
            preg_match_all("/<link\b[^>]*>/is", $html, $script);
            $html = preg_replace('/<link\b[^>]*>/is', "", $html);
            $script = $script[0];
            if (!empty(self::$_componentCss)) {
                $script = array_merge($script, self::$_componentCss);
            }
            for ($x = 0; $x < count($script); $x++) {
                $scriptHtml .= $script[$x];
            }
            $html = str_replace("<headerCss/>", $scriptHtml, $html);
        }
        else 
        {
            $html = str_replace("<headerCss/>", "", $html);
        }

        $systemData = self::$_emptyComponents;
        $systemData["MKTIME"] = Utils::GetNowMktime();
        $systemData["VERSION"] = VERSION;
        if ($template->IsFrontEnd()) {
            if ($_SERVER["REQUEST_URI"] == "/")
            {
                $systemData["PAGECLASS"] = "homepage";
            }
            else 
            {
                $systemData["PAGECLASS"] = "inPage";
            }

            $systemData["ACTUALYEAR"] = Utils::GetActualYear();
            $systemData["USERLOGINCLASS"] = "userNoLogin";
            $systemData["USERFULLNAME"] = "";
            $systemData["USERNAME"] = "";
            $systemData["SERVER_URL"] = SERVER_NAME_LANG;
            

            if (empty($_GET["lang"]))
            {
                $systemData["LANGCLASS"] = "";
                $systemData["LANG"] = "";
            }
            else
            {
                $systemData["LANGCLASS"] = "lang-" . $_GET["lang"];
                $systemData["LANG"] = $_GET["lang"];
            }

            if ($controller->IsLoginUser()) {
                $systemData["USERFULLNAME"] = $controller->GetFullUserName();
                $systemData["USERNAME"] = $controller->GetUserName();
                $systemData["USERLOGINCLASS"] = "userIsLogged";
            }
        }
        $html = str_replace('<!--{$', '{', $html);
        $html = str_replace('}-->', '}', $html);
        if (!Page::NoRenderComponentState($controllerName, $viewName)) {
            $systemData = array_merge($systemData,$controller->GetWordList());
        }
        $systemData = \Utils\ArrayUtils::AddReplaceCharsToKey($systemData);
        
        $html = preg_replace(array_keys($systemData),$systemData, $html);
        if ($template->IsFrontEnd()) {
            $html = self::CallTemplateFunction($html);
            $html = preg_replace('({[A-Za-z0-9\-]*})', "", $html);
            $html = preg_replace('/<!--(.*)-->/Uis', '', $html);
            $html = self::CompressString($html);
        }
        }
       $html =  str_replace("http://", "https://", $html);
        if (self::IsSmarty()) { 
            $templateSystem->display('string:'.$html);
        }
        
    }

    private static function CheckPermintionController($controler) {
        if ($controler->GetControllerPermition())
            return true;
        return false;
        //throw new Exception("SECURITY ERROR");
    }

    private static function UpdateModel($model) {
        try{
        for ($i = 0; $i < count($model); $i++) {
            if (empty($model[$i]["Name"]))
                $name = $model[$i];
            else
                $name = $model[$i]["Name"];
            
            if (strpos($name, '.php') !== FALSE) {
                
            } else {
                $name = $name . ".php";
            }
            $modelPath = MODEL_PATH . $name;
            
            $className = basename($modelPath, ".php");

            if (empty($className) || $className == "") {
                continue;
            }
            
            
            $className = "Model\\" . $className;
            $modelClass = null;
            $modelClass = new $className();
     
            
            $modelClass->CreateTable();
            $modelClass->OnCreateTable();
            $modelClass->SaveNewColums();
            $modelClass->TableMigrate();
            
            if ($modelClass->WasCreated)
                $modelClass->InsertDefaultData();
        }
        }
        catch (Exception $e)
        {
            echo $e;
        }
    }

    private static function CreateViews($model) {
        for ($i = 0; $i < count($model); $i++) {
            if (empty($model[$i]["Name"]))
                $name = $model[$i];
            else
                $name = $model[$i]["Name"];
            if (strpos($name, '.php') !== FALSE) {
                
            } else {
                $name = $name . ".php";
            }
            $modelPath = MODEL_PATH . $name;
            $className = basename($modelPath, ".php");
            $className = "Model\\" . $className;
            $modelClass = new $className();
            $modelClass->CreateView();
        }
    }

    private static function CreateFunction($model) {
        for ($i = 0; $i < count($model); $i++) {
            if (empty($model[$i]["Name"]))
                $name = $model[$i];
            else
                $name = $model[$i]["Name"];
            if (strpos($name, '.php') !== FALSE) {
                
            } else {
                $name = $name . ".php";
            }
            $modelPath = MODEL_PATH . $name;
            $className = basename($modelPath, ".php");
            $modelClass = new $className();
            $modelClass->CreateFunction();
        }
    }

    public static function ApplicationError($ex, $goHome = false, $clear = false) {
        if (SHOW_ERRORS) {
            echo $ex;
        }
        if (WRITE_TO_LOG) {
            Files::WriteLogFile($ex);
        }
        if ($clear) {
            $_SESSION = null;
            $_COOKIE = null;
        }
        if ($goHome) {
            header("Location: " + SERVER_NAME_LANG);
        }
    }

    private static function IsDeveloperIp() {
        if ($_SERVER["HTTP_HOST"] == "localhost")
            return true;
        if (empty(DEVELOPER_IP))
            return true;
        return false;
    }

    public static function StartUpdateModel($upadateModel  = false) {
        try{
            
            
        // nejdříve zkontrolujeme a případně upteneme model
        if (UPDATE_MODEL && Page::IsDeveloperIp() || !empty($_GET["updatemodel"]) || $upadateModel) {


            // tabulky
            $folderContent = Folders::GetObjectsInFolder(MODEL_PATH, true);
            $folderContent = \Utils\ArrayUtils::GetColumnsvalue($folderContent, "Name");
            $folderContent2 = Folders::GetObjectsInFolder(MODEL_PATH_PLUGINS, true);
            $folderContent2 = \Utils\ArrayUtils::GetColumnsvalue($folderContent2, "Name");
            $folderContent = array_merge($folderContent, $folderContent2);

            $modelPriority = array();
            $modelPriority[] = "ContentSecurity.php";
            $modelPriority[] = "ObjectHistory.php";
            $modelPriority[] = "UserGroups.php";
            $modelPriority[] = "Users.php";
            $modelPriority[] = "UsersInGroup.php";
            $modelPriority[] = "UserGroupsModules.php";
            $modelPriority[] = "Modules.php";
            $modelPriority[] = "ContentConnection.php";
            $modelPriority[] = "AdminLangs.php";
            $modelPriority[] = "WordGroups.php";
            $folderContent = array_merge($modelPriority, $folderContent);


            $folderContent = array_unique($folderContent);
            $folderContent = array_values($folderContent);
            Page::UpdateModel($folderContent);
            // views
            $folderContentViewPriority = array();
            $folderContentViewPriority[] = "FrontendDetailPreview.php";
            $folderContentView = Folders::GetObjectsInFolder(MODEL_VIEWS_PATH, true);
            $folderContentView = \Utils\ArrayUtils::GetColumnsvalue($folderContentView, "Name");
            $folderContentView2 = Folders::GetObjectsInFolder(MODEL_VIEWS_PATH_PLUGINS, true);
            $folderContentView2 = \Utils\ArrayUtils::GetColumnsvalue($folderContentView2, "Name");
            $folderContentView = array_merge($folderContentView, $folderContentView2);
            $folderContentView = array_merge($folderContentViewPriority, $folderContentView);
            $folderContentView = array_unique($folderContentView);
            $folderContentView = array_values($folderContentView);
            Page::CreateViews($folderContentView);

            //functions
            $folderContentFunction = Folders::GetObjectsInFolder(MODEL_FUNCTIONS_PATH, true);
            $folderContentFunction2 = Folders::GetObjectsInFolder(MODEL_FUNCTIONS_PATH_PLUGINS, true);
            $folderContentFunction2 = \Utils\ArrayUtils::GetColumnsvalue($folderContentFunction2, "Name");
            $folderContentFunction = \Utils\ArrayUtils::GetColumnsvalue($folderContentFunction, "Name");
            $folderContentFunction = array_merge($folderContentFunction, $folderContentFunction2);
            Page::CreateFunction($folderContentFunction);
            
        }
        }
        catch(Exception $e)
        {
            echo $e;
            die();
        }
    }

    public static function LoadCss() {
        header("Content-type: text/css");
        $content =  ContentVersion::GetInstance();
        return  self::CompressString($content->GetFrontendCss($_GET["id"], $_GET["langId"]));
    }

    public static function LoadJs() {
        header("Content-type: text/javascript");
        $content = ContentVersion::GetInstance();
        return  self::CompressString($content->GetFrontendJs($_GET["id"], $_GET["langId"]));
    }

    public static function LoadXml() {
        header('Content-type: application/xml');
        header("Content-length: 0");
        $content = ContentVersion::GetInstance();
        $cssDetail = $content->GetFrontendXml($_GET["SeoUrl"]);
        $cssDetail = self::RenderXWebComponent($cssDetail);
        $cssDetail = str_replace("##%#","<%",$cssDetail);
        $cssDetail = str_replace("#%##","%>",$cssDetail);
        $cssDetail = str_replace( '##',"'",$cssDetail);
        $outArray = array();
        preg_match_all("(<%([A-Za-z0-9(),\"\'{}:])*%>)",$cssDetail , $outArray);
        $cssDetail = self::RunTemplateFunction($cssDetail,$outArray);
        $cssDetail = str_replace( '"<![CDATA[','"',$cssDetail);
        $cssDetail = str_replace( ']]>"','"',$cssDetail);
        
        
        return $cssDetail;
    }

    public static function XmlImport() {
        $content = ContentVersion::GetInstance();
        $content->XmlImport($_GET["SeoUrl"]);
    }

    public static function CheckXmlImport() {
        $content = ContentVersion::GetInstance();
        $content->CheckXml($_GET["SeoUrl"]);
    }

    public static function XmlDownload() {
        $content = ContentVersion::GetInstance();
        $content->XmlDownload($_GET["SeoUrl"]);
    }

    public static function RunTest($classsName) {
        $class = new $classsName();
        $class->StartTest();
        $testOut = $class->GetResult();
        $time = $class->GetTime();
        $out = "<br /> ----  TEST NAME $classsName ---- <br />";
        $out .= "TIME : $time <br />";
        $out .= "RESULT: $testOut";
        return $out;
    }

    public static function RunAllTest() {
        $folder = Folders::GetObjectsInFolder(TEST_PATH);
    }

    public static function RunTimer($timerName) {
        try {
            $class = new $timerName();
            $class->RunTimer();
            
        } catch (Exception $e) {
            Page::ApplicationError($e);
        }
    }
    
    public static function RunAllTimers()
    {
        $timers = Folders::GetObjectsInFolder(TIMERS_PATH, true, true);
        print_r($timers);
        
    }

    private static function NoRenderComponentState($controller, $viewName) {
        if (($controller == "WebEdit" && $viewName == "TemplateDetail"))
            return true;
        return false;
    }

    public static function RenderXWebComponent($inHtml) {
        
        preg_match_all("(<xWeb:Component(( )*[A-Za-z]*=\"[\[\]!\%:A-Za-z0-9\_\-/\;( )\',\#=\.><]*\")*( )*/>)", $inHtml, $outArray);
        $inHtml = self::ReplaceComponent($outArray, $inHtml);
        return $inHtml;
    }

    public static function CallTemplateFunction($inHtml) {
        $outArray = array();
        preg_match_all("(<%(.*)%>)",$inHtml , $outArray);
        $inHtml = self::RunTemplateFunction($inHtml,$outArray);
        return $inHtml;
    }
    
    private static function RunTemplateFunction($inHtml,$outArray)
    {
        if (!empty($outArray)) {
            foreach ($outArray[0] as $tfunction) {
                $tmpString = $tfunction;
                $tfunction = StringUtils::RemoveString($tfunction, "<%");
                $tfunction = StringUtils::RemoveString($tfunction, ")%>");
                $tfunction = trim($tfunction);
                $ar = explode("(", $tfunction);
                
                $fHtml = "";
                if (!empty($ar)) {
                    
                    $functionName = "TemplateFunction\\" . trim($ar[0]);
                    if (!empty($ar[1])) {
                        
                        $params = explode("','", $ar[1]);
                        
                        $functionName::SetParametrs($params);
                        
                    }
                    $fHtml = $functionName::CallFunction();
                }
                $inHtml = str_replace($tmpString, $fHtml, html_entity_decode($inHtml));
            }
        }
        return $inHtml;   
    }


    private static function ReplaceComponent($outArray, $inHtml) {
        $componentString = array();
        
        foreach ($outArray[0] as $row) {
            $itemArray = array();
            $replace = $row;
            $obj = new RenderUserComponent();
            preg_match_all("(([\[\]!A-Za-z]*)=\"([A-Za-z0-9\%:\_\-/\;( )\',\#=\.><!\[\]]*)\")", $replace, $itemArray);
            
            
            for ($i = 0; $i < count($itemArray[1]); $i++) {
                $obj->SetParametrs($itemArray[1][$i], $itemArray[2][$i]);
            }
            
            $componentHtml = $obj->RenderHtml();
            
            if (!empty($_GET["caching"]) && !$obj->CacheComponent() )
            {
                
                $componentHtml = $replace;
            }

            $inHtml = str_replace($replace, $componentHtml, $inHtml);
            
            $jsscript = $obj->LinkJavascript();
            $css = $obj->LinkCss();
            $isEmpty = $obj->IsEmptyComponent();
            $idComponent = $obj->GetIdComponent();
            
            if (!empty($jsscript)) {
                self::$_componentsScript[] = $jsscript;
            }
            if (!empty($css)) {
                self::$_componentCss[] = $css;
            }
            if ($isEmpty) {
                self::$_emptyComponents[$idComponent . "-dn"] = "dn";
            }

            $tmpComponentString = $obj->ReplaceComponetString();
            if (!empty($tmpComponentString)) {
                $componentString = array_merge($componentString, $tmpComponentString);
            }
        }
        if (!empty($componentString)) {
            $componentString = \Utils\ArrayUtils::AddReplaceCharsToKey($componentString);
            $inHtml = preg_replace(array_keys($componentString),$componentString, $inHtml);
        }
        
        return $inHtml;
    }

    public static function RequestLogin() {
        if (!empty($_GET["login"]) && !empty($_GET["pswrd"])) {
            $usr =  Users::GetInstance();
            if ($usr->UserLogin($_GET["login"], $_GET["pswrd"])) {
                $_SESSION["RequestLogin"] = true;
            }
        }
    }

    public static function RequestLogout() {
        if (!empty($_SESSION["RequestLogin"])) {
            $_SESSION["RequestLogin"] = null;
            $usr = Users::GetInstance();
            $usr->UserLogout();
        } 
    }

    private static function IsSmarty() {
        return TEMPLATEMODE == "Smarty";
    }
    
    public static function CompressString($html)
    {
        $html =  preg_replace('/\s+/', ' ',$html);
        $html =  preg_replace('/> </', '><',$html);
        return $html;
    }
    
    public static function SetLongRequestParam($name,$value)
    {
        $params = array();
        if (!empty($_SESSION["longRequestParams"]))
            $params = $_SESSION["longRequestParams"];
        if ($name == "Privileges" || $name == "TemplateSettings")
            $params[$name] = $value;
        else 
            $params[$name] = base64_decode($value);
        $_SESSION["longRequestParams"] = $params;
    }
    
    private static function GetLongRequest()
    {
        return $_SESSION["longRequestParams"];
    }
    private static function ClearLongRequest()
    { 
        unset($_SESSION["longRequestParams"]);
    }
    
    private static function GetHtmlFromCache()
    {
        return  "";
        if (!empty($_GET["caching"]))
            return "";
        $url = SERVER_NAME_LANG;
        $user = \Model\Users::GetInstance();
        $groupId = $user->GetUserGroupId();
        $cache = new \Model\Cache();
        $out = array();
        if (!empty($_GET["seourl"]))
        {
            $url = $url. $_GET["seourl"]."/";
            $out = $cache->SelectByCondition("SeoUrl = '$url' AND UserGroupId = $groupId","",array("HtmlCache"));
        }
        else 
        {
            $out = $cache->SelectByCondition("SeoUrl = '$url' ","",array("HtmlCache"));
        }
        if (empty($out))
            return "";
        return $out[0]["HtmlCache"];
    }
    public static function IsLocalHost()
    { 
        if (StringUtils::ContainsString(SERVER_NAME,"localhost") || StringUtils::EndWith(SERVER_NAME,".dev") || StringUtils::ContainsString(SERVER_NAME,".dev:")) {
            return true;
        }
        return false;
    }
    
    public static function GetConfigByDomain()
    {
        if (self::IsLocalHost())
        {
            if (Files::FileExists(ROOT_PATH."settings_localhost.php"))
                {
                    include_once ROOT_PATH."settings_localhost.php";
                }
        }
        $serverUrl = SERVER_NAME;
        $serverUrl = StringUtils::RemoveString(SERVER_NAME, SERVER_PROTOCOL);
        $ar = explode(".", $serverUrl);
        if (count($ar)  > 0)
        {
            if (!empty($ar[0]) && $ar[0] != "www")
            {
                if (!empty($ar[1]))
                {
                    if (Files::FileExists(ROOT_PATH."settings_".$ar[0]."_".$ar[1].".php"))
                    {
                        include_once ROOT_PATH."settings_".$ar[0]."_".$ar[1].".php";
                        
                    }
                }
                if (Files::FileExists(ROOT_PATH."settings_".$ar[0].".php"))
                {
                    include_once ROOT_PATH."settings_".$ar[0].".php";
                }
            }
            if (!empty($ar[1]))
            {
                if (Files::FileExists(ROOT_PATH."settings_".$ar[1].".php"))
                {
                    include_once ROOT_PATH."settings_".$ar[1].".php";
                }
            }   
        }   
    }
    
    public static function GetIframeHtml($key)
    {
        return $_SESSION["iframe_$key"];
    }
    
    public static function GetWebRobots()
    {
        $web = new \Objects\Webs();
        $info = $web->GetRobotsTxt(SERVER_NAME_LANG);
        return $info;
    }
    
    public static function GetSitemap()
    {
        header('Content-type: application/xml');
        header("Content-length: 0");
        $web = new \Objects\Webs();
        $info = $web->GenerateSitemapXml(SERVER_NAME_LANG);
        return $info;
    }
}
