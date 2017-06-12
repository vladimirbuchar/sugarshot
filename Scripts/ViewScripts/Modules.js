$(document).ready(function(){
           
         $("#ConnectedWord").change(function(){
             var value = $(this).val();
             
                $("#ModuleName").val(value);
          });
        });