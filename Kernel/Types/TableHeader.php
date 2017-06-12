<?php
namespace Types;
class TableHeader{
    public $ShowName;
    public $ColumnName;
    public $FiltrType;
    public $Value1;
    public $Value2;
    public $Value3;
    public function __construct($showName,$columnName,$filtrType="")
    {
        $this->ShowName = $showName;
        $this->ColumnName = $columnName;
        $this->FiltrType = $filtrType;
    }
}
