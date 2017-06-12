<?php
namespace HtmlComponents;
class FontAwesome extends HtmlComponents {
    public function __construct()
    {
        $this->HtmlTag = "i";   
    }
    public function SetIcon($iconName)
    {
        $this->CssClass = "fa fa-".$iconName;
        //return $this->RenderHtml();
    }
}



