<?php
namespace Components;
use HtmlComponents\Div;
use HtmlComponents\Button;
use HtmlComponents\Span;
use HtmlComponents\H4;
class BootstrapDialog extends UserComponents {
    public $DialogId = "";
    public $DialogTitle ="";
    public $DialogContent ="";
    public $SaveButtonText ="";
    public $CancelButtonText="";
    
    public function __construct()
    {
        
        $this->Type = "BootstrapDialog";
        parent::__construct();
    }
    public function GetComponentHtml()
    {
        $divParent = new Div();
        $divParent->CssClass = "modal fade";
        $divParent->Id= $this->DialogId;
        $divParent->TabIndex = "-1";
        $divParent->Role = "dialog";
        $divParent->Arialabelledby="myModalLabel";
        $divParent->Ariahidden="true";
        
        $divModalDialog = new Div();
        $divModalDialog->CssClass= "modal-dialog";
        
        $divDialogContent = new Div();
        $divDialogContent->CssClass= "modal-content";
        
        $divDialogHeader = new Div();
        $divDialogHeader->CssClass="modal-header";
        
        $closeButton = new Button("button");
        $closeButton->CssClass="close";
        $closeButton->Datadismiss="modal";
        $closeButton->Arialabel="close";
        $span = new Span();
        $span->Ariahidden="true";
        $span->Html="&times;";
        $closeButton->Html = $span->RenderHtml();
        
        $h4 = new H4();
        $h4->Html = $this->DialogTitle;
        
        $divModalBody =  new Div();
        $divModalBody->CssClass="modal-body";
        $divModalBody->Html = $this->DialogContent;
        $dialogButtons = new Div();
        $dialogButtons->CssClass="modal-footer";
        
        if (!empty($this->SaveButtonText))
        {
            $saveButton = new Button("button");
            $saveButton->CssClass="btn btn-primary";
            $saveButton->OnClick="CloseDialog('$this->DialogId')";
            $saveButton->Html = $this->SaveButtonText;
        }
        $cancelButton = new Button("button");
        $cancelButton->CssClass="btn btn-primary";
        $cancelButton->OnClick="CloseDialog('$this->DialogId')";
        $cancelButton->Html = $this->CancelButtonText;
        $cancelButton->Datadismiss = "modal";
        
        $divDialogHeader->SetChild($h4);
        $divDialogHeader->SetChild($closeButton);
        
        $divDialogContent->SetChild($divDialogHeader);
        $divDialogContent->SetChild($divModalBody);
        
        if (!empty($this->SaveButtonText))
            $dialogButtons->SetChild($saveButton);
        $dialogButtons->SetChild($cancelButton);
        $divDialogContent->SetChild($dialogButtons);
        
        $divModalDialog->SetChild($divDialogContent);
        
        $divParent->SetChild($divModalDialog);
        return $divParent->RenderHtml();
       
    }
}



