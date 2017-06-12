 function ShowPreview(id)
    {
        var url = CallPhpFunctionAjax("WebEdit","GetArticleUrl","POST",id);
        window.open(url+"?preview=true");
    }
    
    function EditItem(type,id)
    {
        var viewName = "";
        if (type=="Javascript")
            viewName = "JsEditor";
        if (type=="UserItem")
            viewName = "Detail";
        if (type=="Form")
            viewName = "FormEditor";
        if (type=="Css")
            viewName = "CssEditor";
        if (type=="Template")
            viewName = "TemplateDetail";
        if (type=="Mail")
            viewName = "MailEditor";
        
        var webId = $("#WebId").val();
        var langId = $("#LangId").val();
        var url = "/xadm/WebEdit/"+viewName+"/"+webId+"/"+langId+"/"+id+"/0/";
        window.location.href = url;
    }
    
    function PublishItem(id)
    {
        var out = CallPhpFunctionAjax("WebEdit","PublishItem","POST",id);
        if (out =="true")
        {
            $("#row_"+id).remove();
        }
        
    }