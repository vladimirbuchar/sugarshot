<?php
namespace Types;
class UserDomainValue {
    public $ValueId;
    public $ItemId;
    public $Value;
    
    public function __construct($ValueId,$ItemId,$Value)
    {   
        $this->ValueId = $ValueId;
        $this->ItemId = $ItemId;
        $this->Value = $Value;
    }
}


