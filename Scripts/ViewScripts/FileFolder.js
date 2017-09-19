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
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/FileFolder/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
        });
         
    });
    
    function Save(publish)
    {
        var params = PrepareParametrs("itemForm");
        params.Publish = publish;
        params.Id = $("#ObjectId").val();
        
        var privileges = ReadUserPrivileges("userSecurity");
        params.Privileges = privileges;
        var outId = CallPhpFunctionAjax("WebEdit","SaveFolderFile","POSTOBJECT",params);
        LoadData(outId,"filefolder");
        $("#ObjectId").val(outId);
        
    }
    