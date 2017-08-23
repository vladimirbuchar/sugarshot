<?php

namespace Objects;

use Dibi;
use Utils\ArrayUtils;

class Users extends ObjectManager {

    public $IsBadLogin;

    public function __construct() {
        parent::__construct();
    }

    public function RegistrationUser($FirstName, $LastName, $UserEmail, $UserName, $UserPassword, $UserPassword2, $profile, $defaultLang = "") {
        $userId = 0;
        $blockDiscusion = false;
        $isActive = false;
        $sendEmailAdmin = true;
        $adminMail = "";
        $adminMailId = 0;
        $adminActivation = false;
        $userEmailActivate = false;


        $sendUserEmail = false;
        $sendEmailUserFrom = "";
        $userMailId = 0;
        if ($this->IsLoginUser()) {
            $userId = $this->GetUserId();
            $blockDiscusion = $this->UserHasBlockDiscusion();
            $isActive = true;
            $sendEmailAdmin = false;
        } else {
            $web = \Model\Webs::GetInstance();
            $webId = $this->GetActualWeb();
            $web->GetObjectById($webId, true, array("AdminUserActive", "UserEmailActivate", "SendInfoEmailToAdmin", "AdminInfoEmail", "AdmiInfoMailId", "AdminUserActive"
                , "AdminUserActive", "SendInfoEmailToUser", "UserInfoEmailFrom", "UserInfoMailId", "UserEmailActivate"));
            $isActive = $web->AdminUserActive || $web->UserEmailActivate ? false : true;
            $sendEmailAdmin = $web->SendInfoEmailToAdmin;
            $adminMail = $web->AdminInfoEmail;
            $adminMailId = $web->AdmiInfoMailId;
            $adminActivation = $web->AdminUserActive;
            $sendUserEmail = $web->SendInfoEmailToUser;
            $sendEmailUserFrom = $web->UserInfoEmailFrom;
            $userMailId = $web->UserInfoMailId;
            $userEmailActivate = $web->UserEmailActivate;
        }


        $registeredUser = $this->GetUserGroupByIdeticator("RegisteredUser");
        $userGroupId = $registeredUser["Id"];

        return $this->CreateUser($userId, $FirstName, $LastName, $UserEmail, $UserName, $UserPassword, $blockDiscusion, $userGroupId, $profile, $isActive, $defaultLang, false, $UserPassword2, $sendEmailAdmin, $adminMail, $adminMailId, $adminActivation, $sendUserEmail, $sendEmailUserFrom, $userMailId, $userEmailActivate);
    }

    public function CreateUser($id, $FirstName, $LastName, $UserEmail, $UserName, $UserPassword, $BlockDiscusion, $MainUserGroup, $profile = array(), $IsActive = false, $defaultLang = "", $createFromAdmin = false, $UserPassword2 = "", $sendEmailAdmin = false, $adminMail = "", $adminMailId = 0, $adminActivation = false, $sendUserEmail = false, $sendEmailUserFrom = "", $userMailId = 0, $userEmailActivate = false) {
        if (empty($id))
            $id = 0;
        $newUser = $id == 0 ? TRUE : FALSE;
        //$this->validatePassword();
        $user = \Model\Users::GetInstance();
        $testExists = $user->SelectByCondition("UserName = '$UserName' AND Id <> $id");
        if (!empty($testExists))
            return -1;
        $testExistsEmail = $user->SelectByCondition("UserEmail  = '$UserEmail' AND Id <> $id");
        if (!empty($testExistsEmail))
            return -2;
        if (!$createFromAdmin && (empty($UserPassword) || $UserPassword != $UserPassword2))
            return -3;
        if ($newUser) {
            $user->UserName = $UserName;
            $user->UserPassword = $UserPassword;
        } else {
            $user->GetObjectById($id, TRUE);
        }
        if (empty($defaultLang))
            $defaultLang = \Utils\Utils::GetDefaultLang();
        $user->Id = $id;
        $user->FirstName = $FirstName;
        $user->LastName = $LastName;
        $user->UserEmail = $UserEmail;
        $user->BlockDiscusion = $BlockDiscusion;
        $user->IsActive = $IsActive;
        $user->DefaultLang = $defaultLang;
        $id = $user->SaveObject();
        $userInGroup = new \Objects\UsersGroups();
        $userInGroup->DeleteByCondition("UserId = $id");
        $userInGroup->AddUserToGroup($id, $MainUserGroup, TRUE);


        if ($newUser && !empty($profile)) {
            $ud = new \Objects\UserDomains();
            $profile["Id"] = $id;
            $profile["DomainIdentificator"] = "UserProfile";
            $ud->SaveUserDomainData($profile);
        }
        $data = array();
        $data[0][0] = "FirstName";
        $data[0][1] = $user->FirstName;
        $data[1][0] = "LastName";
        $data[1][1] = $user->LastName;
        $data[2][0] = "UserName";
        $data[2][1] = $user->UserName;
        $data[3][0] = "UserEmail";
        $data[3][1] = $user->UserEmail;
        $x = 4;
        foreach ($profile as $k => $v) {
            $data[$x][0] = $k;
            $data[$x][1] = $v;
            $x++;
        }
        if ($sendEmailAdmin) {
            $mail = new Mail();
            if ($adminActivation) {
                $data[$x][0] = "linkactivate";
                $data[$x][1] = $this->GenerateLinkActivate($UserPassword, $UserEmail, $id, $UserName);
                $x++;
            }
            $mail->SendEmail($user->UserEmail, $adminMail, $adminMailId, $data);
        }
        if ($sendUserEmail) {
            $mail = new Mail();
            if ($userEmailActivate) {
                $data[$x][0] = "linkactivate";
                $data[$x][1] = $this->GenerateLinkActivate($UserPassword, $UserEmail, $id, $UserName);
            }

            $mail->SendEmail($sendEmailUserFrom, $user->UserEmail, $userMailId, $data);
        }
        return $id;
    }

    private function validatePassword() {
        
    }

    public function UserLogin($userName, $password, $emailLogin = false) {
        $badLogins = new \Objects\BadLogins();
        if ($badLogins->GetBadsLogins() >= 3) {
            $this->IsBadLogin = true;
            return false;
        }

        if (empty($userName) || empty($password)) {
            $badLogins->AddBadLogin();
            return false;
        }

        $res = null;
        if ($emailLogin)
            $res = dibi::query("SELECT * FROM LOGINUSERVIEW WHERE (UserName = %s  OR UserEmail = %s )AND UserPassword = %s", $userName, $userName, MD5(SHA1($password)))->fetchAll();
        else
            $res = dibi::query("SELECT * FROM LOGINUSERVIEW WHERE UserName = %s AND UserPassword = %s", $userName, MD5(SHA1($password)))->fetchAll();


        if (empty($res)) {

            $badLogins->AddBadLogin();
            return FALSE;
        }
        $userData = $res[0];

        self::$SessionManager->SetSessionValue("UserId", $userData->UserId);

        self::$SessionManager->SetSessionValue("UserGroupId", $userData->GroupId);
        self::$SessionManager->SetSessionValue("UserGoupIdentificator", $userData->GroupIdentificator);
        self::$SessionManager->SetSessionValue("FullUserName", $userData->FullName);
        self::$SessionManager->SetSessionValue("UserName", $userData->UserName);
        if (!empty($userData->DefaultLang)) {
            self::$SessionManager->SetSessionValue("AdminUserLang", $userData->DefaultLang);
        }

        if ($this->IsLoginUser()) {
            $badLogins->RemoveAllBadLogins();
            return TRUE;
        }
        $badLogins->AddBadLogin();
        return FALSE;
    }

    public function BlockDiscusionUser($userId) {
        dibi::query("UPDATE Users  SET BlockDiscusion = 1 WHERE Id = %i ", $userId);
    }

    public function UserHasBlockDiscusion() {
        $model = new \Model\Users();
        $model->GetObjectById($this->GetUserId(), true, array("BlockDiscusion"));
        return $model->BlockDiscusion;
    }

    public function GetFullUserName() {
        if (!self::$SessionManager->IsEmpty("FullUserName"))
            return self::$SessionManager->GetSessionValue("FullUserName");
    }

    public function GetUserGroupId() {
        if (!self::$SessionManager->IsEmpty("UserGroupId"))
            return self::$SessionManager->GetSessionValue("UserGroupId");

        $groupData = $this->GetUserGroupByIdeticator("anonymous");
        self::$SessionManager->SetSessionValue("UserGroupId", $groupData->Id);
        return $groupData->Id;
    }

    public function GetOtherUserGroups() {

        if (self::$SessionManager->IsEmpty("OtherUserGroups")) {
            if ($this->IsLoginUser()) {
                $userId = $this->GetUserId();
                $res = dibi::query("SELECT GroupId FROM UsersInGroup WHERE UserId = %i AND IsMainGroup = 0", $userId)->fetchAll();
                self::$SessionManager->SetSessionValue("OtherUserGroups", $res);

                return $res;
            }
            return null;
        }
        return self::$SessionManager->GetSessionValue("OtherUserGroups");
    }

    public function UserActivate($id) {
        dibi::query("UPDATE Users  SET IsActive  = 1 WHERE Id = %i ", $id);
    }

    public function IsLoginUser() {
        if (self::$SessionManager->IsEmpty("UserGroupId"))
            return FALSE;
        if (self::$SessionManager->GetSessionValue("UserGroupId") == 0)
            return FALSE;


        $groupData = $this->GetAnonymousGroup();
        if ($groupData["Id"] == self::$SessionManager->GetSessionValue("UserGroupId"))
            return FALSE;
        return TRUE;
    }

    public function GetUserGroupIdentificator() {

        if (self::$SessionManager->IsEmpty("UserGoupIdentificator"))
            return "";
        return self::$SessionManager->GetSessionValue("UserGoupIdentificator");
    }

    public function GetUserId() {

        $user = \Model\Users::GetInstance();
        if (!self::$SessionManager->IsEmpty("UserId"))
            return self::$SessionManager->GetSessionValue("UserId");

        if (self::$SessionManager->IsEmpty("AnonymousUserId")) {
            $res = $user->GetFirstRow($user->SelectByCondition("UserName = 'anonymous'", "", array("Id")));
            self::$SessionManager->SetSessionValue("AnonymousUserId", $res["Id"]);
        }
        return self::$SessionManager->GetSessionValue("AnonymousUserId");
    }

    public function UserLogout() {
        self::$SessionManager->UnsetKey("UserId");
        self::$SessionManager->UnsetKey("UserName");
        self::$SessionManager->UnsetKey("UserGroupId");
        self::$SessionManager->UnsetKey("UserGoupIdentificator");
        self::$SessionManager->UnsetKey("IsSystemUser");
        self::$SessionManager->UnsetKey("OtherUserGroups");
    }

    public function GetUserDetail($userId) {
        $model = new \Model\Users();
        return $model->GetObjectById($userId);
    }

    public function IsSystemUser() {

        if (self::$SessionManager->IsEmpty("IsSystemUser") || self::$SessionManager->GetSessionValue("IsSystemUser") == true) {
            $model = new \Model\Users();
            $model->GetObjectById($this->GetUserId(), true, array("UserName"));
            if ($model->UserName == "system") {
                self::$SessionManager->SetSessionValue("IsSystemUser", true);
                return true;
            }

            self::$SessionManager->SetSessionValue("IsSystemUser", false);
            return false;
        }
        return self::$SessionManager->GetSessionValue("IsSystemUser");
    }

    public function ChangePassword($password1, $password2, $userId) {
        $model = new \Model\Users();
        $this->validatePassword();
        if (empty($password1) || empty($password2)) {
            return;
        }
        if ($password1 != $password2) {
            return;
        }
        $model->SetValidateRule("UserPassword", \Types\RuleType::$Hash);
        $model->GetObjectById($userId, true);
        $model->UserPassword = $password1;
        $model->SaveObject();
    }

    public function GetUserName() {

        if (self::$SessionManager->IsEmpty("UserName")) {
            return "";
        }
        return self::$SessionManager->GetSessionValue("UserName");
    }

    private function GenerateLinkActivate($userPassword, $userEmail, $id, $userName) {
        $content = new \Objects\Content();
        $userActivationId = $content->GetIdByIdentificator("userActivation");
        $userActivation = $content->GetUserItemDetail($userActivationId, $this->GetUserGroupId(), $this->GetActualWeb(), $this->GetLangIdByWebUrl());
        if (count($userActivation) > 0) {
            return SERVER_NAME_LANG . $userActivation[0]["SeoUrl"] . "/" . base64_encode(StringUtils::HashString($userPassword) . "#" . $userEmail . "#" . $id . "#" . $userName) . "/";
        }
    }

    public function SetUserGroupModules($userGroup, $module) {
        $model = new \Model\UserGroupsModules();
        $model->UserGroupId = $userGroup;
        $model->ModuleId = $module;
        $model->SaveObject();
    }

    public function GetUserGroupDetail($id) {
        $res = dibi::query("SELECT * FROM USERGROUPDETAIL WHERE Id = %i", $id)->fetchAll();
        $res = ArrayUtils::ColummToArray($res, "ModuleId", "UserWebId");
        return $res;
    }

    public function GetUserGroupByIdeticator($identificator) {
        $userGroup = new \Model\UserGroups();
        $res = $userGroup->SelectByCondition("GroupIdentificator = '$identificator'");
        return $userGroup->GetFirstRow($res);
    }

    public function GetAnonymousGroup() {

        if (self::$SessionManager->IsEmpty("AnonymousInfo")) {
            $res = $this->GetUserGroupByIdeticator("anonymous");
            $res = \Utils\ArrayUtils::ObjectToArray($res);
            self::$SessionManager->SetSessionValue("AnonymousInfo", $res);
        }
        return self::$SessionManager->GetSessionValue("AnonymousInfo");
    }

    public function GetSystemGroups() {
        $userGroup = \Model\UserGroups::GetInstance();
        $condition = "IsSystemGroup = 1 AND Deleted = 0";
        if ($this->IsSystemUser()) {
            return $userGroup->SelectByCondition($condition);
        }
        return $userGroup->SelectByCondition($condition . " AND GroupName <> 'system'");
    }

    public function GetNoSystemGroups() {
        $userGroup = \Model\UserGroups::GetInstance();
        $condition = "IsSystemGroup = 0 AND Deleted = 0";
        return $userGroup->SelectByCondition($condition);
    }

    public function ChangeSystemGroupToAdmin() {

        if ($this->IsSystemUser()) {
            $userGroup = \Model\UserGroups::GetInstance();
            $res = $userGroup->SelectByCondition("GroupIdentificator = 'Administrators'");
            return $res[0]["Id"];
        }
        return 0;
    }

    public function GetUserGroups($removeIdentificator = array()) {
        $userGroup = \Model\UserGroups::GetInstance();
        if (empty($removeIdentificator))
            return $userGroup->SelectByCondition("Deleted = 0");
        $where = "";

        for ($i = 0; $i < count($removeIdentificator); $i++) {
            $identificator = $removeIdentificator[$i];
            if (empty($identificator))
                continue;
            if (empty($where))
                $where = " GroupIdentificator <>'" . $identificator . "' ";
            else
                $where .= " AND GroupIdentificator <> '" . $identificator . "'";
        }
        if ($this->IsSystemUser())
            return $userGroup->SelectByCondition("($where) AND Deleted = 0 ");

        return $userGroup->SelectByCondition("($where) AND Deleted = 0 AND   GroupIdentificator <>'system'");
    }

}
