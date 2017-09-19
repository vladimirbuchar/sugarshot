<?php

namespace Controller;

use Model\DiscusionItems;

class AjaxApi extends ApiController {

    public function __construct() {
        parent::__construct();
        $this->SetControllerPermition(array("*"));
        $this->SetApiFunction("GetLastUrl", array("*"));
        $this->SetApiFunction("GetUserGroupMain", array("system", "Administrators"));
        $this->SetApiFunction("Filter", array("*"));
        $this->SetApiFunction("UserLogout", array("*"));
        $this->SetApiFunction("SaveSurveyAnswer", array("*"));
        $this->SetApiFunction("GetUserGroupMinority", array("*"));
        $this->SetApiFunction("DeleteUserItem", array("system", "Administrators", "RegisteredUser"));
        $this->SetApiFunction("SaveUserProfile", array("system", "Administrators", "RegisteredUser"));
        $this->SetApiFunction("Search", array("*"));
        $this->SetApiFunction("UserLogin", array("*"));
        $this->SetApiFunction("UserRegister", array("*"));
        $this->SetApiFunction("AddDiscusionItem", array("*"));
        $this->SetApiFunction("HistoryItemDetail", array("*"));
        $this->SetApiFunction("DiscusionItemDetail", array("*"));
        $this->SetApiFunction("DeleteDiscusionItem", array("*"));
    }

    public function GetLastUrl() {
        return $_SERVER['HTTP_REFERER'];
    }

    public function SaveSurveyAnswer($ajax) {
        
        $content = new \Objects\Content();
        $id = $ajax["ParentId"];
        unset($ajax["ParentId"]);
        $content->CreateSurveyAnswer($this->LangId, $id, $ajax);
    }

    public function GetUserGroupMain($ajaxParametrs) {
        $userId = $ajaxParametrs["UserId"];
        $userGroup = new \Objects\UsersGroups();
        return $userGroup->GetMainUserGroup($userId);
    }

    public function GetUserGroupMinority($ajaxParametrs) {
        $userId = $ajaxParametrs["UserId"];
        $userGroup = new \Objects\UsersGroups();
        return $userGroup->GetMinorityUserGroup($userId);
    }

    public function UserLogout() {
        self::$User->UserLogout();
        $this->Referesch();
    }

    public function DeleteUserItem($params) {
        $content = new \Objects\Content();
        $content->DeleteItem($params["id"]);
    }

    public function SaveUserProfile($ajax) {
        $user = new Users();
        $user->GetObjectById(self::$UserId, true);
        $user->FirstName = $ajax["FirstName"];
        $user->LastName = $ajax["LastName"];
        $user->UserEmail = $ajax["UserEmail"];
        $user->SaveObject();
        unset($ajax["FirstName"]);
        unset($ajax["LastName"]);
        unset($ajax["UserEmail"]);
        unset($ajax["DomainIdentificator_UserProfile"]);
        unset($ajax["ObjectId_UserProfile"]);
        unset($ajax["UserName"]);
        $udv = new \Objects\UserDomains();
        $domainName = "UserProfile";
        $preparedData = array();
        $x = 0;
        foreach ($ajax as $key => $value) {
            $keys = explode("_", $key);
            $preparedData[$x] = new UserDomainValue($keys[2], $keys[1], $value);
            $x++;
        }
        $udv->SaveDomainValue($domainName, self::$UserId, $preparedData);
    }

    public function Search($ajax) {
        $contentVersion = new \Objects\Content();
        $seourl = $contentVersion->GetSeoUrlByIdentificator("search", $this->LangId);
        return $seourl . base64_encode($ajax["Search"]) . "/";
    }

    public function UserLogin($ajax) {
        $web = \Model\Webs::GetInstance();
        $web->GetObjectById($this->WebId,true,array("AfterLoginAction","AfterLoginUrl","EmailUserLogin"));
        $outData = array();
        $outData["Error"] = "";
        $outData["AfterLoginAction"] = "";
        $outData["AfterLoginUrl"] = "";
        if (!self::$User->UserLogin($ajax["UserName"], $ajax["UserPassword"], $web->EmailUserLogin)) {
            self::$User->UserLogout();
            $outData["Error"] = "error";
        } else {
            if ($web->AfterLoginAction == "gohomepage") {
                $outData["AfterLoginUrl"] = SERVER_NAME_LANG;
            } else if ($web->AfterLoginAction == "gotourl") {
                $outData["AfterLoginUrl"] = $web->AfterLoginUrl;
            } else if ($web->AfterLoginAction == "staypage") {
                $outData["AfterLoginUrl"] = "staypage";
            }
        }

        return $outData;
    }

    public function UserRegister($profile) {
        
        $user = new \Objects\Users();

        $profile = $ajax;
        unset($profile["FirstName"]);
        unset($profile["LastName"]);
        unset($profile["Email"]);
        unset($profile["UserName"]);
        unset($profile["UserPassword"]);
        unset($profile["UserPassword2"]);
        unset($profile["DomainIdentificator"]);
        $status = $user->RegistrationUser($ajax["FirstName"], $ajax["LastName"], $ajax["Email"], $ajax["UserName"], $ajax["UserPassword"], $ajax["UserPassword2"], $profile);
        return $status;
    }

    public function AddDiscusionItem($ajaxParametrs) {
        $discusion = new \Objects\Discusion();
        $id = $ajaxParametrs["Id"];
        $subject = $ajaxParametrs["SubjectDiscusion"];
        $showUserName = $ajaxParametrs["ShowUserName"];
        $text = $ajaxParametrs["TextDiscusion"];
        $parent = $ajaxParametrs["ParentIdDiscusion"];
        $discusionId = $ajaxParametrs["DiscusionId"];
        $discusion->AddNewDiscusionItem($subject, $text, $showUserName, $parent, $discusionId, $id);
    }

    public function HistoryItemDetail($ajaxParametrs) {
        $id = $ajaxParametrs["DiscusionItem"];
        $discusion = new \Objects\Discusion();
        return $discusion->GetHistoryItemDetail($id);
    }

    public function DiscusionItemDetail($ajaxParametrs) {
        $id = $ajaxParametrs["DiscusionItem"];
        $discusion = DiscusionItems::GetInstance();
        return $discusion->GetObjectById($id);
    }

    public function DeleteDiscusionItem($ajaxParametrs) {
        $id = $ajaxParametrs["DiscusionItem"];
        $discusion = DiscusionItems::GetInstance();
        $discusion->DeleteObject($id);
    }

    public function ReloadComponent() {
        
    }

}
