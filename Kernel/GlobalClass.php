<?php

namespace Kernel;

use Model\Webs;
use Model\Users;
use Model\Langs;
use Model\UserDomainsValues;
use Model\WordGroups;
use Utils\Utils;
use Utils\Forms;

class GlobalClass {

    /** @var bool*/
    protected static $IsAjax = false;
    /** @var integer*/
    protected static $UserGroupId = 0;
    /** @var integer*/
    protected static $UserId = 0;
    /** @var array()*/
    protected static $OtherUserGroups = array();
    /** @var array()*/
    private static $_templateData = array();
    /** @var \Model\Langs*/
    private static $_lang = null;
    /** @var \Objects\Webs*/
    private static $_web = null;
    /** @var array()*/
    private static $_langInfo = array();
    /** @var bool*/
    private static $_pageRedirects = true;
    /** @var bool*/
    private static $_ipRestriction = true;
    /** @var string*/
    private static $_javascriptFramework = "";
    /** @var bool*/
    protected static $IsCookiesAccept = true;
    /** @var bool*/
    private static $_prepareWords = true;
    /** @var string*/
    protected static $SelectLang = "";
    /** @var \Objects\Users  */
    protected static $User;
    /** @var array */
    private static $_dataRequest = array();
    /** @var bool*/
    private static $_callOtherGroups = true;
    /** @var int*/
    public $WebId = 0;
    /** @var int*/
    public $LangId = 0;
    /** @var bool*/
    protected $IsLogin = false;
    /** @var bool*/
    protected $IsPostBack = false;
    /** @var bool*/
    protected $IsGet = false;
    /** @var bool*/
    protected $IsFrontend = false;
    /** @var string */
    protected $ArticleUrl;
    /** @var bool*/
    protected $test = false;
    /**  @var  \Utils\SessionManager*/
    protected static $SessionManager = null;
    /** @var array*/
    private static $_wordList = array();

    public function __construct() {
        
         if (!empty($_GET["ajax"])) {
            if ($_GET["ajax"] == "ajax")
                self::$IsAjax = TRUE;
        }
        
        
        if (!self::$IsAjax) {
            $this->IsFrontend = empty($_GET) || !empty($_GET["seourl"]) || !empty($_GET["renderHtml"]) || !empty($_GET["lang"]) || !empty($_GET["caching"]) || !empty($_GET["xml"]) ? true : false;
        } else {
            $this->IsFrontend = empty($_GET["isFrontEnd"]) ? true : $_GET["isFrontEnd"] == "false" ? false : true;
        }
        
        if (self::$SessionManager == null)
        {
            if ($this->IsFrontend)
            {
                self::$SessionManager = new \Utils\SessionManager(\Utils\SessionManager::$WebMode);
            }
            else 
            {
                self::$SessionManager = new \Utils\SessionManager(\Utils\SessionManager::$AdminMode);
            }
        }
        
        
        if (self::$_lang == null)
            self::$_lang = new \Objects\Langs ();
        
        if (self::$_web == null)
        {
            self::$_web = new \Objects\Webs();
        }           
        if (self::$User == null)
        {
            self::$User =  new \Objects\Users();
        }
        if (self::$UserGroupId == 0)
        {
            self::$UserGroupId = self::$User->GetUserGroupId();
        }
        
        $this->IsLogin = self::$User->IsLoginUser();
        if (self::$UserId == 0)
        {
            self::$UserId = self::$User->GetUserId();
        }
        
        if ($this->IsLogin) {
            if (empty(self::$OtherUserGroups) && self::$_callOtherGroups) {
                self::$_callOtherGroups = false;
                self::$OtherUserGroups = self::$User->GetOtherUserGroups();
            }
        }
      
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
            $this->IsPostBack = true;
        }
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {
            $this->IsGet = true;
        }

        
        
        
        

        if (!self::$IsAjax) {
            
            if (!$this->IsFrontend) {
                $this->GetLang();
                if (!empty($_GET["webid"]))
                    $this->WebId = $_GET["webid"];
                if (!empty($_GET["langid"]))
                    $this->LangId = $_GET["langid"];
            }
            else {
                
                $web = SERVER_NAME_LANG;
                
                if (!empty($_GET["seourl"]))
                    $this->ArticleUrl = SERVER_NAME_LANG . $_GET["seourl"] . "/";
                else
                    $this->ArticleUrl = SERVER_NAME_LANG;
                
                $this->PageRedirect($this->ArticleUrl);
                
                if (empty(self::$_langInfo))
                    self::$_langInfo = self::$_lang->GetWebInfo($web);
                if (!empty(self::$_langInfo)) 
                {
                    
                    
                        
                    $this->WebId = self::$_langInfo[0]["WebId"];
                    $this->LangId = self::$_langInfo[0]["Id"];
                    $this->PrepareWords(self::$_langInfo[0]["LangIdentificator"]);
                    self::$SelectLang = self::$_langInfo[0]["LangIdentificator"];
                } else {
                    http_response_code(404);
                    throw \Types\xWebExceptions::$NoUrlLangExists;
                } 
            }
        } else {
            if (!empty($_GET["webid"]))
                $this->WebId = $_GET["webid"];
            if (!empty($_GET["langid"]))
                $this->LangId = $_GET["langid"];
        }

        self::$_web->SetWebInfo($this->WebId);
        if (!self::$IsAjax) {
            if ($this->IsFrontend) {
                $this->IpRestriction("web");
            } else {
                $this->IpRestriction("admin");
            }
        }
        

        $ar["ajaxLinks"] = self::$_web->AjaxLinkLoad();
        $ar["FrameworkMode"] = self::$_web->JavascriptFrameworkMode();
        self::$_javascriptFramework = $ar["FrameworkMode"];

        $this->SetTemplateDataArray($ar);
        
        if (empty(self::$_javascriptFramework)) {
            self::$_javascriptFramework = self::$_web->JavascriptFrameworkMode($this->WebId);
        }
        $this->IsCookiesAccept();
        
    }

    public function IsLoginUser() {
        return $this->IsLogin;
    }

    public function UseHttps() {
        return self::$_web->UseHttps();
    }

    protected function GoHome() {
        if (!self::$IsAjax)
            $this->Redirect(SERVER_NAME_LANG);
    }

    protected function Redirect($url) {
        if (empty($url))
            return;
        header("Location: " . $url);
        exit;
    }

    
    public function IsHttps() {
        return stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true;
    }

    public function HttpsRedirect() {
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "") {
            $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $redirect");
        }
    }

    private function PageRedirect($url) {
        if (self::$_pageRedirects) {
            self::$_pageRedirects = false;
            $udv = new \Objects\UserDomains();
            $res = $udv->GetDomainValueConditon("Redirects", 0, "OldUrl", $url);
            if (empty($res))
                return;
            $resNew = $udv->GetDomainValueConditon("Redirects", $res[0]["ObjectId"], "NewUrl");
            if (!empty($resNew)) {
                $this->Redirect($resNew[0]["Value"]);
            }
        }
    }

    private function IpRestriction($mode) {
        if (self::$_ipRestriction) {
            self::$_ipRestriction = false;
            $web = self::$_web->GetIpRestriction();
            $ip = "";
            $action = "";
            if ($mode == "web") {
                if ($web["WebIpRestrictionAll"]) {
                    
                } else if ($web["WebIpRestrictionAceptIp"]) {
                    $action = "accept";
                    $ip = $web["WebIpAddress"];
                } else if ($web["WebIpRestrictionBlockIp"]) {
                    $action = "block";
                    $ip = $web["WebIpAddress"];
                }
            } else if ($mode == "admin") {
                if ($web["AdminIpRestrictionAll"]) {
                    
                } else if ($web["AdminIpRestrictionAceptIp"]) {
                    $action = "accept";
                    $ip = $web["AdminIpAddress"];
                } else if ($web["WebIpRestrictionBlockIp"]) {
                    $action = "block";
                    $ip = $web["AdminIpAddress"];
                }
            }
            $arIp = explode(",", $ip);
            $userIp = Utils::GetIp();
            if (($action == "accept" && !in_array($userIp, $arIp)) || ($action == "block" && in_array($userIp, $arIp))) {
                http_response_code(404);
            }
        }
    }

    protected function ParseInt($value) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    protected function ServerMap($path) {
        $path = ROOT_PATH . $path;
        $path = str_replace("//", "/", $path);
        return $path;
    }
    
   
    protected function SetTemplateDataArray($ar) {
        self::$_templateData = array_merge(self::$_templateData, $ar);
    }

    

    protected function IsJquery() {
        if (empty(self::$_javascriptFramework)) {
            self::$_javascriptFramework = $this->GetJavascriptFramework();
        }
        return self::$_javascriptFramework == "jquery" ? true : false;
    }

    private function GetJavascriptFramework() {

        return self::$_web->JavascriptFrameworkMode($this->WebId);
    }

    public function IsFrontEnd() {
        return $this->IsFrontend;
    }

    public function IsAdmin() {
        return $this->IsFrontend ? false : true;
    }

    protected function GetUserDomain($domainIdentificator, $dataId = 0, $addDomainIdentificator = true, $data = "", $disabled = false) {
        
        $form = new \Kernel\Forms();
        return $form->GetUserDomain($domainIdentificator, $dataId, $addDomainIdentificator, $data, $disabled);
    }

    protected function SetTemplateData($key, $value) {
        self::$_templateData[$key] = $value;
    }
    
    protected function GetTemplateValue($key)
    {
        if (empty(self::$_templateData[$key])) return "";
        return self::$_templateData[$key];
    }


    public function GetTemplateData() {
        return self::$_templateData;
    }



    private function IsCookiesAccept() {
        if ($this->CookiesAccept()) {

            if (empty($_COOKIE["cookiesAccept"])) {
                self::$IsCookiesAccept = false;
            }
        }
    }

    private function CookiesAccept() {
        return self::$_web->MustBeCookiesAccept();
    }

    public function GetFullUserName() {
        return self::$User->GetFullUserName();
    }

    public function GetUserName() {
        return self::$User->GetUserName();
    }

    public function SetDataRequest($key, $value) {
        self::$_dataRequest[$key] = $value;
    }

    public function GetDataRequest($key) {
        return self::$_dataRequest[$key];
    }
    
    public function GetUserGroupId()
    {
        return self::$UserGroupId;
    }
    
    protected function CallUrl($url,$get="",$returnData = false)
    {
        if(!empty($get))
        {
            $url = $url.$get;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = "";
        if ($returnData)
            $data = curl_exec($ch);
        curl_close($ch);
        if ($returnData)
        {
            return $data;
        }
    }
    
    public function GetIfrmaesHtml($key)
    {
        
        
        
        
    }
    
    // WORDS - přesunout do PAGE.PHP
     protected function GetAutoLang() {
        $lang = self::$SessionManager->GetSessionValue("AdminUserLang");
        if (empty($lang))
            $lang = "CS";
        self::$SelectLang = $lang;
        $this->PrepareWords($lang);
    }

    protected function GetLang() {

        if (self::$SessionManager->IsEmpty("AdminUserLang"))
        {
            $this->GetAutoLang();
        }
        return self::$SessionManager->GetSessionValue("AdminUserLang");
    }

    public function GetWord($wordid) {
        $lang = $this->GetLang();
        return self::$SessionManager->IsEmpty("AdminWords$lang",$wordid) ? "" : self::$SessionManager->GetSessionValue("AdminWords$lang",$wordid);
        
    }

    protected function PrepareWords($lang) {
        if (self::$_prepareWords) {
            
            self::$SessionManager->SetSessionValue("AdminUserLang", $lang);self::$SessionManager->SetSessionValue("AdminUserLang", $lang);
            if (empty($lang))
                return;
            $word =  WordGroups::GetInstance();
            $langName = "Word".$lang;
            if (!$word->ColumnExists($langName))
            {
                $lang = "CS";
                $langName = "Word".DEFAULT_LANG;
                self::$SessionManager->SetSessionValue("AdminUserLang", "CS");
                
            }
            $outArray = array();
            /*if (!self::$SessionManager->IsEmpty("AdminWords$lang"))
                return self::$SessionManager->GetSessionValue("AdminWords$lang");*/
                $columns = array("GroupName",$langName,"WordCS");
                $wordList = $word->Select($columns, false, false, false);
                
                foreach ($wordList as $row) {
                    $key = trim($row["GroupName"]);
                    $value = $row["Word" . $lang];
                    $value = trim($value);
                    if (empty($value))
                    {
                        $value = $row["WordCS"];
                        
                    }
                    $outArray[$key] = $value;
                }
                self::$_wordList = $outArray;
                self::$SessionManager->SetSessionValue("AdminWords$lang",$outArray);
                
            $this->SetTemplateDataArray($outArray);
            self::$_prepareWords = false;
            
            return $outArray;
        }
    }
    
    public function GetWordList()
    {
        return self::$_wordList;
    }
    
    protected function SetWordList() {
        $words = WordGroups::GetInstance();
        $wordList = $words->Select();
        $this->SetTemplateData("WordList", $wordList);
        $adminLang = $this->GetLang();

        foreach ($wordList as $row) {
            $row["ShowLang"] = $row["Word" . $adminLang];
        }
    }

    

}
