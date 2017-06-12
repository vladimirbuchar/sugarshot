    $(document).ready(function(){
        $("#OtherLang").change(function(){
            SaveTemplate(false);
            SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POST",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/CssEditor/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
             
        });
    editor = CodeMirror(document.getElementById("DataEditor"), {
          mode: "text/css",
          extraKeys: {"Ctrl-Space": "autocomplete"},
          value: $("#CssCode").val()
        });
        editor.on('change',function(cMirror){
            $("#CssCode").val(cMirror.getValue());
        });
        });
    

function SaveTemplate(publish)
{
    ShowLoading();
    var params = PrepareParametrs("settingTemplate");
    var nextItem = params.length;
    var privileges = ReadUserPrivileges("userSecurity");
    var ar1 = new Array();
    ar1[0] = "Privileges";
    ar1[1] = privileges;
    params[nextItem] = ar1;
    
    var ar2 = new Array();
    ar2[0] = "Id";
    ar2[1] = $("#ObjectId").val();
    nextItem++;
    params[nextItem] = ar2;
    
    var ar3 = new Array();
    ar3[0] = "Publish";
    ar3[1] = publish;
    nextItem++;
    params[nextItem] = ar3;
   var outId = CallPhpFunctionAjax("WebEdit","SaveCss","POST",params);
   $("#ObjectId").val(outId);
   LoadData(outId,"css");
   HideLoading();
}