<?php
namespace HtmlComponents;
class Link extends HtmlComponents {
    public $Href="";
    public $Type ="href";
    public function __construct()
    {
        $this->HtmlTag = "a";
    }
}



