 function SavePrivileges()
    {
        var params = new Array();
        var ar1= new Array();
        ar1[0] = "Id";
        ar1[1] = $("#Id").val();
        params[0] = ar1;
        var privileges = ReadUserPrivileges("userSecurity");
        var ar2= new Array();
        ar2[0] = "privileges";
        ar2[1] = privileges;
        params[1] = ar2;
        CallPhpFunctionAjax("Settings","SaveWebPrivileges","POST",params);
    }
    function OnLoadItemDetail()
    {
        var id = $("#Id").val();
        if (id == "")
        {
            return;
        }
       
        var privileges = $("#WebPrivileges").val();
        var inputs = $("#userSecurity input");
        var xml = $.parseXML(privileges);
        for(var i = 0; i<inputs.length;i++)
        {
            var input = $(inputs[i]);
            input.prop("checked", false);
            var privilegesName = input.attr("class");
            var groupId = input.val();
            $(xml).find("item").each(function () {
            if (privilegesName == $(this).find('PrivilegesName').text()  && groupId== $(this).find('UserGroup').text() && $(this).find('Value').text() =="true") 
            {
                input.prop("checked", true);
            }
            
        });   
        }
    }
    function OnAfterSaveItem(id)
    {
        CallPhpFunctionAjax("Settings","SetDefaultWebPriviles","POST",id);
    }