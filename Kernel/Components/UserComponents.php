<?php
namespace Components;
use Kernel\GlobalClass; 
use Types\CacheTimeType;
use Utils\Files;
use Utils\Utils;
use Utils\ArrayUtils;
use Utils\StringUtils;
use Types\LinkType;
use Types\CacheType; 

class  UserComponents extends GlobalClass {
  
    public $Template;
    public $ItemTemplate ="";
    public $Type;
    public $IsCache = true;
    public $Id;
    public $UseBootstrap = true;
    public $CssClass = "";
    public $LinkJavascript = false;
    public $Visible = true;
    public $UseExteralCookies = false; 
    public $LoadHtmlFromFile = true;
    public $VariantFileName = "";
    public $DataSource = "";
    public $Limit;
    public $LoadSubitems = true;
    public $IsEmptyComponent = false;
    public $CheckAlternativeContent =false;
    public $LimitLevelLoad = 1;
    public $ActiveLevel = 0;
    public $Where = "";
    public $WhereColumns = "";
    public $LoadFirstLevel = true;
    public $ItemDelimiter = " | ";
    public $DatasourceIdentificator = "";
    private static $_dataSources = array(); 
    public $SharedDataSource = "";
    
    protected $InsertJavascriptToContent = false;
    protected $AutoReplaceString = false;
    protected $CacheBySeoUrl = false;
    protected $RenderHtml;
    protected $LoadHtml = false;
    protected  $IgnoreCache = false;  
    protected $UseItemTemplate = false;
    protected $ItemHtml="";
    protected $UseDataSource = false;
    private $_replaceStrings =array();
    private $_componentScripts= array();
    private $_componentCss = array();
    private $_itemFileName = "";
    
    
    
    
    public function __construct() {
        
        parent::__construct();
        
        
        
    }

    
    public function LoadComponent($obj = null) {
        
        
        if ($obj == null)
            $obj = $this;
        $html = "";
        
        if ($this->UseExteralCookies && self::$IsCookiesAccept)
            $this->Visible = false;
        
        if ($this->IgnoreCache)
        {
            $this->IsCache = false;
        }
        if (!$this->Visible)
            return $html;
        
         
        if ($this->LoadHtml)
        {
            $this->LoadTemplateHtml();
        }
        
        if ($this->UseItemTemplate)
        {
            $this->LoadItemTemplateHtml();
        }
        
        if (!empty($this->VariantFileName))
        {
            $functionName = $this->VariantFileName."GetComponentHtml";
            if (method_exists($obj, $functionName))
            {
                $html = $this->$functionName();
            }
                
        }
        
        $html = $this->GetComponentHtml();
            
        if (!$this->Visible)
            return "";
        
        if (empty($html))
            $html = $this->RenderHtml;
        
        
        
        if ($this->AutoReplaceString)
        {
            $this->_replaceStrings = \Utils\ArrayUtils::AddReplaceCharsToKey($this->_replaceStrings);
            $html = preg_replace(array_keys($this->_replaceStrings),$this->_replaceStrings, $html);
        }
        
        
        if ($this->InsertJavascriptToContent)
        {
            $script = "";
            if (Files::FileExists("Scripts/Components/".$this->Type.".js"))
            {
                $script = Files::ReadFile("Scripts/Components/".$this->Type.".js");
            }
            $html = "<script type=\"text/javascript\">$script</script>\n" .$html;
        }
        return $html;
    }
    
    private function LoadTemplateHtml()
    {
        if (!empty($this->Template))
        {
            $content =  new \Objects\Content();
            $template = $content->LoadTemplateByIdentificator($this->Template, self::$UserGroupId, $this->LangId, $this->WebId);
            if (!empty($template))
                $this->RenderHtml = $template["data"];
        }
        if (empty($this->RenderHtml))
        {
            $fileName = $this->Type.trim($this->VariantFileName).".html";
            if (Files::FileExists(COMPONENTS_PATH.$fileName))
            {
                $this->RenderHtml =   Files::ReadFile(COMPONENTS_PATH.$fileName);
            }
            if (Files::FileExists(COMPONENTS_PATH.$this->Type."/". $fileName))
            {
                $this->RenderHtml =   Files::ReadFile(COMPONENTS_PATH.$this->Type."/".$fileName);
            }
            if (Files::FileExists(COMPONENTS_PATH_PLUGINS.$fileName))
            {
                $this->RenderHtml =  Files::ReadFile(COMPONENTS_PATH_PLUGINS.$fileName);
            }
        }
    }
    
    private function LoadItemTemplateHtml()
    {
        $this->_itemFileName = $this->Type.trim($this->VariantFileName)."_item";
        if (!empty($this->ItemTemplate))
        {
            
            $content = new \Objects\Content();
            $template = $content->LoadTemplateByIdentificator($this->ItemTemplate, self::$UserGroupId, $this->LangId, $this->WebId);
            $this->ItemHtml = $template["data"];
        }
        
        if (empty($this->ItemHtml))
        {
            $fileName = $this->_itemFileName.".html";
            if (Files::FileExists(COMPONENTS_PATH.$fileName))
            {
                $this->ItemHtml =   Files::ReadFile(COMPONENTS_PATH.$fileName);
            }
            if (Files::FileExists(COMPONENTS_PATH.$this->Type."/".$fileName))
            {
                $this->ItemHtml =   Files::ReadFile(COMPONENTS_PATH.$this->Type."/".$fileName);
            }
            if (Files::FileExists(COMPONENTS_PATH_PLUGINS.$fileName))
            {
                $this->ItemHtml =  Files::ReadFile(COMPONENTS_PATH_PLUGINS.$fileName);
            }
        }
    }
    
    protected function SetReplaceString ($key,$value)
    {
        $this->_replaceStrings[$key] = $value;
    }
    
    protected function SetReplaceStringArray ($array)
    {
        $this->_replaceStrings = array_merge($this->_replaceStrings,$array);
    }
    
    public function GetReplaceString()
    {
        return $this->_replaceStrings;
    }
    
    protected function ReplaceItems($items)
    {
        $html ="";
        if (!empty($items))
        {
            $html = $this->PrepareHtml($this->ItemHtml, $items);
        }
        $this->SetReplaceString($this->_itemFileName, $html);
        return $html;
    }
    
    protected  function RenderHiddenInput($id,$value)
    {
        $input = new \HtmlComponents\HiddenInput();
        $input->Value = $value;
        $input->Id = $id;
        $inputHtml= $input->RenderHtml();
        $this->SetReplaceString($id, $inputHtml);
    }


    protected function SetUsedWords($wordid)
    {
        $value = $this->GetWord($wordid);
        $this->SetReplaceString($wordid, $value);
    }
    
    protected function AddMoreScript($path)
    { 
        $this->_componentScripts[] = $path;
    }
    
    protected function AddMoreCss($path)
    { 
        $this->_componentCss[] = $path;
    }
    
    public function GetOtherScripts()
    {
        return $this->_componentScripts; 
    }
    
    public function GetOtherCss()
    { 
        return $this->_componentCss;
    }
    
    public function InsertJavascriptToContent()
    {
        return $this->InsertJavascriptToContent;
    }
    
    protected  function GetDataSource($id = 0)
    {
        if (!empty($this->SharedDataSource) && !empty(self::$_dataSources[$this->SharedDataSource]))
        {
            return self::$_dataSources[$this->SharedDataSource];
        }
        $frontedContent = new \Objects\ContentFrontend();
        $frontedContent->Id = $id;
        $frontedContent->DataSource = $this->DataSource;
        $frontedContent->CheckAlternativeContent = $this->CheckAlternativeContent;
        $frontedContent->Limit = $this->Limit;
        $frontedContent->LoadSubItems = $this->LoadSubitems;
        $frontedContent->LangId = $this->LangId;
        $frontedContent->WebId  = $this->WebId;
        $frontedContent->UserGroupId = self::$UserGroupId;
        $frontedContent->LimitLevelLoad = $this->LimitLevelLoad;
        $frontedContent->Where = $this->Where;
        $frontedContent->LoadFirstLevel = $this->LoadFirstLevel;
        $frontedContent->WhereColumns = $this->WhereColumns;
        $data = $frontedContent->LoadContent(0,$this->ActiveLevel);
        if (!empty($this->Id))
        {
            self::$_dataSources[$this->Id] = $data;
        }
        return $data;
    }
    
    protected function PrepareHtml($template, $data) {
        if (empty($data))
        {
            $this->Visible = false;
            return "";
        }
        
        if (empty($template) && !empty($data[0]["TemplateId"])) {
            
            $content =  new \Objects\Content();
            $template = $content->LoadTemplateById($data[0]["TemplateId"], self::$UserGroupId, $this->LangId, $this->WebId);
            if(!empty($template))
            {
                $template = $template["data"];
                $this->RenderHtml = $template;
            }
        }
        $contentObj = new \Objects\ContentFrontend();
        return $contentObj->PrepareHtml($template, $data);
    }
    
   
}