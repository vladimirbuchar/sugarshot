<?php

namespace Model;

use Types\DataTableColumn;

class UserDomainsAddiction  extends DatabaseTable implements \Inteface\iDataTable{
    public $DomainId;
    public $AddictionName;
    public $Item1;
    public $Item1Value;
    public $ItemX;
    public $ItemXValue;
    public $ActionName;
    public $RuleName;
    public $Priority; 
    
    public $IsDomain1;
    public $DomainId1;
    public $ItemId1;
    
    public $IsDomainX;
    public $DomainIdX;
    public $ItemIdX;
    //private static $_instance = null;
     
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "UserDomainsAddiction";     
        $this->SetSelectColums(array("DomainId","AddictionName","Item1","Item1Value","ItemX","ItemXValue","ActionName","RuleName","Priority"," IsDomain1","DomainId1","ItemId1","IsDomainX","DomainIdX","ItemIdX"));
        $this->SetDefaultSelectColumns();
        
    }
    
        
    public function OnCreateTable() {
        $this->AddColumn(new DataTableColumn("DomainId", \Types\DataColumnsTypes::INTEGER, 0, false, 9));
        $this->AddColumn(new DataTableColumn("AddictionName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("Item1", \Types\DataColumnsTypes::VARCHAR, "", false, 20));
        $this->AddColumn(new DataTableColumn("Item1Value", \Types\DataColumnsTypes::VARCHAR, "", FALSE, 255));
        $this->AddColumn(new DataTableColumn("ItemX", \Types\DataColumnsTypes::VARCHAR, "", false, 20));
        $this->AddColumn(new DataTableColumn("ItemXValue", \Types\DataColumnsTypes::VARCHAR, "", false, 255));
        $this->AddColumn(new DataTableColumn("ActionName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("RuleName", \Types\DataColumnsTypes::VARCHAR, "", false, 50));
        $this->AddColumn(new DataTableColumn("Priority", \Types\DataColumnsTypes::INTEGER, 1, false, 9));
        $this->AddColumn(new DataTableColumn("IsDomain1", \Types\DataColumnsTypes::BIT, 0, true));
        $this->AddColumn(new DataTableColumn("DomainId1", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("ItemId1", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("IsDomainX", \Types\DataColumnsTypes::BIT, 0, true));
        $this->AddColumn(new DataTableColumn("DomainIdX", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
        $this->AddColumn(new DataTableColumn("ItemIdX", \Types\DataColumnsTypes::INTEGER, 0, true, 9));
    }
    
    public function InsertDefaultData() {
        $this->Setup();
    }

    
    public function SetValidate($mode = false) {
        
    }
    public function TableMigrate()
    {
        
    }
    public function TableExportSettings()
    {
        
    }

}
