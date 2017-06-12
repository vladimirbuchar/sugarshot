<?php
namespace TemplateFunction; 

class GetMediumFile extends GetFile {
    
    public static function CallFunction() {
        return self::GetFilePath("m");
        
    }
    
    
    
    
}