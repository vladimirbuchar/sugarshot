<?php
namespace Model;
class UserDetail extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDetail";
        $this->SqlView = "SELECT `Users`.`Id`,`Users`.`UserName`,`Users`.`FirstName`,`Users`.`LastName`, CONCAT(`Users`.`FirstName`,' ',`Users`.`LastName`) AS FullName,
                `Users`.`UserEmail`,mainUserGroup.GroupId AS MainUserGroup ,`Users`.BlockDiscusion
                FROM `Users` 
                LEFT JOIN UsersInGroup AS mainUserGroup ON Users.Id = mainUserGroup.UserId AND mainUserGroup.IsMainGroup = 1";
    }
    public function TableExportSettings()
    {
        
    }


}
