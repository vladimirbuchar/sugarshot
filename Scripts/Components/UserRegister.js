function UserRegister()
{
  var params = PrepareParametrs("userRegister");
  var out = CallPhpFunctionAjax("Ajax","UserRegister","JSON",params);
  /*var error = out.Error;
  if (error =="error")
  {
      return;
  }*/
  
  
  
}