$(document).ready(function(){
       $("#StartExport").click(function(){
          var param = PrepareParametrs("settingTemplate") ;
          var url = CallPhpFunctionAjax("Settings","ExportSettings","POSTOBJECT",param);
          window.location.href= url;
       });
    });