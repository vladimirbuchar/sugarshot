
function SetCopyMoveAction(action)
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetTreeCopyDialog", "POSTOBJECT", null);
        $("#dialogCopyMoveTree").html(html);
        copyModeAction = action;
    }
    
    