<?php
namespace Model;
class DiscusionItemsList extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "DiscusionItemsList";
        $this->SqlView = "SELECT * FROM `DiscusionItems` WHERE IsLast = 1 AND Deleted = 0 ";
                
    }
    public function TableExportSettings()
    {
        
    }
}
