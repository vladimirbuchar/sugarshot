<?php
namespace Types;
class ExportSettings {
    public $ColumnName;
    public $ShowName;
    
    public function __construct($columnName,$showName)
    {   
        $this->ColumnName = $columnName;
        $this->ShowName = $showName;
    }
}


