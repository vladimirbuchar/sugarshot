    $(document).ready(function(){
        $("#OtherLang").change(function(){
            Save(false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POST",null);
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
        var domainValues = PrepareParametrs("parametrs");
        var ar4 = new Array();
        ar4[0] = "Parametrs";
        ar4[1] = domainValues;
        params[nextItem] = ar4;
        nextItem++;
        var outId = CallPhpFunctionAjax("WebEdit","SaveFile","POST",params);
        $("#ObjectId").val(outId);
        LoadData(outId,"fileupload");
        HideLoading();
        
    }
    
    function LoadUserDomain(domainDentifcator,data)
    {
        var param = Array();
        var ar = Array();
        ar[0] = "Identifcator";
        ar[1] = domainDentifcator;
        param[0] = ar;
        var ar1 = Array();
        ar1[0] = "ObjectId";
        ar1[1] = $("#ObjectId").val();
        param[1] = ar1;    
        var html = CallPhpFunctionAjax("WebEdit","GetDomainByIdentificator","POST",param);
        $("#parametrs").html(html);
         
   }