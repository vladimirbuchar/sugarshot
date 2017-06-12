    $(document).ready(function(){
       $('#ActiveFrom').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
        $('#ActiveTo').datetimepicker({
            dayOfWeekStart : 1,
            lang:'cs'
        });
                $("#OtherLang").change(function(){
                    Save(false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POST",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/FileFolder/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
         
    });
    
    function Save(publish)
    {
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
        var outId = CallPhpFunctionAjax("WebEdit","SaveFolderFile","POST",params);
        LoadData(outId,"filefolder");
        $("#ObjectId").val(outId);
        
    }
    