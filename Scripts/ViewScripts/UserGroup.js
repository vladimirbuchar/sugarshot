$(document).ready(function(){
             $("#ConnectedWord").change(function(){
             var value = $(this).val();
             $("#GroupName").val(value);
          });
          });
          function OnAfterSaveItem(id)
          {
                var webList = PrepareParametrs("webList");
                var parametrs = new Array();
                var p1 = new Array();
                p1[0] = "UserGroupId";
                p1[1] = id;
                parametrs[0] = p1;
                var p2 = new Array();
                p2[0] = "WebList";
                p2[1] = webList;
                parametrs[1] = p2;
                CallPhpFunctionAjax("UsersItem","SaveUsersGroupWeb","POST",parametrs);
                
                
                var moduleList = PrepareParametrs("moduleList");
                var parametrsmoduleList = new Array();
                var p1 = new Array();
                p1[0] = "UserGroupId";
                p1[1] = id;
                parametrsmoduleList[0] = p1;
                var p2 = new Array();
                p2[0] = "ModuleList";
                p2[1] = moduleList;
                parametrsmoduleList[1] = p2;
                CallPhpFunctionAjax("UsersItem","SaveUsersModules","POST",parametrsmoduleList);
                
          }