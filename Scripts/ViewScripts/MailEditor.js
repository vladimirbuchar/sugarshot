$(document).ready(function(){
        $("#OtherLang").change(function(){
             Save(false,false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POST",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/MailEditor/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });

});
     
    function Save(publish,checkValid)
    {
       ShowLoading();
        var params = PrepareParametrs("itemForm");
        var nextItem = params.length;
        var ar1 =  new Array();
        ar1[0] = "Publish";
        ar1[1] = publish;
        params[nextItem] = ar1;
        nextItem++;
        
        var ar2 = new Array();
        ar2[0] = "Id";
        ar2[1] = $("#ObjectId").val();
        params[nextItem] = ar2;
        nextItem++;
        
        var privileges = ReadUserPrivileges("userSecurity");
        var ar3 = new Array();
        ar3[0] = "Privileges";
        ar3[1] = privileges;
        params[nextItem] = ar3;
        nextItem++;
        var outId = CallPhpFunctionAjax("WebEdit","SaveEmail","POST",params);
        $("#ObjectId").val(outId);
        LoadData(outId,"mail");
        HideLoading();
    }