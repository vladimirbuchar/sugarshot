 function SavePrivileges()
    {
        var privileges = ReadUserPrivileges("userSecurity");
        var params = {Id:$("#Id").val() ,privileges:  privileges};
        
        CallPhpFunctionAjax("Settings","SaveWebPrivileges","POSTOBJECT",params);
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
        CallPhpFunctionAjax("Settings","SetDefaultWebPriviles","POSTOBJECT",{id:id});
    }