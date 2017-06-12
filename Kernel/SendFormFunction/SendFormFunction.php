<?php
namespace SendFormFunction; 
class SendFormFunction
{
    private $_params = array();
    private $_result = array();
    protected $FunctionType;
    
    
    public function SetParameter($key,$value)
    {
        $this->_params[$key] = $value;
    }
    
    protected function GetParameter($key)
    {
        return $this->_params[$key];
    }
    
    protected function SetResult($key,$value)
    {
        $this->_result[$key] = $value;
    }
    
    public function GetResult()
    {
        return $this->_result;
    }
    
    public function IsBeforeFunction()
    {
        return $this->FunctionType == \Types\SendFormFunctionTypes::$Before;;
    }
    public function IsAfterFunction()
    {
        return $this->FunctionType == \Types\SendFormFunctionTypes::$After;
    }
    public function GetParametrsFromSaveData($paramname)
    {   
        $saveData  = $this->GetParameter("SaveData");
        $data = \Utils\ArrayUtils::GetNormalArray($saveData,$paramname);
        if (!empty($data))
            return $data[$paramname];
        return "";
        
        
    }

    
    
    
}

