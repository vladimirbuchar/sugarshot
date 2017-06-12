  $(document).ready(function(){
       $("#StartClean").click(function(){
          var param = PrepareParametrs("CleanSettings") ;
          CallPhpFunctionAjax("Settings","StartCleanDatabase","POST",param) ;
       });
    });