<?php
namespace Components;
class UserArticles extends UserComponents{
    
    public function UserArticles() {
        
        $this->Type = "UserArticles";
        parent::__construct();
        
    }     
    
    public function GetComponentHtml($obj)
    {
        $content =  \Model\ContentVersion::GetInstance();
        $id = $content->GetIdByIdentificator($this->DataSource);
        $data = $content->GetUserItemDetail($id, self::$UserGroupId, $this->WebId, $this->LangId,0,true);
        $userDomains = \Model\UserDomainsItems::GetInstance();
        $domainIdentificator = $userDomains->GetUserDomainByTemplateId($data[0]["ChildTemplateId"]);
        $form = new Forms();
        $formHtml = $form->GetUserDomain($domainIdentificator);
        $divForm = new Div();
        $divForm->CssClass="form-horizontal";
        $divForm->Role ="form";
        $divForm->Html = $formHtml;
        
        $divContnet = new Div();
        $divMenu = new Div();
        $divMenu->CssClass="menuDialog-$id";
        $addInzert = null;
        if ($obj->Mode == "Redirect")
        {
            $addInzert = new Link();
            $addInzert->Html = $this->GetWord($obj->ButtonAdd);
            $addInzert->CssClass = $obj->AddButtonClass;
            $formDetail = $content->GetFormDetail($content->GetIdByIdentificator($obj->AddForm), self::$UserGroupId, $this->WebId, $this->LangId);
            $addInzert->Href = SERVER_NAME_LANG. $formDetail[0]["SeoUrl"]."/";
        }
        else 
        {
            $addInzert = new Button();
            $addInzert->Value= $this->GetWord($obj->ButtonAdd);
            $addInzert->CssClass = $obj->AddButtonClass;
            $addInzert->DataTarget="#".$obj->EditDialogId;
            $addInzert->DataToggle ="modal";
            $editDialog = new BootstrapDialog();
            $editDialog->DialogId =  $obj->EditDialogId;
            $editDialog->DialogTitle = $this->GetWord($obj->EditDialogTitle);
            $editDialog->DialogContent = $divForm->RenderHtml();
            $editDialog->SaveButtonText = $this->GetWord($obj->SaveButtonText);
            $editDialog->CancelButtonText = $this->GetWord($obj->CancelButtonText);
            $divMenu->Html = $editDialog->GetComponentHtml();
        }
        $tree = $content->GetUserItems(self::$UserId,$this->LangId,$id);
        $divTree = new Div();
        foreach ($tree as $item)
        {
           $divTreeItem = new Div();
           $aEditLink = new Link();
           $formDetail = $content->GetFormDetail($content->GetIdByIdentificator($obj->AddForm), self::$UserGroupId, $this->WebId, $this->LangId);
           if ($obj->Mode == "Redirect")
           {
                $aEditLink = new Link();
                $aEditLink->CssClass = $obj->AddButtonClass;
                $aEditLink->Html ="Edit";
                $aEditLink->Href = SERVER_NAME_LANG. $formDetail[0]["SeoUrl"]."/".$item["ContentId"]."/";
            }
            $aDeleteLink = new Link();
            $aDeleteLink->CssClass = $obj->AddButtonClass;
            $aDeleteLink->Html ="DELETE";
            $aDeleteLink->OnClick="DeleteUserItemFrontend('".$item["ContentId"]."'); return false";
            
            //$formDetail = $content->GetFormDetail($content->GetIdByIdentificator($obj->AddForm), $this->userGroupId, $this->webId, $this->langId);
            //$aEditLink->Href = SERVER_NAME_LANG. $formDetail[0]["SeoUrl"]."/".$item["ContentId"]."/";
            
            $divTreeItem->Html = $item["Name"];
            $divTreeItem->SetChild($aEditLink);
            $divTreeItem->SetChild($aDeleteLink);
            $divTree->SetChild($divTreeItem);
        }
        $divMenu->SetChild($addInzert);
        $divContnet->SetChild($divMenu);
        $divContnet->SetChild($divTree);
        return $divContnet->RenderHtml();
    }
    
    
}
