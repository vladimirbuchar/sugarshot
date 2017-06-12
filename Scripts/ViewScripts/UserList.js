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
           var params = new Array();
           var ar = new Array ();
           ar[0]="UserId";
           ar[1]=id;
           params[0] = ar;
           var mainGroup = CallPhpFunctionAjax("Ajax","GetUserGroupMain","POST",params);
           $("#MainUserGroup").val(mainGroup);
           var userGroups  = CallPhpFunctionAjax("Ajax","GetUserGroupMinority","JSON",params);
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
       var params = new Array();
       var ar = new Array ();
       ar[0]="UserId";
       ar[1]=id;
       params[0] = ar;
       var ar1 = new Array ();
       ar1[0]="password1";
       ar1[1]=$("#PassowordChange1").val();
       params[1] = ar1;
       var ar2 = new Array ();
       ar2[0]="password2";
       ar2[1]=$("#PassowordChange2").val();
       params[2] = ar2;
       CallPhpFunctionAjax("UsersItem","ChangePassword","POST",params);
       
   }
   function OnAfterSaveItem(id)
   {
       var params = PrepareParametrs("OtherGroups");
       var length =params.length;
       var ar1 = new Array();
       ar1[0] = "UserId";
       ar1[1] =id;
       params[length] = ar1;
       CallPhpFunctionAjax("UsersItem","SaveOtherUserGroups","POST",params);
   }