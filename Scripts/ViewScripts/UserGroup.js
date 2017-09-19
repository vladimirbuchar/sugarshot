$(document).ready(function(){
             $("#ConnectedWord").change(function(){
             var value = $(this).val();
             $("#GroupName").val(value);
          });
          });
          function OnAfterSaveItem(id)
          {
                var webList = PrepareParametrs("webList");
                var parametrs = {UserGroupId:id,WebList:webList}
                
                CallPhpFunctionAjax("UsersItem","SaveUsersGroupWeb","POSTOBJECT",parametrs);
                
                
                var moduleList = PrepareParametrs("moduleList");
                var parametrsmoduleList = {UserGroupId:id, ModuleList:moduleList}
                CallPhpFunctionAjax("UsersItem","SaveUsersModules","POSTOBJECT",parametrsmoduleList);
                
          }