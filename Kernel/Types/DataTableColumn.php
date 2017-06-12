<?php
namespace Types;
class DataTableColumn {
    public $Name;
    public $Type;
    public $DefaultValue="";
    public $IsNull = true;
    public $Length = 999999;
    public $IsAutoIncrement = false; 
    public $Key = "";
    public $Mode;
    public function __construct($name = "",$type="",$defaultValue="",$isnull = true, $length = 99999,$isautoincrement = false,$key = "")
    {   
        $this->Mode = AlterTableMode::$AddColumn;
        $this->Name =$name;
        $this->Type = $type;
        $this->DefaultValue = $defaultValue;
        $this->IsNull = $isnull;
        $this->Length = $length;
        $this->IsAutoIncrement = $isautoincrement;
        $this->Key = $key;
        
    }
}

class  KeyType{
    public static $PrimaryKey = "PRIMARY KEY";
    public static $INDEX   = "INDEX";
    public static $UNIQUE   = "UNIQUE";
    public static $FULLTEXT ="FULLTEXT";
}
class AlterTableMode{
    public static $AddColumn = "ADD COLUMN";
}
