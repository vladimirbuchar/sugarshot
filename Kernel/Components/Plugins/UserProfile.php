<?php

class UserProfile extends UserComponentsDatasource{
    
    
    public function UserProfile() {
        parent::UserComponentsDatasource();
        $this->Type = "UserProfile";
        $this->LoadHtml = true;
        $this->ReplaceRowMode = true;
    }     
    
    public function GetComponentHtml()
    {
        $user =  \Model\Users::GetInstance();
        $userDetail = $user->GetUserDetail($this->UserId);
        $domainHtml = $this->GetUserDomain("UserProfile",$this->User->GetUserId());
        $this->ReplaceData = $userDetail;
        return $html;
        
        
        
        
    }
    
    
}
