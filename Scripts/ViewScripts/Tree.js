
function SetCopyMoveAction(action)
    {
        var html = CallPhpFunctionAjax("WebEdit", "GetTreeCopyDialog", "POST", null);
        $("#dialogCopyMoveTree").html(html);
        copyModeAction = action;
    }
    
    