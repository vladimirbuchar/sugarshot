<?php
namespace Components;
class ArticleList extends UserComponents{
    
    public $DivId = "";
    public $LoadUrl ="";
    
    public $ShowPager = false;
    public $LoadItemsCount = 0;
    public $NextLoadItemsCount = 0;
    
    public $ShowSort = false;
    public $SortDomain ="";
    public $ShowSortByName = false;
    public $WordSortByName = "word780";
    public $SortASC = "word781";
    public $SortDESC = "word782";
    public $SortQuery = "";
    
    public $ShowFiltr = false;
    public $FiltrDomain = "";
    public $LoadAllSubitems = false;
    public $IgnoreActiveUrl = true;
    public $AcceptUserTeplates ="";
    
    public function __construct() {
        $this->LinkJavascript = true;
        //$this->InsertJavascriptToContent = true;
        $this->Type = "ArticleList";
        $this->LoadHtml = true;
        $this->UseItemTemplate = true;    
        $this->AutoReplaceString =true;
        parent::__construct();   
    }     
    
    public function GetComponentHtml()
    {
        $content =  \Model\ContentVersion::GetInstance();
        $pagerHtml = "";
        $sortHtml = "";
        $filtrHtml = "";
        if (!empty($_GET["seourl"])) 
            $this->LoadUrl = $_GET["seourl"];
        //$childs = $this->GetDataSource();
        $childs = $content->LoadFrontendFromSeoUrl($this->LoadUrl, self::$UserGroupId, $this->LangId, $this->WebId,$this->LoadItemsCount,$this->SortQuery,$this->LoadAllSubitems,$this->IgnoreActiveUrl,"",true,$this->Where,$this->WhereColumns);
        $this->IsEmptyComponent = empty($childs);
        if ($this->LoadItemsCount >= count($childs))
            $this->ShowPager = false;
        if ($this->ShowPager)
        {
            $pager = new Pager();
            $pager->PagerDivId = $this->DivId;
            $pager->UseUrl = $this->LoadUrl;
            $pagerHtml = $pager->LoadComponent();
        }
        
        if ($this->ShowSort)
        {
            $sort = new Sort();
            $sort->SortDivId = $this->DivId;
            $sort->SortDomain = $this->SortDomain;
            $sort->ShowSortByName = $this->ShowSortByName;
            $sort->WordSortByName = $this->WordSortByName;
            $sort->SortASC = $this->SortASC;
            $sort->SortDESC = $this->SortDESC;
            $sort->UseUrl = $this->LoadUrl;
            $sort->SortQuery = $this->SortQuery;
            $sortHtml = $sort->LoadComponent();
        }
        
        if ($this->ShowFiltr)
        {
            $filtr = new Filtr();
            $filtr->FiltrDivId = $this->DivId;
            $filtr->FiltrDomain = $this->FiltrDomain;
            $filtrHtml = $filtr->LoadComponent();
            
            
        }
        
        $this->SetReplaceString("pager", $pagerHtml);
        $this->SetReplaceString("sort", $sortHtml);
        $this->SetUsedWords("word837");
        
        $this->RenderHiddenInput("pagerLoadItems",$this->LoadItemsCount);
        $this->RenderHiddenInput("pagerNextLoadItems",$this->NextLoadItemsCount);
        $this->RenderHiddenInput("showPager",$this->ShowPager);
         
        /// sort setting
        $this->RenderHiddenInput("showSort",$this->ShowSort);
        $this->RenderHiddenInput("sortDomain",$this->SortDomain);
        
        $this->RenderHiddenInput("showSortByName",$this->ShowSortByName);
        $this->RenderHiddenInput("wordSortByName",$this->WordSortByName);
        
        $this->RenderHiddenInput("sortASC",$this->SortASC);
        $this->RenderHiddenInput("sortDESC",$this->SortDESC);
        $this->RenderHiddenInput("sortQuery", $this->SortQuery);    
        
        $this->SetReplaceString("pager", $pagerHtml);
        $this->SetReplaceString("sort", $sortHtml);
        
        // filtr
        $this->SetReplaceString("filtr", $filtrHtml);
        
        $this->ReplaceItems($childs);
        
    }
    
    
}
