<?php
namespace Types;
class SortDatabase{
    public $SortType;
    public $ColumnName;
    public function __construct ($sortType,$columnName)
    {
        $this->SortType = $sortType;
        $this->ColumnName = $columnName;
    }
}
