<?php

class UserProfile extends UserComponentsDatasource implements \Inteface\iComponent{
    
    
    public function UserProfile() {
        parent::UserComponentsDatasource();
        $this->Type = "UserProfile";
        $this->LoadHtml = true;
        $this->ReplaceRowMode = true;
    }     
    
    public function GetComponentHtml()
    {
        $user =  new \Objects\Users();
        $userDetail = $user->GetUserDetail($this->UserId);
        $domainHtml = $this->GetUserDomain("UserProfile",$this->User->GetUserId());
        $this->ReplaceData = $userDetail;
        return $html;
        
        
        
        
    }
    
    
}
