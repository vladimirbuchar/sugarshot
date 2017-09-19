<?php

namespace Objects;
use Types\DataTableColumn;

class WordGroups extends ObjectManager{
    public function __construct() {
        parent::__construct();

    }
    
    public function AddColumnLang($parametrs)
    {
        $wordIndetificator = $parametrs["LangIdentificator"];
        $model =  \Model\WordGroups::GetInstance();
        if (!empty($wordIndetificator))
        {
            $model->AddColumn(new DataTableColumn("Word$wordIndetificator", \Types\DataColumnsTypes::TEXT, "", true));
            $model->SaveNewColums();
        }
    }
}
