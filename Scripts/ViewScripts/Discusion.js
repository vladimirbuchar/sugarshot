
    function Save()
    {
        var params = PrepareParametrs("itemForm");
        var nextItem = params.length;
        var ar2 = new Array();
        ar2[0] = "ObjectId";
        ar2[1] = $("#ObjectId").val();
        params[nextItem] = ar2;
        nextItem++;
        var privileges = ReadUserPrivileges("userSecurity");
        var ar3 = new Array();
        ar3[0] = "Privileges";
        ar3[1] = privileges;
        params[nextItem] = ar3;
        nextItem++;
        var outId = CallPhpFunctionAjax("WebEdit","SaveDiscusion","POST",params);
        $("#ObjectId").val(outId);
    }
    
    
    
    
    
    
    