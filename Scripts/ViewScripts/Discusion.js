
    function Save()
    {
        var params = PrepareParametrs("itemForm");
        var privileges = ReadUserPrivileges("userSecurity");
        params.ObjectId =  $("#ObjectId").val();
        params.Privileges =  privileges;
        var outId = CallPhpFunctionAjax("WebEdit","SaveDiscusion","POSTOBJECT",params);
        $("#ObjectId").val(outId);
    }
    
    
    
    
    
    
    