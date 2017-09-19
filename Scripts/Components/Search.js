function FrontendSearch()
{
  var value = {Search: $("#SearchInput").val()};
  var url = CallPhpFunctionAjax("Ajax","Search","POSTOBJECT",value);
  window.location.href=url;
  
}