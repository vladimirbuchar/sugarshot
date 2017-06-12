<?php
namespace HtmlComponents;
class HiddenInput extends HtmlComponents {
    public function __construct()
    {
        $this->HtmlTag = "input";
        $this->Type = "hidden";
    }
}



