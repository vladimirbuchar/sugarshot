<?php
namespace Model;
class FrontendDetail extends DatabaseViews{
    public function __construct()
    {
        
        
        parent::__construct();
        $this->ObjectName = "FrontendDetail";
        $this->SqlView = "SELECT Date,Sort, SaveToCache,NoLoadSubItems,ActivatePager, FirstItemLoadPager, NextItemLoadPager,TemplateId,Id, GroupId,WebId,LangId,Name,SeoUrl,AvailableOverSeoUrl,Data,Header,ActiveFrom,ContentType,  
                ActiveTo,NoIncludeSearch,Identificator,ParentId,IsActive,IsLast FROM FRONTENDDETAILPREVIEW WHERE FRONTENDDETAILPREVIEW.IsActive = 1";
    }
    public function TableExportSettings()
    {
        
    }


}
