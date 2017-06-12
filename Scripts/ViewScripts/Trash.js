    function Recovery(id)
    {
        var params = new Array();
        var ar1 =  new Array();
        ar1[0] = "Id";
        ar1[1] = id;
        params[0] = ar1;
        CallPhpFunctionAjax("WebEdit","RecoveryItem","POST",params);
        $("#"+id).remove();
    }