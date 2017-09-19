   function OnLoadItemDetail() 
   {
       var id = $("#Id").val();
       
       if (id == "")
       {
           id = 0;
       }
       $("#passwordGroup input").addClass("required");
       $("#passwordGroup").show();
       if (id > 0)
       {
           $("#passwordGroup").hide();
           $("#passwordGroup input").removeClass("required");
           var params = {UserId: id}
           
           var mainGroup = CallPhpFunctionAjax("Ajax","GetUserGroupMain","POSTOBJECT",params);
           $("#MainUserGroup").val(mainGroup);
           var userGroups  = CallPhpFunctionAjax("Ajax","GetUserGroupMinority","JSONOBJECT",params);
           for(var i = 0; i< userGroups.length; i++)
           {
               var gid = userGroups[i].GroupId;
               $("#group_"+gid).attr("checked","checked");
            }
           
        }
   }
   function PasswordChange()
   {
       var id = $("#Id").val();
       var params = {UserId:id,password1:$("#PassowordChange1").val(), password2: $("#PassowordChange2").val()};
       
       CallPhpFunctionAjax("UsersItem","ChangePassword","POSTOBJECT",params);
       
   }
   function OnAfterSaveItem(id)
   {
       var params = PrepareParametrs("OtherGroups");
       params.UserId = id;
       CallPhpFunctionAjax("UsersItem","SaveOtherUserGroups","POSTOBJECT",params);
   }