<?php
namespace Model;
class LoginUserView extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "LoginUserView";
        $this->SqlView = "SELECT CONCAT_WS(' ',`FirstName`,`LastName`) AS FullName, Users.UserName, Users.UserPassword, Users.Id AS UserId,UsersInGroup.GroupId,UserGroups.`GroupIdentificator`,Users.DefaultLang,Users.UserEmail 
                FROM `Users` JOIN UsersInGroup ON UsersInGroup.UserId = Users.Id AND UsersInGroup.IsMainGroup = 1 AND Users.Deleted= 0 AND UsersInGroup.Deleted= 0 
                JOIN UserGroups ON UsersInGroup.GroupId = UserGroups.Id AND UserGroups.Deleted= 0 
                WHERE Users.IsActive = 1
            ";
                
    }
    public function TableExportSettings()
    {
        
    }


}
