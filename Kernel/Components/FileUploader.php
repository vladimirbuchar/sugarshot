<?php
namespace Components;
use HtmlComponents\Div;
use HtmlComponents\Span;
use HtmlComponents\HiddenInput;
use HtmlComponents\FileUpload;
use HtmlComponents\Button;
use HtmlComponents\FontAwesome;
class FileUploader extends UserComponents{
    
    public $Id;
    private $_filePath;
    private $_isUploadedFile = false;
    public $MultiUpload = false;
    
    public function __construct($filePath ="") {
        $this->_filePath = $filePath;
        $this->IgnoreCache =true;
        if (!empty($this->_filePath) && $this->_filePath !="")
        {
            $this->_isUploadedFile  = true;
        }
        parent::__construct();
    }
    
    public function GetComponentHtml() {
        $div  =new Div();
        $div->CssClass="row";
        $span = new Span();
        $span->Id = $this->Id."_label";
        $span->CssClass =$this->_isUploadedFile ? " col-md-2":" dn ";
        
        $spanFilePath = new HiddenInput();
        $spanFilePath->Id = $this->Id."_filepath";
        $spanFilePath->CssClass = "noDatabase";
        if ($this->_isUploadedFile)
        {
            $file = "/".$this->_filePath;
            $fileinfo = pathinfo($file);
            $span->Html = $fileinfo["basename"];
            $spanFilePath->Value = $this->_filePath;
        }
        $fu = new FileUpload();
        $fu->CssClass = "col-md-3";
        $fu->Id = $this->Id;
        $fu->Multiple = $this->MultiUpload;
        $div->SetChild($fu);
        $div->SetChild($span);
        $div->SetChild($spanFilePath);
        
        $button =  new Button("button");
        $button->Id = $this->Id."_remove";
        $button->CssClass =$this->_isUploadedFile ?  "btn col-md-1":" dn ";
        $button->OnClick = "FileDelete('$this->Id')";
        $fa = new FontAwesome();
        $fa->SetIcon("times");
        $button->Html = $fa->RenderHtml();
        $div->SetChild($button);
        return $div->RenderHtml();
        
        
    }
}
