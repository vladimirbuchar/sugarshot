  $(document).ready(function(){
       $("#StartExport").click(function(){
          var param = PrepareParametrs("SettingCopy") ;
          CallPhpFunctionAjax("Settings","StartCopyLang","POST",param) ;
       });
    });