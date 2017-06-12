<?php
namespace Components;
class Instagram extends UserComponents{
    
    public $AccessToken = "";
    public $InstagramUserName = "";
    
    
    public function __construct() {
        $this->LinkJavascript = true;
        //$this->InsertJavascriptToContent = true;
        $this->Type = "Instagram";
        $this->LoadHtml = true;
        $this->AutoReplaceString =true;
        parent::__construct();   
    }     
    
    public function GetComponentHtml()
    {
  
  //      return "";
        $this->AccessToken = "4315292716.1677ed0.136e02f3196c483780613d5595d513ea";
        $this->InstagramUserName = "sugarshot_store";
        $instagram = new \xweb_plugins\Instagram($this->AccessToken);
        $data = $instagram->GetInstagramPhotosByUserName($this->InstagramUserName);
        print_r($data);
        
    }
    
    
}
