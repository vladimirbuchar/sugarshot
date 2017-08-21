<?php

namespace Objects;
use Types\DataTableColumn;

class WordGroups extends ObjectManager{
    private static $_webInfo = array();
    public function __construct() {
        parent::__construct();

    }
    
    public function AddColumnLang($wordIndetificator)
    {
        $model =  \Model\WordGroups::GetInstance();
        if (!empty($wordIndetificator))
        {
            $model->AddColumn(new DataTableColumn("Word$wordIndetificator", \Types\DataColumnsTypes::TEXT, "", true));
            $model->SaveNewColums();
        }
    }
}
