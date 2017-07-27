<?php
namespace SendFormFunction; 
class SendFormFunction
{
    private $_params = array();
    private $_result = array();
    protected $FunctionType;
    
    /** 
     * @var \Utils\SessionManager
     */
    protected static $SessionManager = null;
   
    public function __construct() {
        if (self::$SessionManager == null)
        self::$SessionManager = new \Utils\SessionManager();        
    }
    
    
    public function SetParameter($key,$value)
    {
        $this->_params[$key] = $value;
    }
    
    protected function GetParameter($key)
    {
        return $this->_params[$key];
    }
    
    protected function GetAllParametrs()
    {
        return $this->_params;
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

