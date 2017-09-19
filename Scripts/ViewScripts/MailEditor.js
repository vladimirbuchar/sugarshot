$(document).ready(function(){
        $("#OtherLang").change(function(){
             Save(false,false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/MailEditor/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });

});
     
    function Save(publish,checkValid)
    {
       ShowLoading();
        var params = PrepareParametrs("itemForm");
        
        params.Publish = publish;
        params.Id = $("#ObjectId").val();
        var privileges = ReadUserPrivileges("userSecurity");
        params.Privileges = privileges;
        var outId = CallPhpFunctionAjax("WebEdit","SaveEmail","POSTOBJECT",params);
        $("#ObjectId").val(outId);
        LoadData(outId,"mail");
        HideLoading();
    }