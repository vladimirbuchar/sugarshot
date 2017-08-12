<?php

namespace Model;
use Utils\StringUtils;
use Types\RuleType;
use Dibi;
use Types\DataTableColumn;
use Types\AlterTableMode;
class Users  extends DatabaseTable{
    public $UserName;
    public $FirstName;
    public $LastName;
    public $UserEmail;
    public $IsBadLogin = false;
    public $BlockDiscusion = false;
    public $IsActive = false;
    public $DefaultLang;
    //private static $_instance = null;
    
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "Users";
        $this->SaveHistory = false;
        $this->SetSelectColums(array("UserName","FirstName","LastName","UserEmail","BlockDiscusion","IsActive","DefaultLang"));
        $this->SetDefaultSelectColumns();
        
    }
    
    /*
    public static function GetInstance()
    {
        self::$_instance = null;
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }
        return self::$_instance;
    }*/
    private function validatePassword()
    {

    }
    
    public function RegistrationUser($FirstName,$LastName,$UserEmail,$UserName,$UserPassword,$UserPassword2,$profile,$defaultLang ="")
    {
        $userId = 0;
        $blockDiscusion =  false;
        $isActive =  false;
        $sendEmailAdmin = true;
        $adminMail ="";
        $adminMailId = 0;
        $adminActivation = false;
        $userEmailActivate = false;
        
        
        $sendUserEmail = false;
        $sendEmailUserFrom = "";
        $userMailId = 0;
        if ($this->IsLoginUser())
        {
            $userId = $this->GetUserId();
            $blockDiscusion = $this->UserHasBlockDiscusion();
            $isActive = true;
            $sendEmailAdmin = false;
        }
        else 
        {
            $web = Webs::GetInstance();
            $webId = $this->GetActualWeb();
            
            $web->GetObjectById($webId,true);
            $isActive = $web->AdminUserActive || $web->UserEmailActivate  ? false: true;
            $sendEmailAdmin = $web->SendInfoEmailToAdmin;
            $adminMail = $web->AdminInfoEmail;
            $adminMailId = $web->AdmiInfoMailId;
            $adminActivation = $web->AdminUserActive;
            $sendUserEmail = $web->SendInfoEmailToUser;
            $sendEmailUserFrom = $web->UserInfoEmailFrom;
            $userMailId = $web->UserInfoMailId;
            $userEmailActivate = $web->UserEmailActivate;
        }
        
        $ug =  UserGroups::GetInstance();
        $registeredUser = $ug->GetUserGroupByIdeticator("RegisteredUser");
        $userGroupId = $registeredUser["Id"];
        
        return $this->CreateUser($userId,$FirstName,$LastName,$UserEmail,$UserName,$UserPassword,$blockDiscusion,$userGroupId,$profile,$isActive,$defaultLang,false, $UserPassword2, $sendEmailAdmin,$adminMail,$adminMailId,$adminActivation,$sendUserEmail,$sendEmailUserFrom,$userMailId,$userEmailActivate);
    }
    public function CreateUser($id,$FirstName,$LastName,$UserEmail,$UserName,$UserPassword,$BlockDiscusion,$MainUserGroup,$profile = array(),$IsActive =false,$defaultLang ="",$createFromAdmin = false , $UserPassword2="",$sendEmailAdmin = false,$adminMail ="",$adminMailId=0,$adminActivation = false,$sendUserEmail=false,$sendEmailUserFrom="",$userMailId=0,$userEmailActivate=false)
    {
        if (empty($id))
            $id = 0;
        $newUser = $id == 0 ? TRUE: FALSE;
        //$this->validatePassword();
        $user =  Users::GetInstance();
        $testExists = $this->SelectByCondition("UserName = '$UserName' AND Id <> $id");
        if (!empty($testExists))
            return -1;
        $testExistsEmail = $this->SelectByCondition("UserEmail  = '$UserEmail' AND Id <> $id");
        if (!empty($testExistsEmail))
            return -2;
        if (!$createFromAdmin && (empty($UserPassword) || $UserPassword != $UserPassword2))
            return -3;
        if ($newUser)
        {
            $user->UserName = $UserName;
            $user->UserPassword = $UserPassword;
        }
        else 
        {
            $user->GetObjectById($id,TRUE);
        }
        if (empty($defaultLang))
            $defaultLang = \Utils\Utils::GetDefaultLang ();
        $user->Id = $id;
        $user->FirstName = $FirstName;
        $user->LastName = $LastName;
        $user->UserEmail = $UserEmail;
        $user->BlockDiscusion = $BlockDiscusion;
        $user->IsActive = $IsActive;
        $user->DefaultLang = $defaultLang;
        $id = $user->SaveObject($user);
        $userInGroup = UsersInGroup::GetInstance();
        $userInGroup->DeleteByCondition("UserId = $id");
        $userInGroup->AddUserToGroup($id, $MainUserGroup , TRUE);
        
        
        if ($newUser && !empty($profile))
        {
            $ud = \Model\UserDomainsValues::GetInstance();
            $profile["Id"] = $id;
            $profile["DomainIdentificator"] = "UserProfile";
            $ud->SaveUserDomainData($profile);
        }
        $data = array();
        $data[0][0] ="FirstName";
        $data[0][1] =$user->FirstName;
        $data[1][0] ="LastName";
        $data[1][1] =$user->LastName;
        $data[2][0] ="UserName";
        $data[2][1] =$user->UserName;
        $data[3][0] ="UserEmail";
        $data[3][1] =$user->UserEmail;
        $x = 4;
        foreach ($profile as $k =>$v)
        {
            $data[$x][0] = $k;
            $data[$x][1] = $v;
            $x++;
        }
        if ($sendEmailAdmin)
        {
            $mail = new Mail();
            if ($adminActivation)
            {
                $data[$x][0]=  "linkactivate";
                $data[$x][1]= $this->GenerateLinkActivate($UserPassword, $UserEmail, $id, $UserName);
                $x++;
            }
            $mail->SendEmail($user->UserEmail, $adminMail, $adminMailId, $data);
        }
        if ($sendUserEmail)
        {
            $mail = new Mail();
            if ($userEmailActivate)
            {
               $data[$x][0]=  "linkactivate";
               $data[$x][1]= $this->GenerateLinkActivate($UserPassword, $UserEmail, $id, $UserName);
            }
            
            $mail->SendEmail($sendEmailUserFrom, $user->UserEmail, $userMailId, $data);
        }
        return $id;
    }
    
    public function UserLogin($userName,$password,$emailLogin =  false)
    {
        $badLogins = new \Objects\BadLogins();
        if ($badLogins->GetBadsLogins() >= 3)
        {
            $this->IsBadLogin = true;
            return false;
        }
        
        if (empty($userName) || empty($password)) 
        {
            $badLogins->AddBadLogin();
            return false;
        }
        
        $res = null;
        if ($emailLogin) 
            $res = dibi::query("SELECT * FROM LOGINUSERVIEW WHERE (UserName = %s  OR UserEmail = %s )AND UserPassword = %s",$userName,$userName,MD5(SHA1($password)))->fetchAll();    
        else 
            $res = dibi::query("SELECT * FROM LOGINUSERVIEW WHERE UserName = %s AND UserPassword = %s",$userName,MD5(SHA1($password)))->fetchAll();    
        
        
        if (empty($res)){
            
            $badLogins->AddBadLogin();
            return FALSE;
        }
        $userData = $this->GetFirstRow($res);
        
        self::$SessionManager->SetSessionValue("UserId", $userData->UserId);
        
        self::$SessionManager->SetSessionValue("UserGroupId", $userData->GroupId);
        self::$SessionManager->SetSessionValue("UserGoupIdentificator", $userData->GroupIdentificator);
        self::$SessionManager->SetSessionValue("FullUserName", $userData->FullName);
        self::$SessionManager->SetSessionValue("UserName", $userData->UserName);
        if (!empty($userData->DefaultLang))
        {
            self::$SessionManager->SetSessionValue("AdminUserLang", $userData->DefaultLang);
        }
        
        if($this->IsLoginUser())
        {
            $badLogins->RemoveAllBadLogins();
            return TRUE;
        }
        $badLogins->AddBadLogin();
        return FALSE;
    }
    
    public function BlockDiscusionUser($userId)
    {
        dibi::query("UPDATE Users  SET BlockDiscusion = 1 WHERE Id = %i ",$userId);
    }
    
    public function UserHasBlockDiscusion()
    {
        $userId = $this->GetUserId();
        $this->GetObjectById($userId,true);
        return $this->BlockDiscusion;
    }
    public function GetFullUserName()
    {
        if (!self::$SessionManager->IsEmpty("FullUserName"))
            return self::$SessionManager->GetSessionValue("FullUserName");
    }
    public function GetUserGroupId()
    {
        if (!self::$SessionManager->IsEmpty("UserGroupId"))
            return self::$SessionManager->GetSessionValue("UserGroupId");
        $userGroup =  UserGroups::GetInstance();
        $groupData = $userGroup->GetUserGroupByIdeticator("anonymous");
        self::$SessionManager->SetSessionValue("UserGroupId", $groupData->Id);
        return $groupData->Id;
    }
    
    public function GetOtherUserGroups()
    {
        
        if (self::$SessionManager->IsEmpty("OtherUserGroups"))
        {
            if ($this->IsLoginUser())
            {
                $userId = $this->GetUserId();
                $res = dibi::query("SELECT GroupId FROM UsersInGroup WHERE UserId = %i AND IsMainGroup = 0",$userId)->fetchAll();
                self::$SessionManager->SetSessionValue("OtherUserGroups",$res);
                
                return $res;
            }
            return null;
        }
        return self::$SessionManager->GetSessionValue("OtherUserGroups");
    }
    public function UserActivate($id)
    {
        dibi::query("UPDATE Users  SET IsActive  = 1 WHERE Id = %i ",$id);
    }
            
    
    public function IsLoginUser()
    {
        try{
            
        if (self::$SessionManager->IsEmpty("UserGroupId"))
            return FALSE;
        if (self::$SessionManager->GetSessionValue("UserGroupId") == 0) 
            return FALSE;
        
        $userGroup =  UserGroups::GetInstance();
        $groupData = $userGroup->GetAnonymousGroup();
        
        if($groupData->Id == self::$SessionManager->GetSessionValue("UserGroupId"))
            return FALSE;
        return TRUE;   
        }
        catch(Exception $ex){
            \Utils\Files::WriteLogFile($ex);
            return false;
        }
    }
    public function GetUserGroupIdentificator()
    {
        
        if (self::$SessionManager->IsEmpty("UserGoupIdentificator")) return "";
        return self::$SessionManager->GetSessionValue("UserGoupIdentificator");
    }
    
    public function GetUserId()
    {
        try{
            
            if (!self::$SessionManager->IsEmpty("UserId")) return self::$SessionManager->GetSessionValue("UserId");
            
            if (self::$SessionManager->IsEmpty("AnonymousUserId"))
            {
                $res = $this->GetFirstRow($this->SelectByCondition("UserName = 'anonymous'","",array("Id")));
                self::$SessionManager->SetSessionValue("AnonymousUserId",$res["Id"]);
                
            }
            return self::$SessionManager->GetSessionValue("AnonymousUserId");
            
        }
        catch (Exception $ex){
            
            return -1;
        }
    }

    public function UserLogout()
    {
        self::$SessionManager->UnsetKey("UserId");
        self::$SessionManager->UnsetKey("UserName");
        self::$SessionManager->UnsetKey("UserGroupId");
        self::$SessionManager->UnsetKey("UserGoupIdentificator");
        self::$SessionManager->UnsetKey("IsSystemUser");
        self::$SessionManager->UnsetKey("OtherUserGroups");
        
        
    }
    
    public function GetUserDetail($userId)
    {
        return $this->GetObjectById($userId);
    }
    
    public function IsSystemUser()
    {
        
        if (self::$SessionManager->IsEmpty("IsSystemUser") || self::$SessionManager->GetSessionValue("IsSystemUser")== true)
        {
            $this->GetObjectById($this->GetUserId(),true);
            if ($this->UserName == "system"){
                self::$SessionManager->SetSessionValue("IsSystemUser",true);
                return true;
            }
            self::$SessionManager->SetSessionValue("IsSystemUser",false);
            return false;
        }
        return self::$SessionManager->GetSessionValue("IsSystemUser");
    }
    
    public function ChangePassword($password1,$password2,$userId)
    {
        
        $this->validatePassword();
        if (empty($password1) || empty($password2))
        {
            return;
        }
        if ($password1 != $password2)
        {
            return;
        }
        $this->SetValidateRule("UserPassword", RuleType::$Hash);
        $this->GetObjectById($userId,true);
        $this->UserPassword = $password1;
        $this->SaveObject();
    }
    public function GetUserName()
    {
        
        if(self::$SessionManager->IsEmpty("UserName"))
        {
            return "";
        }
        return self::$SessionManager->GetSessionValue("UserName");
    }
    
    


    
    
    

    
    public function OnCreateTable() {
        
        $colUserName = new DataTableColumn();
        $colUserName->DefaultValue ="";
        $colUserName->IsNull = false;
        $colUserName->Length = 50;
        $colUserName->Name ="UserName";
        $colUserName->Type = "varchar";
        $colUserName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserName);
        
        $colUserPassword = new DataTableColumn();
        $colUserPassword->DefaultValue ="";
        $colUserPassword->IsNull = false;
        $colUserPassword->Length = 50;
        $colUserPassword->Name ="UserPassword";
        $colUserPassword->Type = "varchar";
        $colUserPassword->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserPassword);
        
        $colUserFirstName = new DataTableColumn();
        $colUserFirstName->DefaultValue ="";
        $colUserFirstName->IsNull = true;
        $colUserFirstName->Length = 50;
        $colUserFirstName->Name ="FirstName";
        $colUserFirstName->Type = "varchar";
        $colUserFirstName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserFirstName);
        
        $colUserLastName = new DataTableColumn();
        $colUserLastName->DefaultValue ="";
        $colUserLastName->IsNull = true;
        $colUserLastName->Length = 50;
        $colUserLastName->Name ="LastName";
        $colUserLastName->Type = "varchar";
        $colUserLastName->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserLastName);
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue ="";
        $colUserEmail->IsNull = true;
        $colUserEmail->Length = 50;
        $colUserEmail->Name ="UserEmail";
        $colUserEmail->Type = "varchar";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue = false;
        $colUserEmail->IsNull = true;
        $colUserEmail->Name ="BlockDiscusion";
        $colUserEmail->Type = "BOOLEAN";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
       
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue = false;
        $colUserEmail->IsNull = true;
        $colUserEmail->Name ="IsActive";
        $colUserEmail->Type = "BOOLEAN";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
        $colUserEmail = new DataTableColumn();
        $colUserEmail->DefaultValue ="";
        $colUserEmail->IsNull = true;
        $colUserEmail->Length = 50;
        $colUserEmail->Name ="DefaultLang";
        $colUserEmail->Type = "varchar";
        $colUserEmail->Mode = AlterTableMode::$AddColumn;
        $this->AddColumn($colUserEmail);
        
        
        
        

    }

    public function InsertDefaultData() {
        $this->Setup($this);
    }

    
    private function GenerateLinkActivate($userPassword,$userEmail,$id,$userName)
    {
        $content = ContentVersion::GetInstance();
        $userActivationId=  $content->GetIdByIdentificator("userActivation");
        $userActivation = $content->GetUserItemDetail($userActivationId, $this->GetUserGroupId(), $this->GetActualWeb(), $this->GetLangIdByWebUrl());
        if (count($userActivation) > 0)
        {
            return SERVER_NAME_LANG.$userActivation[0]["SeoUrl"]."/". base64_encode(StringUtils::HashString($userPassword)."#".$userEmail."#".$id."#".$userName)."/";
        }
    }

    public function SetValidate($mode = false) {
        $this->SetValidateRule("UserName", RuleType::$NoEmpty,$this->GetWord("word480"));
        $this->SetValidateRule("UserName", RuleType::$Unique,$this->GetWord("word481"));
        $this->SetValidateRule("FirstName", RuleType::$NoEmpty,$this->GetWord("word482"));
        $this->SetValidateRule("LastName", RuleType::$NoEmpty,$this->GetWord("word483"));
        $this->SetValidateRule("UserEmail", RuleType::$NoEmpty,$this->GetWord("word484"));
        $this->SetValidateRule("UserEmail", RuleType::$Unique,$this->GetWord("word485"));
        if ($mode)
        {
            $this->SetValidateRule("UserPassword", RuleType::$Hash);
            $this->SetValidateRule("UserPassword", RuleType::$NoEmpty,$this->GetWord("word486"));
        }
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
