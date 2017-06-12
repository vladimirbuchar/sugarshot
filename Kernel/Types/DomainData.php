<?php
namespace Types;
class DomainData {
    public $ItemId;
    public $ValueId;
    public $Value;
    
    public function __construct($itemId,$valueId,$value)
    {   
        $this->ItemId = $itemId;
        $this->ValueId= $valueId;
        $this->Value = $value;

    }
}


