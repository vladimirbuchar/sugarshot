<?php
namespace Kernel; 

class RenderUserComponent{
    private $_type;
    private $_parametrs = array();
    private $_obj = null;
    private $_js ="";
    
    public function __construct ()
    {
        
    }
    public function SetParametrs($key,$value)
    {
        if ($key == "Type")
        {
            $this->_type = "Components\\".$value;
            $this->_js = $value;
            //echo $value."\n";
        }
        else 
        {
            $this->_parametrs[$key] = $value;
        }
    }
    
        
    public function RenderHtml()
    {
        try{
            
            $this->_obj = new $this->_type();
            
            if (!empty($this->_parametrs))
            {
                foreach ($this->_parametrs as $key => $value)
                {
                    $this->_obj->$key = $value;
                }
            }
            
            
            return $this->_obj->LoadComponent($this->_obj);
        }
        catch (Exception $ex)
        {
            \Kernel\Page::ApplicationError($ex);
        }
         
    }
    public function LinkJavascript()
    {
        $script ="";
        if ($this->_obj != null)
        {
            
            if (!$this->_obj->InsertJavascriptToContent())
            {
                
                $scripts = $this->_obj->GetOtherScripts();
                if (!empty($scripts))
                {
                    foreach ($scripts as $row)
                    {
                        $script .=  '<script type="text/javascript"  src="'.$row.'"></script>';
                    }
                } 
                $file =  "/Scripts/Components/".$this->_js.".js";
                //echo $file;
                if (Files::FileExists(ROOT_PATH.$file))
                {   
                    
                    $script .=  '<script type="text/javascript"  src="'.$file.'"></script>';
                }
            }
        }
        
        return $script;
    }
    
    public function LinkCss()
    {
        $css ="";
        if ($this->_obj != null)
        {
            $csss= $this->_obj->GetOtherCss();
            if (!empty($csss))
                {
                    foreach ($csss as $row)
                    {
                        $css .=  '<link rel="stylesheet" type="text/css"  href="'.$row.'"></script>';
                    }
                }
        }
        return $css;
    }
    public function ReplaceComponetString()
    {
        if ($this->_obj != null)
        {
            return $this->_obj->GetReplaceString();
        }
    }
    
    public function IsEmptyComponent()
    {
        if ($this->_obj != null)
        {
            return $this->_obj->IsEmptyComponent;
        }
        return false;
    }
    
    public function  GetIdComponent()
    {
        if ($this->_obj != null)
        {
            return $this->_obj->Id;
        }
        return "";
    }
    
    public function CacheComponent()
    {
        
        if ($this->_obj != null)
        {
            return $this->_obj->IsCache;
        }
        return false;
    }
}