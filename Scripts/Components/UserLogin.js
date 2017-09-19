function UserLogin()
{
  var params = PrepareParametrs("userLogin");
  var out = CallPhpFunctionAjax("Ajax","UserLogin","JSONOBJECT",params);
  var error = out.Error;
  if (error =="error")
  {
      return;;
  }
  if (out.AfterLoginUrl =="staypage")
  {
      window.location.href="";
  }
  else 
  {
      window.location.href=out.AfterLoginUrl;
  }
  
  
}