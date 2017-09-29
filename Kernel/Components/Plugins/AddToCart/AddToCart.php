<?php
namespace Components;
    class AddToCart extends UserComponents implements \Inteface\iComponent{
    
    public $ButtonName = "";
    public $SelectItem ="";
    public $ErrorMessage = "";
    public $ProductId = 0;
    public $CartUrl ="";
    
    public function __construct() {
        
        $this->LoadHtml = true;
        $this->Type ="AddToCart";
        $this->AutoReplaceString = true;
        
        parent::__construct();
    }
    
    public function GetComponentHtml() {
        $content =  new \Objects\Content();
        if ($this->ProductId == 0)
        {
            $this->ProductId = $content->GetIdBySeoUrl($_GET["seourl"], $this->WebId);
        }
        $status = $content->GetValueFromContentData($this->ProductId,$this->LangId,"ProductStock");
        $this->SetReplaceString("ShowCartButton", "");
        if ($status == -1 || $status == -5)
            $this->SetReplaceString("ShowCartButton", "dn");
        
        $orderMaxCount = $status;
        
        $this->SetReplaceString("SelectItem", $this->SelectItem);
        $this->SetReplaceString("ProductId", $this->ProductId);
        $this->SetReplaceString("ButtonName", $this->GetWord($this->ButtonName));
        $this->SetReplaceString("ErrorMessage",$this->GetWord($this->ErrorMessage));
        $this->SetReplaceString("CartUrl", \Utils\StringUtils::NormalizeUrl(SERVER_NAME_LANG.$this->CartUrl));
        $this->SetReplaceString("MaxOrder", $orderMaxCount);
        $this->SetUsedWords("word795");
        
        
        
    }
}
