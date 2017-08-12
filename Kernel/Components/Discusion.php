<?php
namespace Components;

use Model\DiscusionItems;
use HtmlComponents\Div;
use Types\DiscusionsMode;
use HtmlComponents\Link;
class Discusion extends UserComponents{
    
    public $DiscusionMode;
    public $HideGoToDiscusion = false;
    public $LoadDiscusionFormSeoUrl = true;
    public $DiscusionId = 0;
    public $AddButtonText = "word410";
    public $DiscusionTitle = "word411";
    public $ShowItems = 0;
    public $OpenDiscusion = "word783";
    private $_discusionSeoUrl = "";
    
    private $_canDiscusion;
    private $_showUserName ="";
   
    
    public function __construct() {
        
        $this->CacheBySeoUrl = true;
        $this->Type = "Discusion";
        $this->LoadHtml = true;
        $this->AutoReplaceString = true;
        $this->InsertJavascriptToContent = true;
        if (empty($this->DiscusionMode))
            $this->DiscusionMode = DiscusionsMode::$UserMode;
        parent::__construct();
    }
    public function GetComponentHtml()
    {
        
        $contentVersion = new \Objects\Content();
        $discusion = new \Objects\Discusion();
        $this->SetUsedWords("word412");
        $this->SetUsedWords("word413");
        $this->SetUsedWords("word414");
        $this->SetUsedWords("word415");
        $this->SetUsedWords("word416");
        $this->SetUsedWords("word417");
        $this->SetUsedWords("word418");
        $this->SetReplaceString("EditUserName", "");
        $this->SetReplaceString("DiscusionTitle", $this->GetWord($this->DiscusionTitle));
        $this->SetReplaceString("AddDiscusionItem", "");
        if ($this->IsLogin)
        {
            $this->_showUserName = self::$User->GetFullUserName();
            $this->SetReplaceString("UserFullName", $this->_showUserName);
            $this->SetReplaceString("EditUserName", "disabled= 'disabled'");
        }
        if ($this->LoadDiscusionFormSeoUrl)
        {
            $this->DiscusionId = $contentVersion->GetDiscusionIdBySeoUrl($_GET["seourl"], $this->LangId, $this->WebId);
            if ($this->DiscusionId >0)
            {
                $detail  = $contentVersion->GetDiscusionDetail($this->DiscusionId, self::$UserGroupId, $this->WebId, $this->LangId);
                $this->_discusionSeoUrl = $detail[0]["SeoUrl"];
            }
        }
        if ($this->DiscusionId == 0)
            $this->Visible = false;
        
        $items = $discusion->GetDiscusionItems($this->DiscusionId,$this->ShowItems); 
        $this->_canDiscusion = self::$User->UserHasBlockDiscusion() ? false: true;
        $this->SetReplaceString("DiscusionId", $this->DiscusionId);
        
        if ($this->_canDiscusion)
        {
            $addNewItem = new \HtmlComponents\Link();
            $addNewItem->Href="#";
            $addNewItem->DataToggle="modal";
            $addNewItem->DataTarget="#AddDiscusionItem";
            $addNewItem->CssClass="btn btn-default";
            $addNewItem->OnClick="SetParentId(0,0)";
            $addNewItem->Html = $this->GetWord($this->AddButtonText);
            $this->SetReplaceString("AddDiscusionItem", $addNewItem->RenderHtml());
        }
        $openLink = "";
        if ($this->ShowItems > 0)
        {
            $openDiscusion = new \HtmlComponents\Link();
            $openDiscusion->Html = $this->GetWord($this->OpenDiscusion);
            $openDiscusion->Href = SERVER_NAME_LANG. $this->_discusionSeoUrl."/";
            $openLink = $openDiscusion->RenderHtml();
            
        }
        $this->SetReplaceString("DiscusionItems", $this->RenderHtmlDiscusionItems($items,0,1));
        $this->SetReplaceString("OpenLink", $openLink);
        
        
                
        
        
    }
    private function RenderHtmlDiscusionItems($array,$parent,$level)
    {
        $outHtml ="";
        foreach ($array as $row)
        {
            if ($row["ParentIdDiscusion"] == $parent)
            {
                $div = new Div();
                $div->CssClass = "discusionItem level".$level;
                
                $divSubject = new Div();
                $divSubject->CssClass = "discusionSubject";
                $divSubject->Html = $row["SubjectDiscusion"];
                
                $divText = new Div();
                $divText->CssClass = "discusionText";
                $divText->Html = $row["TextDiscusion"];
                
                $divDate = new Div();
                $divDate->CssClass = "dateDiscusion";
                $divDate->Html = date("m-d-Y H:m:s",$row["DateTime"]);
                $divControls = new Div();
                if ($this->_canDiscusion)
                {
                
                    if ($this->DiscusionMode == DiscusionsMode::$AdminMode)    
                    {
                        $delete = new Link();
                        $delete->Html =$this->GetWord("word487");
                        $delete->OnClick = "DeleteDiscusionItem('".$row["Id"]."'); return false;";
                    }
                    $edit = new Link();
                    $edit->Html = $this->GetWord("word488");
                    $edit->DataTarget="#AddDiscusionItem";
                    $edit->DataToggle="modal";
                    $edit->OnClick = "LoadDiscusionItemDetail(".$row["Id"]."); ";
                
                    $addReaction = new Link();
                    $addReaction->Html = $this->GetWord("word489");
                    $addReaction->DataTarget="#AddDiscusionItem";
                    $addReaction->DataToggle="modal";
                    $addReaction->OnClick = "SetParentId(".$row["VersionId"].",0); ";
                
                    $historyDiscusionItems = new Link();
                    $historyDiscusionItems->Html = $this->GetWord("word490");
                    $historyDiscusionItems->DataTarget="#HistoryDiscusionItem";
                    $historyDiscusionItems->DataToggle="modal";
                    $historyDiscusionItems->OnClick = "ShowHistoryDiscusionItems(".$row["VersionId"].",0); ";
                    if ($this->DiscusionMode == DiscusionsMode::$AdminMode)    
                    {
                        $blockUserDiscusion = new Link();
                        $blockUserDiscusion->Html = $this->GetWord("word491");
                        $blockUserDiscusion->OnClick = "BlockDiscusionUser(".$row["UserId"].")";
                    }
                    if ($this->DiscusionMode == DiscusionsMode::$AdminMode)
                        $divControls->Html = $delete->RenderHtml($delete)." ". $edit->RenderHtml($edit)." ".$addReaction->RenderHtml($addReaction)." ".$historyDiscusionItems->RenderHtml($historyDiscusionItems)." ".$blockUserDiscusion->RenderHtml($blockUserDiscusion);
                    else if ($this->DiscusionMode == DiscusionsMode::$UserMode)
                        $divControls->Html =  $edit->RenderHtml($edit)." ".$addReaction->RenderHtml($addReaction)." ".$historyDiscusionItems->RenderHtml($historyDiscusionItems);
                    else if ($this->DiscusionMode ==  DiscusionsMode::$AddItems)
                    {
                        $divControls->Html =  $addReaction->RenderHtml($addReaction);
                    }
                    else if ($this->DiscusionMode == DiscusionsMode::$ReadOnly)
                    {
                        $divControls->Html ="";
                    }
                }
                $tmpLevel = $level +1;
                $child="";
                $child = $this->RenderHtmlDiscusionItems($array,$row["VersionId"],$tmpLevel);
                $div->Html = $divSubject->RenderHtml($divSubject)." ".$divText->RenderHtml($divText)." ".$divDate->RenderHtml($divDate) ." ".$divControls->RenderHtml($divControls)." ".$child;                       
                $outHtml .= $div->RenderHtml($div);
            }
        }
        return $outHtml;
    }
    
         
    
    
}
