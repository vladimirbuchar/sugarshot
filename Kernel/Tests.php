<?php

class Tests {
    protected $Result;
    private $_startTime;
    private $_endTime;
    private $_isError = false;
    
    public function StartTest()
    {
        try{
            $this->_startTime = microtime();
            $this->Result=  $this->RunTest();
            $this->_endTime = microtime();
        }
        catch (Exception $ex){
            $this->Result = $ex;
            $this->_isError = true;
            $this->_endTime = microtime();
        }
    }
    public function GetResult()
    {
        return $this->Result;
    }
    public function GetTime()
    {
        return $this->_endTime - $this->_startTime;
    }
    
    public function IsError()
    {
        return $this->_isError;
    }
    
    
    
}
