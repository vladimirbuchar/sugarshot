<?php
namespace Controller;
use Utils\Files;
use Kernel\GlobalClass;
use Model\Langs;
use Model\AdminLangs;
use Model\UsersInGroup;
use Model\Modules;
use Utils\StringUtils; 
use Model\UserDomainsValues; 
use Types\DomainData;
class Controllers extends GlobalClass{

    /** @var string */
    protected $ControllerName = "";
    /** @var string */
    public $SharedView = "";
    /** @var bool */
    public $LinkTestPrivileges = true;
    /** @var bool */
    protected $TestUserPrivileges = true;
    /** @var bool */
    public $ExitQuestion = false;
    /** @ar array */
    private $_styles = array();
    /** @ar array */
    private $_scripts = array();
    /** @ar array */
    private $_ajaxFunctions = array();
    /** @ar array */
    private $_commandFunctions = array();
    /** @ar array */
    private $_controllerPermition = false;
    /** @ar array */
    private $_viewPermition = array();
    /** @ar array */
    private $_mustBeWebId = array();
    /** @ar array */
    private $_mustBeLang = array();
    /** @ar array */
    private $_ignoreUserPrivileges = array();
    /** @var bool */
    private $_noAccess = false;
    
 
    
    public function __construct() {
        parent::__construct();
        
        $this->ControllerName = $this->GetControllerName();
        
    
    }
    #SECURITY 
    /**
     * this function is testing blocking web administration 
     */
    protected function BlockAdmin()
    {
        $lang =  Langs::GetInstance();
        if($lang->BlockAdmin(SERVER_NAME))
        {
            self::$User->UserLogout();
            $this->GoHome();
        }
    }
    # SECURITY END 
    
    # VIEWS SETTING 
    
    #
    
    
    
    
    protected function SetNoAccess($value)
    {  
        $this->_noAccess  = $value;
    }
    
    public function GetNoAccess()
    {
        return $this->_noAccess;
    }
     
    protected function SetMustBeWebId($viewName) {
        $this->_mustBeWebId[] = $viewName;
    }

    protected function SetIgnoreUserPrivileges($function) {
        $this->_ignoreUserPrivileges[] = $function;
    }

    protected function SetMustBeLang($viewName) {
        $this->_mustBeLang[] = $viewName;
    }

    public function MustBeLangId($viewName) {
        return in_array($viewName, $this->_mustBeLang);
    }

    public function MustBeWebId($viewName) {
        return in_array($viewName, $this->_mustBeWebId);
    }

    protected function SetTemplateDataXml($xmlString)
    {
        $xml = simplexml_load_string($xmlString);
        foreach ($xml as $key => $value)
        {
            $this->SetTemplateData($key, $value);
        }
    }

    protected function IncludeState() {
        try{
            $stateName = $this->GetViewName();
            $controllerName = $this->GetControllerName();
            $path = VIEWS_PATH .TEMPLATEMODE."/". $controllerName . "/". $stateName . ".html";
            if (!Files::FileExists($path))
                $path = VIEWS_PATH_PLUGINS .TEMPLATEMODE."/". $controllerName . "/" . $stateName . ".html";
            
            $this->SetTemplateData("stateName", $path);
            
            
        }
        catch (Exception $e)
        {
            \Kernel\Page::ApplicationError($ex);
        }
    } 

    protected function GetViewName() {
        if (empty($_GET["View"]))
            return DEFAULT_VIEW_NAME;
        return $_GET["View"];
    }
    
    protected function IsView($view)
    {
        return $this->GetViewName() == $view;
    }

    protected function GetControllerName() {
        
        if (empty($_GET["Controller"]))
        {
            return DEFAULT_CONTROLER_NAME;
        }
        return $_GET["Controller"];
    } 

    protected function AddStyle($path) {
        if (!self::$IsAjax)
            $this->_styles[] = $path;
    }

    public function GetStyles() {
        return $this->_styles;
    }

    protected function GoToState($controller, $view, $prefix = "", $webid = 0, $langid = 0, $objectId = 0) {
        $url = $controller . "/" . $view . "/";
        
        if (!empty($prefix))
            $url = "/$prefix/" . $url."/";
        
        if ($webid > 0)
            $url = $url . $webid . "/";
        
        if ($langid > 0)
            $url = $url . $langid . "/";
        
        if ($objectId > 0)
            $url = $url . $objectId . "/";
        
        if (!StringUtils::StartWidth($url, "/"))
                $url = "/$url";
        
        $this->Redirect($url);
    }

    public function AddScript($path) {
        if (!self::$IsAjax)
            $this->_scripts[] = $path;
    }

    public function GetScripts() {
        return $this->_scripts;
    }

    protected function SetAjaxFunction($functioName, $settings = array()) {
        if (!empty($settings)) {
            if (!in_array("*", $settings)) {
                $userGroupIdentificator = self::$User->GetUserGroupIdentificator();
                if (empty($userGroupIdentificator))
                    return;
                if (!in_array($userGroupIdentificator, $settings))
                    return;
            }
            $this->_ajaxFunctions[] = $functioName;
        }
    }

    protected function SetCommnadFunction($functionName, $settings = array()) {
        if (!empty($settings)) {
            if (!in_array("*", $settings)) {
                $userGroupIdentificator = self::$User->GetUserGroupIdentificator();
                if (empty($userGroupIdentificator))
                    return;
                if (!in_array($userGroupIdentificator, $settings))
                    return;
            }
            $this->_commandFunctions[] = $functionName;
        }
    }

    public function IsAjaxFunction($functionName) {
       return in_array($functionName, $this->_ajaxFunctions);
    }

    public function IsCommandFunction($functionName) {
        return in_array($functionName, $this->_commandFunctions);
    }

    protected function PrepareAjaxParametrs($params = null) {
        if ($params == null)
        {
            if (!empty($_GET["params"]))
                $params =$_GET["params"];
            else if (!empty($_POST["params"]))
                $params =$_POST["params"];
            else if ($_COOKIE["params"])
            {
                $params =$_COOKIE["params"];
                unset($_COOKIE["params"]);
                return $params;
            }
            
            
            if (empty($params))
                return;
        }
        
        $outArray = array();
        for ($i = 0; $i < count($params); $i++) {
            if (empty($params[$i]))
                continue;
            $id = $params[$i][0];
            if (StringUtils::EndWith($id, "__ishtmleditor__"))
            {
                $id = StringUtils::RemoveString($id, "__ishtmleditor__");
                $id = StringUtils::RemoveLastChar($id, 5);
            }

            $value = empty($params[$i][1])? "":$params[$i][1];
            
            if (!array_key_exists($id, $outArray))
                $outArray[$id] = $value;
            else {
                if (is_array($outArray[$id])) {
                    $outArray[$id][] = $value;
                } else {
                    $outArray[$id] = array($outArray[$id], $value);
                }
            }
        }
        return $outArray;
    }
    protected function SetStateTitle($title) {
        $this->SetTemplateData("stateTitle", $title);
    }
  
    protected function SaveUserDomain($data)
    {
        $objectId = 0;
        $domainName ="";
        
        $domainData = array();
        foreach ($data as $key => $value)
        {
            $valueId = 0;
            if (strpos($key,"ObjectId_") !== false)
            {
                $objectId = $value;
            }
            else if (strpos($key,"DomainIdentificator_") !== false)
            {
                
                $domainName = $value;
            }
            else
            {
                $ar = explode("_", $key);
                if (StringUtils::StartWidth($key,"checkbox_"))
                {
                    $key = $ar[2];
                    $value = $ar[1];
                    $domainData[] = new DomainData($key,$valueId,$value);
                }
                else if (!empty($ar[2]))
                {
                    $key = $ar[1];
                    $valueId = $ar[2];
                    $domainData[] = new DomainData($key,$valueId,$value);
                }
                else 
                {
                    $key = $ar[1];
                    $valueId = 0;
                    $domainData[] = new DomainData($key,$valueId,$value);
                }
                
            }
            
        }
        //print_r($domainData);die();
        
        $domainValue =  UserDomainsValues::GetInstance();
        $domainValue->SaveDomainValue($domainName, $objectId, $domainData);
    }
    
    protected function SetControllerPermition($settings) {
        if (empty($settings)) {
            $this->_controllerPermition = false;
        } else {

            if (in_array("*", $settings))
                $this->_controllerPermition = true;
            else {
                $userGroupIdentificator = self::$User->GetUserGroupIdentificator();
                if (empty($userGroupIdentificator))
                    $this->_controllerPermition = false;
                else {

                    if (in_array($userGroupIdentificator, $settings))
                        $this->_controllerPermition = true;
                    else
                        $this->_controllerPermition = false;
                }
            }
        }
    }

    public function GetControllerPermition() {
        return $this->_controllerPermition;
    }

    protected function SetViewPermition($viewName, $settings) {
        $this->_viewPermition[$viewName] = $settings;
    }

    public function GetViewPermition($viewName, $controllerName = "") {
        try {
            // temporary sugarshot
            return true;
            
            if (empty($controllerName))
                $controllerName = $this->GetControllerName();
            if (!array_key_exists($viewName, $this->_viewPermition)) {
                return false;
            }
            
            $settings = $this->_viewPermition[$viewName];
            if (empty($settings))
            {
                return false;
            }
            
            if (in_array("*", $settings))
               return true;
            $userGroupIdentificator = self::$User->GetUserGroupIdentificator();
            if (empty($userGroupIdentificator)) {
                return false;
            }
            if ($this->IsAdmin())
            {
                
                if (in_array($userGroupIdentificator, $settings)) {
                    if ($this->CheckUserPermition($controllerName, $viewName, self::$User->GetUserGroupId()) || in_array($viewName, $this->_ignoreUserPrivileges) || !$this->TestUserPrivileges) {
                        return true;
                    }
                    
                    return false;
                }
            }
            
        } catch (Exception $ex) {
            return FALSE;
        }

        return false;
    }

    

    protected function Referesch() {
        if (self::$IsAjax) return;
        $this->Redirect("");
    }

    protected function CreateLink($controllerName, $viewName, $linkName, $prefix = "", $webId = 0, $langId = 0, $objectId ="") {
        $xLink = new \Components\xLink();
        $xLink->Controller = $controllerName;
        $xLink->View = $viewName;
        $xLink->Text = $linkName;
        $xLink->Prefix = $prefix;
        $xLink->ObjectId = $objectId;
        return $xLink->GetComponentHtml();
    }

    

    public function CheckUserPermition($controllerName, $viewName, $groupId = 0) {
        $canShow = true;
        if ($groupId == 0)
            $groupId = self::$User->GetUserGroupId();
        $uig = UsersInGroup::GetInstance();
        $otherGroups = $uig->GetMinorityUserGroup(self::$UserId);
        $module = Modules::GetInstance();
        if (!empty($otherGroups))
        {
            foreach ($otherGroups as $group)
            {
                if (!$module->CanModuleShow($controllerName, $viewName, $group["GroupId"]))
                        return false;
            }
        }
        
        return $module->CanModuleShow($controllerName, $viewName, $groupId);
    }

    protected function PrepareList($stateTitle, $colums, $tableOut,$parentId =0,$where = "") {
        
        $modelName = $tableOut->ModelName;
        $header = $tableOut->Header;
        
        if (!self::$SessionManager->IsEmpty("where",$modelName)) {
            $modelItem = self::$SessionManager->GetSessionValue("where",$modelName);
            if ($modelItem != "clear") {
                for ($i = 0; $i < count($modelItem); $i++) {
                    $colName = $modelItem[$i][0];
                    foreach ($header as $head) {
                        if ($colName == $head->ColumnName) {
                            if (!empty($modelItem[$i][1]))
                                $head->Value1 = $modelItem[$i][1];
                            $i++;
                            if (!empty($modelItem[$i][1]))
                                $head->Value2 = $modelItem[$i][1];
                            $i++;
                            if (!empty($modelItem[$i][1]))
                                $head->Value3 = $modelItem[$i][1];
                        }
                    }
                }
            }
        }
        $this->SetStateTitle($stateTitle);
        
        if (empty($tableOut->Data) && !$tableOut->AceptEmptyData)
        {
            if (!empty($modelName))
            {
                $modelName = "Model\\".$modelName;
                $model = new $modelName();
                $data = null;
                $data = $model->Select($colums, FALSE, true, true, null, $where, true,$parentId);
                
                $tableOut->Data = $data;
            }
        }
        $tableOut->TemplateRow = $colums;
        foreach ($tableOut as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                
                if (strpos($value, "word") !== FALSE)
                {
                    $tmp = $value;
                    $value = $this->GetWord($value);
                    if (empty($value))
                        $value = $tmp;                            
                }
            }
            $this->SetTemplateData($key, $value);
        }
        $this->SetTemplateData("Table", $tableOut->GetComponentHtml());   
    }
    protected function  GoToBack()
    {
        $this->Redirect($_SERVER['HTTP_REFERER']);
    }
    
    protected function GetAdminLangs()
    {
        $adminLang = AdminLangs::GetInstance();
        $adminLangData= $adminLang->Select();
        foreach ($adminLangData as $row)
        {
            $row["selected"] ="";
            if (self::$SelectLang == $row->LangIdentificator)
            {
                $row["selected"] = "selected =\"selected\"";
            }
        }
        $this->SetTemplateData("AdminLang", $adminLangData);
    }
    
    protected function CheckWebPrivileges($viewName ="")
    {
        
        if (empty($viewName) || $this->GetViewName() == $viewName)
        {
            if ($this->WebId > 0)
            {
                $web = new \Objects\Webs();
                if (!$web->CheckWebPrivileges(self::$UserGroupId, $this->WebId))
                {
                    $this->SetNoAccess(true);
                }
            }
        }
    }
    
    
    protected function GetRoorUrl()
    {
        $langItem = Langs::GetInstance();
        return $langItem->GetRootUrl($this->LangId);
    }
    
    protected function ReplaceHtmlWord($data)
    {
        foreach ($data as $row)
        {
            foreach ($row as $key=>$value)
            {
                if (StringUtils::StartWidth($value, '<!--{$') && StringUtils::EndWith($value, '}-->'))
                {
                    $value = StringUtils::RemoveString($value, '<!--{$');
                    $value = StringUtils::RemoveString($value, '}-->');
                    $value = $this->GetWord($value);
                    $row[$key] = $value;
                }
            }
        }
        return $data;   
    }
    
    protected function SetViewSettings($viewName,$permition,$mustBeWebId = false,$mustBeLangId = false)
    {
        $this->SetViewPermition($viewName, $permition);
        if ($mustBeLangId)
            $this->MustBeLangId($viewName);
        if ($mustBeWebId)
            $this->MustBeWebId ($viewName);
    }
    
    
            
    
    
    
    
    
    

}
