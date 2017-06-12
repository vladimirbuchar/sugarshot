<?php
namespace Types;
class ModelRule {
    public $Column = "";
    public $RuleType = "";
    public $ErrorMessage = "";
    public function __construct($column,$ruleType,$errorMessge)
    {
        $this->Column = $column;
        $this->RuleType = $ruleType;
        $this->ErrorMessage = $errorMessge;
    }
}


