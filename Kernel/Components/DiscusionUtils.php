<?php

class DiscusionUtils extends GlobalClass{
    public function GetHtml($discusionItems,$mode = "",$showGoToDiscusionButton = false,$href="")
    {
        if (empty($mode))
            $mode = DiscusionsMode::$AdminMode;
        $users = new \Objects\Users();
        $blockDiscusion = $users->UserHasBlockDiscusion();
        $button = new Button();
        $button->Value = "Přidat příspěvěk do diskuze";
        $discusionHtml = $this->GetChilds($discusionItems, 0,1,$blockDiscusion,$mode);
        if (!$blockDiscusion && ($mode != DiscusionsMode::$ReadOnly))
        {
            if ($mode != DiscusionsMode::$AdminMode)
                $discusionHtml = $button->RenderHtml($button)." ".$discusionHtml;
            else 
                $discusionHtml = $discusionHtml;
        }
        if (!$showGoToDiscusionButton && $mode != DiscusionsMode::$AdminMode)
        {
            $link = new Link();
            $link->Html = "Vstoupit do diskuze";
            $link->Href = $href;
            $discusionHtml  = $link->RenderHtml($link)." ".$discusionHtml;
        }
        return $discusionHtml;
        
    }
     private function GetChilds($array,$parent,$level,$blockDiscusion,$mode)
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
                if (!$blockDiscusion)
                {
                
                    if ($mode == DiscusionsMode::$AdminMode)    
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
                    if ($mode == DiscusionsMode::$AdminMode)    
                    {
                        $blockUserDiscusion = new Link();
                        $blockUserDiscusion->Html = $this->GetWord("word491");
                        $blockUserDiscusion->OnClick = "BlockDiscusionUser(".$row["UserId"].")";
                    }
                    if ($mode == DiscusionsMode::$AdminMode)
                        $divControls->Html = $delete->RenderHtml($delete)." ". $edit->RenderHtml($edit)." ".$addReaction->RenderHtml($addReaction)." ".$historyDiscusionItems->RenderHtml($historyDiscusionItems)." ".$blockUserDiscusion->RenderHtml($blockUserDiscusion);
                    else if ($mode == DiscusionsMode::$UserMode)
                        $divControls->Html =  $edit->RenderHtml($edit)." ".$addReaction->RenderHtml($addReaction)." ".$historyDiscusionItems->RenderHtml($historyDiscusionItems);
                    else if ($mode ==  DiscusionsMode::$AddItems)
                    {
                        $divControls->Html =  $addReaction->RenderHtml($addReaction);
                    }
                    else if ($mode == DiscusionsMode::$ReadOnly)
                    {
                        $divControls->Html ="";
                    }
                }
                $tmpLevel = $level +1;
                $child = $this->GetChilds($array,$row["VersionId"],$tmpLevel,$blockDiscusion,$mode);
                $div->Html = $divSubject->RenderHtml($divSubject)." ".$divText->RenderHtml($divText)." ".$divDate->RenderHtml($divDate) ." ".$divControls->RenderHtml($divControls)." ".$child;                       
                $outHtml .= $div->RenderHtml($div);
            }
        }
        return $outHtml;
    }
}


