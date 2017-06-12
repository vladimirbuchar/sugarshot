<?php
namespace Model;
class FrontendDetail extends DatabaseViews{
    public function __construct()
    {
        parent::__construct();
        $this->ObjectName = "FrontendDetail";
        $this->SqlView = "SELECT * FROM FRONTENDDETAILPREVIEW WHERE FRONTENDDETAILPREVIEW.IsActive = 1";
    }
    public function TableExportSettings()
    {
        
    }


}
