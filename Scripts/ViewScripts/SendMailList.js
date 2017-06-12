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
        var params = new Array();
        var ar1 = new Array();
        ar1[0] = "Mails";
        ar1[1] = sendId;
        params[0] =ar1;
        CallPhpFunctionAjax("WebEdit", "ReSendMails", "POST", params);
        
    
}