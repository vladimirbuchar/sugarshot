    function Recovery(id)
    {
        var params = {Id:id};
        CallPhpFunctionAjax("WebEdit","RecoveryItem","POSTOBJECT",params);
        $("#"+id).remove();
    }