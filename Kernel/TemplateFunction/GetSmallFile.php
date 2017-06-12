<?php
namespace TemplateFunction; 

class GetSmallFile extends GetFile {
    
    public static  function CallFunction() {
        return self::GetFilePath("s");
        
    }
    
    
    
    
}