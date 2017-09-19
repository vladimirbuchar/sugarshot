 function OnLoadItemDetail()
    {
        var id = $("#Id").val();
        var params = {Id:id}
        var out = CallPhpFunctionAjax("Settings","GetMailingItemDetail","JSONOBJECT",params);
         var mailingGroups = out["MailingGroups"];
         
         for (var i = 0; i< mailingGroups.length; i++)
         {
             var mg = mailingGroups[i].GroupId;
             $("#MailingGroupName_"+mg).attr("checked","checked");
         }
    }
    function OnAfterSaveItem(id)
   {
       var mailingGroups = PrepareParametrs("mailingGroups");
       var params = {MailingGroups:mailingGroups,Id:id};
       CallPhpFunctionAjax("Settings","SaveMailinContact","POSTOBJECT",params);
   }