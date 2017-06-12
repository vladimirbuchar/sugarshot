 function OnLoadItemDetail()
    {
        var id = $("#Id").val();
        var params = new Array();
         var ar1 = new Array();
         ar1[0] = "Id";
         ar1[1] = id;
         params[0] = ar1;
         var out = CallPhpFunctionAjax("Settings","GetMailingItemDetail","JSON",params);
         var mailingGroups = out["MailingGroups"];
         
         for (var i = 0; i< mailingGroups.length; i++)
         {
             var mg = mailingGroups[i].GroupId;
             $("#MailingGroupName_"+mg).attr("checked","checked");
         }
    }
    function OnAfterSaveItem(id)
   {
        var params = new Array();
        var mailingGroups = PrepareParametrs("mailingGroups");
         
         var ar1 = new Array();
         ar1[0] = "MailingGroups";
         ar1[1] = mailingGroups;
         params[0] = ar1;
         
        var ar2 = new Array();
         ar2[0] = "Id";
         ar2[1] = id;
         params[1] = ar2;
         CallPhpFunctionAjax("Settings","SaveMailinContact","POST",params);
   }