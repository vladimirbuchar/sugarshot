<?php
namespace Utils;
class GlobalStaticVariables {
    public function SetVariable($name,$value)
    {
        $_SESSION["StaticVariables"][$name] = $value;
    }
    
    public function GetVariable($name)
    {
        if ($this->IsEmpty($name)) return null;
        return $_SESSION["StaticVariables"][$name];
    }
    
    public function ClearValue($name)
    {
        unset ($_SESSION["StaticVariables"][$name]);
    }
    
    public function IsEmpty($name)
    {
        if (empty($_SESSION["StaticVariables"][$name])) return true;
        return false;
    }
}
