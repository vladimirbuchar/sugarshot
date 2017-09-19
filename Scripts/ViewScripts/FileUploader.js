    $(document).ready(function(){
        $("#OtherLang").change(function(){
            Save(false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/FileFolder/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
        
       $('#ActiveFrom').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
        $('#ActiveTo').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
         
    });
    
    function Save(publish)
    {
        ShowLoading();
        var params = PrepareParametrs("itemForm");
        params.Publish = publish;
        params.Id = $("#ObjectId").val();
        
        var privileges = ReadUserPrivileges("userSecurity");
        params.Privileges = privileges;
        
        var domainValues = PrepareParametrs("parametrs");
        params.Parametrs = domainValues;
        
        var outId = CallPhpFunctionAjax("WebEdit","SaveFile","POSTOBJECT",params);
        $("#ObjectId").val(outId);
        LoadData(outId,"fileupload");
        HideLoading();
        
    }
    
    function LoadUserDomain(domainDentifcator,data)
    {
        var param = {Identifcator: domainDentifcator, ObjectId:$("#ObjectId").val()}
        var html = CallPhpFunctionAjax("WebEdit","GetDomainByIdentificator","POSTOBJECT",param);
        $("#parametrs").html(html);
         
   }