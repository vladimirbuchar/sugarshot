<?php
namespace Types;
class CallModelFunction {
    public $Class;
    public $Function;
    public $Parametrs;
    public $Type;
    
    public function __construct($class,$function,$parametrs,$type)
    {   
        $this->Class = $class;
        $this->Function = $function;
        $this->Parametrs = $parametrs;
        $this->Type = $type;
        
    }
}


