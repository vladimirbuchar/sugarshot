$(document).ready(function(){
           
         $("#ConnectedWord").change(function(){
             var value = $(this).val();
             
                $("#DomainName").val(value);
          });
        });