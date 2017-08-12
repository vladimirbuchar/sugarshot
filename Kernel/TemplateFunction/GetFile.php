<?php

namespace TemplateFunction; 
 
class GetFile extends TemplateFunction {
    protected static function GetFilePath($mode)
    {
        $file = empty(self::$Parametrs) ?"" : self::$Parametrs[0];
        $oldFile = $file;
        $noImage = "Images/noimage.png";
        if (!empty($file))
            $file = str_replace(".", "_$mode.", $file);
        else 
        {
            return $noImage;
        }
        
        if (!\Utils\Files::FileExists(ROOT_PATH.$file))
        {
            if (\Utils\Files::FileExists(ROOT_PATH.$oldFile))
            {
                return $oldFile;
            }
            return $noImage;
        }
        return $file;
    }
}
