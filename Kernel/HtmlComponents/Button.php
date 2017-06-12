<?php
namespace HtmlComponents;
class Button extends HtmlComponents {
    public function __construct($mode ="input")
    {
        $this->HtmlTag = $mode;
        $this->Type = "button";
    }
}



