$(document).ready(function(){
                    $("#OtherLang").change(function(){
                        SaveTemplate(false);
             SetIgnoreExit(true);
             CallPhpFunctionAjax("WebEdit","ChangeLangVersion","POSTOBJECT",null);
             var selectLang = $(this).val();
             window.location.href= "/xadm/WebEdit/JsEditor/"+$("#WebId").val()+"/"+ selectLang+"/"+$("#ObjectId").val()+"/0/";
             
        });
    editor = CodeMirror(document.getElementById("DataEditor"), {
          mode: "text/javascript",
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
    var privileges = ReadUserPrivileges("userSecurity");
    params.Privileges = privileges;
    params.Id = $("#ObjectId").val();
    params.Publish = publish;
    var outId = CallPhpFunctionAjax("WebEdit","SaveJs","POSTOBJECT",params);
   $("#ObjectId").val(outId);
   LoadData(outId,"js");
   HideLoading();
}