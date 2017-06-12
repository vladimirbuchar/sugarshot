<?php
namespace Components;
class Sort extends UserComponents{
    public $SortDivId = "";
    public $SortDomain = "";
    public $ShowSortByName = false;
    public $WordSortByName = "word780";
    public $SortASC = "word781";
    public $SortDESC = "word782";
    public $UseUrl ="";
    public $SortQuery = "";
    public function __construct() {
        $this->Type = "Sort";
        parent::__construct();
        
    }
    
    private function AddSort($name,$value)
    {
        $option = new \HtmlComponents\Option();
        $option->Value = $value;
        $option->Html = $name;
        $option->Selected = $this->SortQuery == $value;
        return $option;
    }
    
  
    public function GetComponentHtml(){
        $userDomainItems = \Model\UserDomainsItems::GetInstance();
        $data = $userDomainItems->GetUserDomainItemByIdentificator($this->SortDomain, "sort");
        $select = new \HtmlComponents\Select();
        $select->Id = "selectSort";
        $select->OnChange="ReloadListPage('$this->SortDivId','".$this->UseUrl."','sort')";
        $ascText = $this->GetWord($this->SortASC);
        $descText = $this->GetWord($this->SortDESC);
        $select->SetChild($this->AddSort("----", ""));
        if ($this->ShowSortByName)
        {
            $select->SetChild($this->AddSort($this->GetWord($this->WordSortByName)." ".$ascText, "Name ASC"));
            $select->SetChild($this->AddSort($this->GetWord($this->WordSortByName)." ".$descText, "Name DESC"));
        }
        foreach ($data as $row)
        {
            $select->SetChild($this->AddSort($row["ShowName"]." ".$ascText, "##".$row["Identificator"]." ASC"));
            $select->SetChild($this->AddSort($row["ShowName"]." ".$descText, "##".$row["Identificator"]." DESC"));
        }
        return $select->RenderHtml();
    }
    
    
    
}
