function FrontendSearch()
{
  var value = $("#SearchInput").val();
  var url = CallPhpFunctionAjax("Ajax","Search","POST",value);
  window.location.href=url;
  
}