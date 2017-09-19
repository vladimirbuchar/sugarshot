   function SendEmails()
    {
        var selectboxs = $(".selectbox");
        var sendId = new Array();
        
        for(var i = 0; i<selectboxs.length; i++)
        {
            var selectbox = $(selectboxs[i]);
            if (selectbox.is(":checked"))
            {
                sendId[i] =selectbox.attr("id");
            }
        }
        var params = {Mails:sendId };
        CallPhpFunctionAjax("WebEdit", "ReSendMails", "POSTOBJECT", params);
        
    
}