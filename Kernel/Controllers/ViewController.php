<?php

namespace Controller;

use Utils\Files;
use Utils\StringUtils;

class ViewController extends \Controller\Controllers {

    /** @var array */
    private $_viewPermition = array();
    
    /** @var array */
    private $_mustBeLang = array();
    
    /** @var array */
    private $_mustBeWebId = array();
    
    /** @var array */
    private $_ignoreUserPrivileges = array();
    
    /** @var bool */
    protected $TestUserPrivileges = true;
    
    /** @var array */
    private $_styles = array();
    
    
    /** @var array */
    private $_scripts = array();
    
    /** @var array */
    private $_commandFunctions = array();
    
    /** @var bool */
    public $ExitQuestion = false;
    
    /** @var string */
    public $SharedView = "";
    
    /** @var bool */
    public $LinkTestPrivileges = true;
    
    public function __construct() {
        parent::__construct();
        
    }
    protected function SetViewSettings($viewName, $permition, $mustBeWebId = false, $mustBeLangId = false) {
        $this->SetViewPermition($viewName, $permition);
        if ($mustBeLangId)
            $this->SetMustBeLang($viewName);
        if ($mustBeWebId)
            $this->SetMustBeWebId($viewName);
    }
    
    
    private function SetMustBeLang($viewName) {
        $this->_mustBeLang[] = $viewName;
    }
    
    
    public function MustBeLangId($viewName) {
        return in_array($viewName, $this->_mustBeLang);
    }
    
    
    private function SetMustBeWebId($viewName) {
        $this->_mustBeWebId[] = $viewName;
    }
    
    public function MustBeWebId($viewName) {
        return in_array($viewName, $this->_mustBeWebId);
    }
    
    private function SetViewPermition($viewName, $settings) {
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
            if (empty($settings)) {
                return false;
            }

            if (in_array("*", $settings))
                return true;
            $userGroupIdentificator = self::$User->GetUserGroupIdentificator();
            if (empty($userGroupIdentificator)) {
                return false;
            }
            if ($this->IsAdmin()) {

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
    
    protected function SetIgnoreUserPrivileges($function) {
        $this->_ignoreUserPrivileges[] = $function;
    }
    
    protected function IncludeState() {
        try {
            $stateName = $this->GetViewName();
            $controllerName = $this->GetControllerName();
            $path = VIEWS_PATH . TEMPLATEMODE . "/" . $controllerName . "/" . $stateName . ".html";
            if (!Files::FileExists($path))
                $path = VIEWS_PATH_PLUGINS . TEMPLATEMODE . "/" . $controllerName . "/" . $stateName . ".html";

            $this->SetTemplateData("stateName", $path);
        } catch (Exception $e) {
            \Kernel\Page::ApplicationError($ex);
        }
    }
    
    protected function GetViewName() {
        if (empty($_GET["View"]))
            return DEFAULT_VIEW_NAME;
        return $_GET["View"];
    }

    protected function IsView($view) {
        return $this->GetViewName() == $view;
    }
    
    protected function AddStyle($path) {
        $this->_styles[] = $path;
    }

    public function GetStyles() {
        return $this->_styles;
    }
    
    protected function GoToState($controller, $view, $prefix = "", $webid = 0, $langid = 0, $objectId = 0) {
        $url = $controller . "/" . $view . "/";

        if (!empty($prefix))
            $url = "/$prefix/" . $url . "/";

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
        
            $this->_scripts[] = $path;
    }

    public function GetScripts() {
        return $this->_scripts;
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

    

    public function IsCommandFunction($functionName) {
        return in_array($functionName, $this->_commandFunctions);
    }
    
    protected function SetStateTitle($title) {
        $this->SetTemplateData("stateTitle", $title);
    }
    
    protected function Referesch() {
        
        $this->Redirect("");
    }
    
    protected function CreateLink($controllerName, $viewName, $linkName, $prefix = "", $webId = 0, $langId = 0, $objectId = "") {
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
        $uig = new \Objects\UsersGroups();
        $otherGroups = $uig->GetMinorityUserGroup(self::$UserId);
        $module = new \Objects\Modules();
        if (!empty($otherGroups)) {
            foreach ($otherGroups as $group) {
                if (!$module->CanModuleShow($controllerName, $viewName, $group["GroupId"]))
                    return false;
            }
        }

        return $module->CanModuleShow($controllerName, $viewName, $groupId);
    }
    
    protected function PrepareList($stateTitle, $colums, $tableOut, $parentId = 0, $where = "") {

        $modelName = $tableOut->ModelName;
        $header = $tableOut->Header;

        if (!self::$SessionManager->IsEmpty("where", $modelName)) {
            $modelItem = self::$SessionManager->GetSessionValue("where", $modelName);
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

        if (empty($tableOut->Data) && !$tableOut->AceptEmptyData) {
            if (!empty($modelName)) {
                $modelName = "Model\\" . $modelName;
                $model = new $modelName();
                $data = null;
                $data = $model->Select($colums, FALSE, true, true, null, $where, true, $parentId);

                $tableOut->Data = $data;
            }
        }
        $tableOut->TemplateRow = $colums;
        foreach ($tableOut as $key => $value) {
            if (!is_array($value) && !is_object($value)) {

                if (strpos($value, "word") !== FALSE) {
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
    
    protected function GoToBack() {
        $this->Redirect($_SERVER['HTTP_REFERER']);
    }
    
    
    

}