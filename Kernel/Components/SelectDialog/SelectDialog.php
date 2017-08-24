<?php
namespace Components;
use HtmlComponents\Iframe;
class SelectDialog extends UserComponents{
    
    public $Id = "";
    public $CssClass = "SelectDialog";
    public $SelectFirstTab = false;
    public $ShowUserItemsTab = false;
    public $ShowFileRepositoryTab = false;
    public $ShowLinkTab = false;
    public $ShowFormTab = false;
    public $ShowJavascriptEvent = false;
    public $ShowPrivileges = false;
    public $IgnoreId = 0;
    private $_firstTabName = "";
    
    
    public function __construct($showUserItemsTab=false,$showFileRepositoryTab=false,$showLinkTab=false,$showForm = false,$showJavascript = FALSE,$showPrivileges= FALSE) {
        
        $this->Type="SelectDialog";
        $this->ShowFileRepositoryTab = $showFileRepositoryTab;
        $this->ShowUserItemsTab = $showUserItemsTab;
        $this->ShowLinkTab = $showLinkTab;
        $this->ShowFormTab = $showForm;
        $this->ShowJavascriptEvent = $showJavascript;
        $this->ShowPrivileges = $showPrivileges;
        $this->LoadHtml = true;
        $this->IgnoreCache = true;
        $this->AutoReplaceString = true;
        parent::__construct();
        
    }
    
    public function GetComponentHtml() {
        $fullHtml ="";
        if (empty($this->Id))
        {
            $this->Id = \Utils\StringUtils::GenerateRandomString();
        }
        $this->SetUsedWords("word308");
        $this->SetUsedWords("word309");
        $this->SetUsedWords("word310");
        $this->SetUsedWords("word311");
        $this->SetUsedWords("word677");
        $this->SetUsedWords("word723");
        $this->SetUsedWords("word312");
        $this->SetUsedWords("word313");
        $this->SetUsedWords("word678");
        $this->SetReplaceString("tree", "");
        $this->SetReplaceString("DialogId", $this->Id);
        $contentVersion =  new \Objects\Content();
        
        if ($this->ShowUserItemsTab)
        {
            
            $this->ShowTab("tab1");
            
            $tree = $contentVersion->GetTree($_GET["langid"]);
            $dataTree = $contentVersion->CreateHtml($tree,true,"",false,true,false,$this->Id."html1");
            $html = "";
            $html .="<script type=\"text/javascript\">";
            $html .="$(document).ready(function () {";
            $html .="$('#".$this->Id."html1').treegrid();";
            $html .="});";
            $html .="</script>";
            $html .="<table id=\"".$this->Id."html1\" class=\"tree\">";
            $html .= $dataTree;
            $html .="</table>";
            $fullHtml .= $html;
            $this->SetReplaceString("tree", $html);
        }
        else 
        {
            $this->HideTab("tab1");
        }
        
        if ($this->ShowFileRepositoryTab)
        {
            $this->ShowTab("tab2");
            $tree = $contentVersion->GetFileTree ($_GET["langid"]);
            $dataTree = $contentVersion->CreateHtml($tree,true,"",false,true,false,$this->Id."html2");
            $html = "";
            $html .="<script type=\"text/javascript\">";
            $html .="$(document).ready(function () {";
            $html .="$('#".$this->Id."html2').treegrid();";
            $html .="});";
            $html .="</script>";
            $html .="<table id=\"".$this->Id."html2\" class=\"tree\">";
            $html .= $dataTree;
            $html .="</table>";
            $fullHtml .= $html;
            $this->SetReplaceString("treeFile", $html);
        }
        else 
        {
            $this->HideTab("tab2");
        }
        
        if ($this->ShowLinkTab)
        {
            $this->ShowTab("tab3");
        }
        else 
        {
            $this->HideTab("tab3");
        }
        if ($this->ShowFormTab)
        {
            $tree = $contentVersion->GetFormsList(self::$UserGroupId, $_GET["langid"]);
            $dataTree = $contentVersion->CreateHtml($tree,true,"",false,true,false,$this->Id."html4");
            $html = "";
            $html .="<script type=\"text/javascript\">";
            $html .="$(document).ready(function () {";
            $html .="$('#".$this->Id."html4').treegrid();";
            $html .="});";
            $html .="</script>";
            $html .="<table id=\"".$this->Id."html4\" class=\"tree\">";
            $html .= $dataTree;
            $html .="</table>";
            $this->ShowTab("tab4");
            $fullHtml .= $html;
            $this->SetReplaceString("treeForm", $html);
        }
        else 
        {
            $this->HideTab("tab4");
        }
        if ($this->ShowJavascriptEvent)
        {
            $this->ShowTab("tab5");   
        }
        else 
        {
            $this->HideTab("tab5");
        }
        if ($this->ShowPrivileges)
        {
            $userGroups =  new \Objects\Users();
            $list = $userGroups->GetUserGroups();
            $security =\Utils\ArrayUtils::CreateCheckBoxList($list, "GroupName", "Id");
            $this->SetReplaceString("security",$security);
            $this->ShowTab("tab6");
        }
        else 
        {
            $this->HideTab("tab6");
        }
        $this->ActivateFirstTab();
        return $fullHtml;
    }
    private function  ShowTab($tabname)
    {
        $this->SetReplaceString($tabname, "");
        $this->SetFirstTab($tabname);
    }
    private function HideTab($tabname)
    {
        $this->SetReplaceString($tabname, "dn");
    }
    private function ActivateFirstTab()
    {
        $this->SetReplaceString("tab1activateUl", "");
        $this->SetReplaceString("tab1activateDiv","");
        $this->SetReplaceString("tab2activateUl", "");
        $this->SetReplaceString("tab2activateDiv","");
        $this->SetReplaceString("tab3activateUl", "");
        $this->SetReplaceString("tab3activateDiv","");
        $this->SetReplaceString("tab4activateUl", "");
        $this->SetReplaceString("tab4activateDiv","");
        $this->SetReplaceString("tab5activateUl", "");
        $this->SetReplaceString("tab5activateDiv","");
        $this->SetReplaceString("tab6activateUl", "");
        $this->SetReplaceString("tab6activateDiv","");
        if($this->SelectFirstTab)
        {
            $this->SetReplaceString($this->_firstTabName."activateUl", "active");
            $this->SetReplaceString($this->_firstTabName."activateDiv", "active in");
        }
    }
    private function SetFirstTab($tabName)
    {
        if (empty($this->_firstTabName))
            $this->_firstTabName = $tabName;
    }
    
            
}
