$(document).ready(function(){
       $("#StartExport").click(function(){
          var param = PrepareParametrs("settingTemplate") ;
          var url = CallPhpFunctionAjax("Settings","ExportSettings","POST",param);
          window.location.href= url;
       });
    });