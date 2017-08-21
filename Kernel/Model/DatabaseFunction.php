<?php

namespace Model;
use Dibi;
class DatabaseFunction extends SqlDatabase{
    /** @var string */
    protected $FunctionCode ="";
    public function  __construct()
    {
        $this->IsFunction = true;
        parent::__construct();
        
    }
    /** function for create database function*/
    public function CreateFunction()
    {
        try{
            \dibi::query("DROP FUNCTION IF EXISTS $this->ObjectName");
            \dibi::query($this->FunctionCode);
        }
        catch (Exception $ex)
        {
            \Kernel\Page::ApplicationError($ex);
        }
    }   
}   
